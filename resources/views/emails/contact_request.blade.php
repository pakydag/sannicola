<x-mail::message>
# Nuova Richiesta di Contatto

Hai ricevuto una nuova richiesta di contatto dal sito web. Di seguito i dettagli compilati:

**Dati Richiedente:**
- **Nome:** {{ $data['nome'] }} {{ $data['cognome'] }}
@if(!empty($data['ragione_sociale']))
- **Ragione Sociale:** {{ $data['ragione_sociale'] }}
@endif
- **Email:** {{ $data['email'] }}
- **Telefono:** {{ $data['telefono'] ?? 'Non specificato' }}

**Messaggio / Richiesta:**
{{ $data['richiesta'] }}

<x-mail::button :url="url('/amministrazione/contatti')">
Visualizza nel Gestionale
</x-mail::button>

Grazie,<br>
{{ config('app.name') }}
</x-mail::message>
