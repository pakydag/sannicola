<x-mail::message>
# Nuovo Messaggio per la Richiesta Noleggio Auto

Il cliente **{{ $carRentalRequest->nome }} {{ $carRentalRequest->cognome }}** ha risposto alla richiesta di noleggio auto.

**Messaggio:**
{{ $messageText }}

Clicca sul pulsante sottostante per visualizzare la conversazione completa ed inserire una nuova risposta nel gestionale:

<x-mail::button :url="url('/amministrazione/car-rentals/' . $carRentalRequest->id)">
Apri nel Gestionale
</x-mail::button>

Grazie,<br>
{{ config('app.name') }}
</x-mail::message>
