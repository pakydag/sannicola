<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 10px; }
        .header { text-align: center; margin-bottom: 30px; }
        .booking-details { background-color: #f9fafb; padding: 20px; border-radius: 5px; margin-bottom: 30px; }
        .footer { font-size: 12px; color: #999; text-align: center; border-top: 1px solid #eee; padding-top: 20px; }
        .status { padding: 5px 10px; border-radius: 15px; font-size: 12px; font-weight: bold; text-transform: uppercase; }
        .confirmed { background-color: #d1fae5; color: #065f46; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Conferma Prenotazione #{{ $booking->id }}</h1>
        </div>
        <div class="content">
            <p>Gentile <strong>{{ $booking->customer->nome ?? 'Cliente' }}</strong>,</p>
            
            @if($isPending)
                <p>Ti informiamo che abbiamo ricevuto la tua richiesta di prenotazione. La tua prenotazione è attualmente <strong>in attesa di pagamento</strong> tramite Bonifico Bancario.</p>
                <div style="background-color: #fffbeb; border: 1px solid #fcd34d; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                    <h4 style="margin-top: 0; color: #92400e;">Coordinate per il Bonifico:</h4>
                    @php
                        $bank_name = \App\Models\Setting::where('key', 'bonifico_banca')->value('value');
                        $bank_iban = \App\Models\Setting::where('key', 'bonifico_iban')->value('value');
                        $bank_holder = \App\Models\Setting::where('key', 'bonifico_intestazione')->value('value');
                    @endphp
                    <p style="margin-bottom: 5px;"><strong>Banca:</strong> {{ $bank_name ?? 'N/D' }}</p>
                    <p style="margin-bottom: 5px;"><strong>IBAN:</strong> <span style="font-family: monospace;">{{ $bank_iban ?? 'N/D' }}</span></p>
                    <p style="margin-bottom: 0;"><strong>Intestato a:</strong> {{ $bank_holder ?? 'N/D' }}</p>
                    <p style="margin-top: 10px; font-size: 13px;"><em>Si prega di inserire il Codice Prenotazione <strong>#{{ $booking->id }}</strong> nella causale del bonifico.</em></p>
                </div>
            @else
                <p>Siamo lieti di confermarti la tua prenotazione presso la nostra struttura.</p>
            @endif
            
            <div class="booking-details">
                <h3 style="margin-top: 0;">Riepilogo Soggiorno</h3>
                <p><strong>Struttura:</strong> {{ $booking->structure->nome }}</p>
                <p><strong>Check-in:</strong> {{ \Carbon\Carbon::parse($booking->start_date)->format('d/m/Y') }}</p>
                <p><strong>Check-out:</strong> {{ \Carbon\Carbon::parse($booking->end_date)->format('d/m/Y') }}</p>
                <p><strong>Ospiti:</strong> {{ $booking->adulti }} Adulti, {{ $booking->bambini }} Bambini</p>
                <p><strong>Stato Pagamento:</strong> {{ ucfirst($booking->stato_pagamento) }}</p>
                <p><strong>Metodo:</strong> {{ ucfirst($booking->metodo_pagamento) }}</p>
                <p style="font-size: 18px; color: #4f46e5;"><strong>Totale: €{{ number_format($booking->totale_prezzo, 2, ',', '.') }}</strong></p>
            </div>

            @if($booking->stripe_payment_link && $booking->stato_pagamento != 'pagato')
                <div style="text-align: center; margin: 30px 0;">
                    <p>Puoi completare il pagamento cliccando sul link sottostante:</p>
                    <a href="{{ $booking->stripe_payment_link }}" style="display: inline-block; padding: 12px 24px; background-color: #4f46e5; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;">Paga Ora con Stripe</a>
                </div>
            @endif

            <p>Se hai bisogno di modificare la tua prenotazione o hai richieste speciali, non esitare a contattarci.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Tutti i diritti riservati.</p>
        </div>
    </div>
</body>
</html>
