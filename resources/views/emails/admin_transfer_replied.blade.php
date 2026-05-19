<x-mail::message>
# Nuova Risposta per la Richiesta Transfer

Gentile {{ $transferRequest->nome }},
hai ricevuto una risposta in merito alla tua richiesta di transfer:

**Risposta:**
{{ $messageText }}

Per visualizzare l'intera conversazione, scaricare gli allegati o inviare una nuova risposta, clicca sul pulsante sottostante:

<x-mail::button :url="route('public.transfer.thread', ['token' => $transferRequest->secure_token])">
Apri la Conversazione
</x-mail::button>

Se il pulsante non funziona, puoi copiare e incollare questo link nel tuo browser:
{{ route('public.transfer.thread', ['token' => $transferRequest->secure_token]) }}

Grazie,<br>
{{ config('app.name') }}
</x-mail::message>
