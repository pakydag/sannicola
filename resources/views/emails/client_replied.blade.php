<x-mail::message>
# Nuovo Messaggio dal Cliente

Il cliente **{{ $contactRequest->nome }} {{ $contactRequest->cognome }}** ha risposto alla richiesta di contatto.

**Messaggio:**
{{ $messageText }}

Clicca sul pulsante sottostante per visualizzare la conversazione completa ed inserire una nuova risposta nel gestionale:

<x-mail::button :url="url('/amministrazione/contatti/' . $contactRequest->id)">
Apri nel Gestionale
</x-mail::button>

Grazie,<br>
{{ config('app.name') }}
</x-mail::message>
