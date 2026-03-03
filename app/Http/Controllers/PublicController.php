<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Section;
use App\Models\Article;
use App\Models\HomeBlock;
use App\Models\Setting;
use App\Models\ContactRequest;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class PublicController extends Controller
{
    public function __construct()
    {
        // Share standard visible sections with all public views for the navigation menu
        $shared_sezioni = Section::where('visibile', true)->orderBy('ordine')->get();
        View::share('shared_sezioni', $shared_sezioni);

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
            'image' => $this->global_seo['home_seo_image'] ?: asset('img/default-share.jpg'),
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
            'image' => $sezione->seo_image ?: $sezione->foto ?: $this->global_seo['home_seo_image'] ?: asset('img/default-share.jpg'),
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
            'image' => $articolo->seo_image ?: $articolo->foto ?: $this->global_seo['home_seo_image'] ?: asset('img/default-share.jpg'),
            'url' => url()->current()
        ];

        return view('public.articolo', compact('articolo', 'seo'));
    }

    public function submitContactForm(Request $request)
    {
        $validated = $request->validate([
            'ragione_sociale' => 'nullable|string|max:255',
            'nome' => 'required|string|max:255',
            'cognome' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'email' => 'required|email|max:255',
            'richiesta' => 'required|string',
        ]);

        ContactRequest::create($validated);

        return redirect()->back()->with('success', 'Grazie per averci contattato! La tua richiesta è stata inviata con successo. Ti risponderemo il prima possibile.');
    }
}
