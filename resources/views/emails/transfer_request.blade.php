<x-mail::message>
# Nuova Richiesta Transfer

Hai ricevuto una nuova richiesta di transfer dal sito web. Di seguito i dettagli:

**Dati Cliente:**
- **Nome:** {{ $data['nome'] }} {{ $data['cognome'] }}
- **Email:** {{ $data['email'] }}
- **Telefono:** {{ $data['telefono'] }}

**Dettagli Transfer:**
- **Luogo di Arrivo:** {{ $data['luogo_arrivo'] }}
- **Data Arrivo:** {{ \Carbon\Carbon::parse($data['data'])->format('d/m/Y') }}
- **Orario Arrivo:** {{ $data['orario'] }}
- **Numero di persone:** {{ $data['numero_persone'] }}

@if(isset($data['andata_ritorno']) && $data['andata_ritorno'])
**Dettagli Ritorno:**
- **Data Ritorno:** {{ isset($data['data_ritorno']) ? \Carbon\Carbon::parse($data['data_ritorno'])->format('d/m/Y') : 'Non specificata' }}
- **Orario Ritorno:** {{ $data['orario_ritorno'] ?? 'Non specificato' }}
@endif

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
