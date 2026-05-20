<x-mail::message>
# Nuova Richiesta Noleggio Auto

Hai ricevuto una nuova richiesta di noleggio auto dal sito web. Di seguito i dettagli:

**Dati Cliente:**
- **Nome:** {{ $data['nome'] }} {{ $data['cognome'] }}
- **Email:** {{ $data['email'] }}
- **Telefono:** {{ $data['telefono'] }}

**Dettagli Noleggio:**
- **Data e Orario Ritiro:** {{ \Carbon\Carbon::parse($data['data_ritiro'])->format('d/m/Y') }} alle {{ $data['orario_ritiro'] }}
- **Data e Orario Riconsegna:** {{ \Carbon\Carbon::parse($data['data_riconsegna'])->format('d/m/Y') }} alle {{ $data['orario_riconsegna'] }}
- **Numero Posti Richiesti:** {{ $data['numero_posti'] }}

@if(!empty($data['messaggio']))
**Messaggio / Richieste particolari:**
{{ $data['messaggio'] }}
@endif

<x-mail::button :url="config('app.url')">
Vai al Sito
</x-mail::button>

Grazie,<br>
{{ config('app.name') }}
</x-mail::message>
