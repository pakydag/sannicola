<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Section;
use App\Models\Article;
use App\Models\HomeBlock;
use App\Models\Setting;
use App\Models\ContactRequest;
use App\Models\TransferRequest;
use App\Models\CarRentalRequest;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class PublicController extends Controller
{
    public function __construct()
    {
        // Let AppServiceProvider handle shared_sezioni to ensure it's loaded for all public views

        // Fetch Global SEO Settings once
        $this->global_seo = [
            'home_seo_title' => Setting::where('key', 'home_seo_title')->value('value'),
            'home_seo_description' => Setting::where('key', 'home_seo_description')->value('value'),
            'home_seo_image' => Setting::where('key', 'home_seo_image')->value('value'),
        ];
    }

    public function home()
    {
        // Example: load latest 6 articles that belong to visible sections
        $ultimi_articoli = Article::where('visibile', true)->whereHas('section', function ($q) {
            $q->where('visibile', true);
        })->orderBy('ordine')->latest()->take(6)->get();

        // Carica i blocchi drag & drop della home
        $homeBlocks = HomeBlock::with('globalWidget')->orderBy('ordine')->get();

        $seo = [
            'title' => $this->global_seo['home_seo_title'] ?: config('app.name', 'Sito Web'),
            'description' => $this->global_seo['home_seo_description'] ?: 'Benvenuti sul nostro sito web ufficiale.',
            'image' => $this->global_seo['home_seo_image'] ? asset($this->global_seo['home_seo_image']) : asset('img/default-share.jpg'),
            'url' => url()->current()
        ];

        return view('public.home', compact('ultimi_articoli', 'homeBlocks', 'seo'));
    }

    public function sezione($slug)
    {
        $sezione = Section::where('slug', $slug)
                          ->where('visibile', true)
                          ->firstOrFail();

        // Se la sezione è di tipo "pagina", eseguiamo un redirect diretto al suo primo articolo
        if ($sezione->tipo === 'pagina') {
            $primo_articolo = $sezione->articles()->where('visibile', true)->latest()->first();
            
            if ($primo_articolo) {
                // Redirect alla vista articolo
                $articolo_slug = $primo_articolo->slug ?? $primo_articolo->id . '-it';
                return redirect()->route('public.articolo', ['sezione_slug' => $sezione->slug, 'articolo_slug' => $articolo_slug]);
            }
        }

        $articoli = $sezione->articles()->where('visibile', true)->orderBy('ordine')->latest()->paginate(9);

        // Prepare SEO for Section (lists)
        $seo = [
            'title' => $sezione->seo_title ?: ($sezione->nome . ' - ' . ($this->global_seo['home_seo_title'] ?: config('app.name'))),
            'description' => $sezione->seo_description ?: Str::limit(strip_tags($sezione->contenuto), 160) ?: ($this->global_seo['home_seo_description'] ?? 'Esplora i contenuti di ' . $sezione->nome),
            'image' => $sezione->seo_image ? asset($sezione->seo_image) : ($sezione->foto ? asset($sezione->foto) : ($this->global_seo['home_seo_image'] ? asset($this->global_seo['home_seo_image']) : asset('img/default-share.jpg'))),
            'url' => url()->current()
        ];

        return view('public.sezione', compact('sezione', 'articoli', 'seo'));
    }

    public function articolo($sezione_slug, $articolo_slug)
    {
        $sezione = Section::where('slug', $sezione_slug)
                          ->where('visibile', true)
                          ->firstOrFail();

        // Fallback: cerca l'articolo per slug testuale O per ID se lo slug è nel formato 'id-it'
        $articolo = Article::where('visibile', true)
                           ->where(function($query) use ($articolo_slug) {
                               $query->where('slug', $articolo_slug)
                                     ->orWhere(function($query) use ($articolo_slug) {
                                         if (preg_match('/^(\d+)-it$/', $articolo_slug, $matches)) {
                                             $query->where('id', $matches[1]);
                                         }
                                     });
                           })
                           ->firstOrFail();

        // Prepare SEO for Article
        $seo = [
            'title' => $articolo->seo_title ?: ($articolo->titolo . ' - ' . ($this->global_seo['home_seo_title'] ?: config('app.name'))),
            'description' => $articolo->seo_description ?: Str::limit(strip_tags($articolo->contenuto), 160) ?: ($this->global_seo['home_seo_description'] ?? 'Leggi l\'articolo ' . $articolo->titolo),
            'image' => $articolo->seo_image ? asset($articolo->seo_image) : ($articolo->foto ? asset($articolo->foto) : ($this->global_seo['home_seo_image'] ? asset($this->global_seo['home_seo_image']) : asset('img/default-share.jpg'))),
            'url' => url()->current()
        ];

        return view('public.articolo', compact('articolo', 'seo'));
    }

    public function submitContactForm(Request $request)
    {
        $request->validate([
            'privacy' => 'required|accepted',
        ]);

        $validated = $request->validate([
            'ragione_sociale' => 'nullable|string|max:255',
            'nome' => 'required|string|max:255',
            'cognome' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'email' => 'required|email|max:255',
            'richiesta' => 'required|string',
        ]);

        $recipient = config('mail.from.address');
        if ($recipient) {
            try {
                \Illuminate\Support\Facades\Mail::to($recipient)->send(new \App\Mail\ContactRequestMail($validated));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Failed to send contact request email: " . $e->getMessage());
            }
        }

        ContactRequest::create($validated);

        $successMessage = app()->getLocale() === 'en' 
            ? 'Thank you for contacting us! Your request has been sent successfully. We will reply as soon as possible.'
            : 'Grazie per averci contattato! La tua richiesta è stata inviata con successo. Ti risponderemo il prima possibile.';

        return redirect()->back()->with('success', $successMessage);
    }

    public function submitTransferForm(Request $request)
    {
        $request->validate([
            'privacy' => 'required|accepted',
        ]);

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'cognome' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telefono' => 'required|string|max:20',
            'luogo_arrivo' => 'required|string|max:255',
            'data' => 'required|date',
            'orario' => 'required|string',
            'numero_persone' => 'required|integer|min:1',
            'andata_ritorno' => 'nullable|boolean',
            'data_ritorno' => 'nullable|required_if:andata_ritorno,1|date',
            'orario_ritorno' => 'nullable|required_if:andata_ritorno,1|string',
            'messaggio' => 'nullable|string',
        ]);

        $recipient = config('mail.from.address');
        if ($recipient) {
            \Illuminate\Support\Facades\Mail::to($recipient)->send(new \App\Mail\TransferRequestMail($validated));
        }

        TransferRequest::create($validated);

        $successMessage = app()->getLocale() === 'en'
            ? 'Thank you! Your transfer request has been sent successfully. We will reply as soon as possible.'
            : 'Grazie! La tua richiesta di transfer è stata inviata con successo. Ti risponderemo al più presto.';

        return redirect()->back()->with('transfer_success', $successMessage);
    }

    public function submitCarRentalForm(Request $request)
    {
        $request->validate([
            'privacy' => 'required|accepted',
        ]);

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'cognome' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telefono' => 'required|string|max:20',
            'data_ritiro' => 'required|date',
            'orario_ritiro' => 'required|string',
            'data_riconsegna' => 'required|date',
            'orario_riconsegna' => 'required|string',
            'numero_posti' => 'required|integer|min:1|max:9',
            'messaggio' => 'nullable|string',
        ]);

        $recipient = config('mail.from.address');
        if ($recipient) {
            \Illuminate\Support\Facades\Mail::to($recipient)->send(new \App\Mail\CarRentalRequestMail($validated));
        }

        CarRentalRequest::create($validated);

        $successMessage = app()->getLocale() === 'en'
            ? 'Thank you! Your car rental request has been sent successfully. We will reply as soon as possible.'
            : 'Grazie! La tua richiesta di noleggio auto è stata inviata con successo. Ti risponderemo al più presto.';

        return redirect()->back()->with('car_rental_success', $successMessage);
    }

    public function viewContactThread($token)
    {
        $contactRequest = ContactRequest::where('secure_token', $token)->firstOrFail();

        $seo = [
            'title' => (app()->getLocale() === 'en' ? 'Conversation Thread' : 'Conversazione Richiesta') . ' - ' . config('app.name'),
            'description' => 'Visualizza la conversazione in merito alla richiesta di contatto.',
            'image' => null,
            'url' => url()->current()
        ];

        return view('public.contact_thread', compact('contactRequest', 'seo'));
    }

    public function replyContactThread(Request $request, $token)
    {
        $contactRequest = ContactRequest::where('secure_token', $token)->firstOrFail();

        $request->validate([
            'message' => 'required|string',
            'attachment' => 'nullable|file|max:10240', // 10MB limit
        ]);

        $attachmentPath = null;
        $attachmentName = null;

        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('attachments', 'public');
            $attachmentName = $request->file('attachment')->getClientOriginalName();
        }

        $message = \App\Models\ContactMessage::create([
            'contact_request_id' => $contactRequest->id,
            'sender' => 'client',
            'message' => $request->message,
            'attachment_path' => $attachmentPath,
            'attachment_name' => $attachmentName,
        ]);

        // Imposta letto a false per notificare l'amministratore nel gestionale
        $contactRequest->update(['letto' => false]);

        // Invia email all'amministratore del sito
        $recipient = config('mail.from.address');
        if ($recipient) {
            try {
                \Illuminate\Support\Facades\Mail::to($recipient)->send(
                    new \App\Mail\ClientRepliedMail($contactRequest, $request->message, $attachmentPath, $attachmentName)
                );
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Failed to send client reply notification: " . $e->getMessage());
            }
        }

        $successMsg = app()->getLocale() === 'en'
            ? 'Your reply has been sent successfully.'
            : 'La tua risposta è stata inviata con successo.';

        return redirect()->back()->with('success', $successMsg);
    }

    public function viewTransferThread($token)
    {
        $transferRequest = \App\Models\TransferRequest::where('secure_token', $token)->firstOrFail();
        $transferRequest->load('messages');

        $seo = [
            'title' => (app()->getLocale() === 'en' ? 'Transfer Conversation' : 'Conversazione Transfer') . ' - ' . config('app.name'),
            'description' => 'Visualizza la conversazione in merito alla richiesta di transfer.',
            'image' => null,
            'url' => url()->current()
        ];

        return view('public.transfer_thread', compact('transferRequest', 'seo'));
    }

    public function replyTransferThread(Request $request, $token)
    {
        $transferRequest = \App\Models\TransferRequest::where('secure_token', $token)->firstOrFail();

        $request->validate([
            'message' => 'required|string',
            'attachment' => 'nullable|file|max:10240', // 10MB
        ]);

        $attachmentPath = null;
        $attachmentName = null;

        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('attachments', 'public');
            $attachmentName = $request->file('attachment')->getClientOriginalName();
        }

        \App\Models\TransferMessage::create([
            'transfer_request_id' => $transferRequest->id,
            'sender' => 'client',
            'message' => $request->message,
            'attachment_path' => $attachmentPath,
            'attachment_name' => $attachmentName,
        ]);

        $transferRequest->update(['letto' => false]);

        $recipient = config('mail.from.address');
        if ($recipient) {
            try {
                \Illuminate\Support\Facades\Mail::to($recipient)->send(
                    new \App\Mail\ClientTransferRepliedMail($transferRequest, $request->message, $attachmentPath, $attachmentName)
                );
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Failed to send client transfer reply notification: " . $e->getMessage());
            }
        }

        $successMsg = app()->getLocale() === 'en'
            ? 'Your reply has been sent successfully.'
            : 'La tua risposta è stata inviata con successo.';

        return redirect()->back()->with('success', $successMsg);
    }

    public function viewCarRentalThread($token)
    {
        $carRentalRequest = \App\Models\CarRentalRequest::where('secure_token', $token)->firstOrFail();
        $carRentalRequest->load('messages');

        $seo = [
            'title' => (app()->getLocale() === 'en' ? 'Rental Conversation' : 'Conversazione Noleggio') . ' - ' . config('app.name'),
            'description' => 'Visualizza la conversazione in merito alla richiesta di noleggio auto.',
            'image' => null,
            'url' => url()->current()
        ];

        return view('public.car_rental_thread', compact('carRentalRequest', 'seo'));
    }

    public function replyCarRentalThread(Request $request, $token)
    {
        $carRentalRequest = \App\Models\CarRentalRequest::where('secure_token', $token)->firstOrFail();

        $request->validate([
            'message' => 'required|string',
            'attachment' => 'nullable|file|max:10240', // 10MB
        ]);

        $attachmentPath = null;
        $attachmentName = null;

        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('attachments', 'public');
            $attachmentName = $request->file('attachment')->getClientOriginalName();
        }

        \App\Models\CarRentalMessage::create([
            'car_rental_request_id' => $carRentalRequest->id,
            'sender' => 'client',
            'message' => $request->message,
            'attachment_path' => $attachmentPath,
            'attachment_name' => $attachmentName,
        ]);

        $carRentalRequest->update(['letto' => false]);

        $recipient = config('mail.from.address');
        if ($recipient) {
            try {
                \Illuminate\Support\Facades\Mail::to($recipient)->send(
                    new \App\Mail\ClientCarRentalRepliedMail($carRentalRequest, $request->message, $attachmentPath, $attachmentName)
                );
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Failed to send client car rental reply notification: " . $e->getMessage());
            }
        }

        $successMsg = app()->getLocale() === 'en'
            ? 'Your reply has been sent successfully.'
            : 'La tua risposta è stata inviata con successo.';

        return redirect()->back()->with('success', $successMsg);
    }
}
