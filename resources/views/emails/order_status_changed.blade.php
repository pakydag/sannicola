<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Aggiornamento Ordine #{{ $order->numero_ordine }}</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-w-xl mx-auto p-4 border rounded shadow-sm">
        <h2 style="color: #4F46E5;">Aggiornamento Stato Ordine</h2>
        <p>Gentile <strong>{{ $order->customer->nome ?? 'Cliente' }} {{ $order->customer->cognome ?? '' }}</strong>,</p>
        
        <p>Ti informiamo che lo stato del tuo ordine <strong>#{{ $order->numero_ordine }}</strong> del {{ $order->created_at->format('d/m/Y') }} è stato aggiornato.</p>
        
        <div style="background-color: #f3f4f6; padding: 15px; border-left: 4px solid #4F46E5; margin-bottom: 20px;">
            <h3 style="margin-top: 0; margin-bottom: 5px;">Nuovo Stato Spedizione: 
                @if($order->stato === 'nuovo')
                    <span style="color: #d97706;">Ricevuto</span>
                @elseif($order->stato === 'in_lavorazione')
                    <span style="color: #2563eb;">In Lavorazione</span>
                @elseif($order->stato === 'spedito')
                    <span style="color: #059669;">Spedito</span>
                @elseif($order->stato === 'annullato')
                    <span style="color: #dc2626;">Annullato</span>
                @else
                    {{ ucfirst($order->stato) }}
                @endif
            </h3>
        </div>

        @if($order->stato === 'spedito')
            <p>Il tuo ordine è stato affidato al corriere ed è in viaggio verso l'indirizzo indicato:</p>
            <p style="background-color: #f9f9f9; padding: 10px; border: 1px solid #eee;">
                {{ $order->spedizione_nome }}<br>
                {{ $order->spedizione_indirizzo }}<br>
                {{ $order->spedizione_cap }} {{ $order->spedizione_citta }} ({{ $order->spedizione_provincia }})<br>
                {{ $order->spedizione_nazione }}
            </p>
        @endif

        <p>Puoi rispondere a questa email per qualsiasi informazione o chiarimento.</p>

        <p style="margin-top: 40px; font-size: 0.9em; color: #777;">Questo è un messaggio automatico generato dal nostro sistema E-Commerce.</p>
    </div>
</body>
</html>
