<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 10px; }
        .header { text-align: center; border-bottom: 2px solid #6366f1; padding-bottom: 10px; margin-bottom: 20px; }
        .btn { display: inline-block; padding: 12px 24px; background-color: #6366f1; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; }
        .footer { margin-top: 30px; font-size: 12px; color: #777; border-top: 1px solid #eee; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Aggiornamento Prenotazione</h2>
        </div>

        <p>Gentile {{ $booking->customer->nome }},</p>

        @if($statusType == 'paid')
            <p>Siamo lieti di informarti che abbiamo ricevuto il pagamento di <strong>€{{ number_format($booking->totale_prezzo, 2, ',', '.') }}</strong> per la tua prenotazione <strong>#{{ $booking->id }}</strong> presso <strong>{{ $booking->structure->nome }}</strong>.</p>
            <p>Il tuo soggiorno è ora confermato integralmente.</p>
        @elseif($statusType == 'cancelled')
            <p>Ti informiamo che la tua prenotazione <strong>#{{ $booking->id }}</strong> per un totale di <strong>€{{ number_format($booking->totale_prezzo, 2, ',', '.') }}</strong> è stata <strong>annullata</strong>.</p>
            <p>Se ritieni si tratti di un errore, ti preghiamo di contattarci il prima possibile.</p>
        @elseif($statusType == 'stripe_link')
            <p>Per confermare la tua prenotazione <strong>#{{ $booking->id }}</strong> presso <strong>{{ $booking->structure->nome }}</strong>, è necessario procedere al pagamento dell'importo totale di <strong>€{{ number_format($booking->totale_prezzo, 2, ',', '.') }}</strong>.</p>
            <p style="text-align: center; margin: 30px 0;">
                <a href="{{ $booking->stripe_payment_link }}" class="btn">Paga Ora €{{ number_format($booking->totale_prezzo, 2, ',', '.') }}</a>
            </p>
        @elseif($statusType == 'bank_details')
            <p>Ti ricordiamo i dati per effettuare il bonifico di <strong>€{{ number_format($booking->totale_prezzo, 2, ',', '.') }}</strong> relativo alla prenotazione <strong>#{{ $booking->id }}</strong>:</p>
            <div style="background: #f9fafb; padding: 15px; border-radius: 8px;">
                <p><strong>Importo:</strong> €{{ number_format($booking->totale_prezzo, 2, ',', '.') }}</p>
                <p><strong>IBAN:</strong> {{ \App\Models\Setting::where('key', 'bonifico_iban')->value('value') }}</p>
                <p><strong>Banca:</strong> {{ \App\Models\Setting::where('key', 'bonifico_banca')->value('value') }}</p>
                <p><strong>Intestatario:</strong> {{ \App\Models\Setting::where('key', 'bonifico_intestazione')->value('value') }}</p>
                <p><strong>Causale:</strong> Prenotazione #{{ $booking->id }} - {{ $booking->customer->cognome }}</p>
            </div>
        @elseif($statusType == 'paga_in_struttura')
            <p>La tua prenotazione <strong>#{{ $booking->id }}</strong> presso <strong>{{ $booking->structure->nome }}</strong> è confermata.</p>
            <p>L'importo totale di <strong>€{{ number_format($booking->totale_prezzo, 2, ',', '.') }}</strong> dovrà essere corrisposto direttamente in struttura al tuo arrivo.</p>
        @endif

        <p>Dettagli Soggiorno:<br>
        Dal: {{ \Carbon\Carbon::parse($booking->start_date)->format('d/m/Y') }}<br>
        Al: {{ \Carbon\Carbon::parse($booking->end_date)->format('d/m/Y') }}</p>

        <div class="footer">
            <p>Grazie per aver scelto i nostri servizi.</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}</p>
        </div>
    </div>
</body>
</html>
