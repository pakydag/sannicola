<x-mail::message>
# Nuovo Messaggio per la Richiesta Transfer

Il cliente **{{ $transferRequest->nome }} {{ $transferRequest->cognome }}** ha risposto alla richiesta transfer.

**Messaggio:**
{{ $messageText }}

Clicca sul pulsante sottostante per visualizzare la conversazione completa ed inserire una nuova risposta nel gestionale:

<x-mail::button :url="url('/amministrazione/transfers/' . $transferRequest->id)">
Apri nel Gestionale
</x-mail::button>

Grazie,<br>
{{ config('app.name') }}
</x-mail::message>
