<x-mail::message>
# Nuova Risposta dall'Amministratore

Gentile {{ $contactRequest->nome }},
hai ricevuto una risposta in merito alla tua richiesta di contatto:

**Risposta:**
{{ $messageText }}

Per visualizzare l'intera conversazione, scaricare gli allegati o inviare una nuova risposta, clicca sul pulsante sottostante:

<x-mail::button :url="route('public.contact.thread', ['token' => $contactRequest->secure_token])">
Apri la Conversazione
</x-mail::button>

Se il pulsante non funziona, puoi copiare e incollare questo link nel tuo browser:
{{ route('public.contact.thread', ['token' => $contactRequest->secure_token]) }}

Grazie,<br>
{{ config('app.name') }}
</x-mail::message>
