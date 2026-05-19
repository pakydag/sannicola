<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        @if($locale === 'en')
            Booking Confirmation #{{ $booking->id }}
        @else
            Conferma Prenotazione #{{ $booking->id }}
        @endif
    </title>
    <style>
        body { 
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; 
            line-height: 1.6; 
            color: #334155; 
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
        }
        .container { 
            max-width: 600px; 
            margin: 40px auto; 
            padding: 32px; 
            background-color: #ffffff;
            border: 1px solid #e2e8f0; 
            border-radius: 24px; 
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
        }
        .header { 
            text-align: center; 
            margin-bottom: 32px; 
        }
        .logo {
            max-height: 70px;
            margin-bottom: 24px;
        }
        h1 {
            color: #0f172a;
            font-size: 24px;
            font-weight: 800;
            margin: 0;
        }
        .welcome-text {
            font-size: 16px;
            color: #475569;
            margin-bottom: 24px;
        }
        .booking-details { 
            background-color: #f8fafc; 
            padding: 24px; 
            border-radius: 16px; 
            margin-bottom: 32px; 
            border: 1px solid #f1f5f9;
        }
        .booking-details h3 {
            color: #1e1b4b;
            font-size: 18px;
            font-weight: 700;
            margin-top: 0;
            margin-bottom: 16px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 12px;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 14px;
        }
        .detail-label {
            color: #64748b;
            font-weight: 500;
        }
        .detail-value {
            color: #0f172a;
            font-weight: 700;
        }
        .price-row {
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px dashed #cbd5e1;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .price-label {
            font-size: 16px;
            color: #4f46e5;
            font-weight: 700;
        }
        .price-value {
            font-size: 22px;
            color: #4f46e5;
            font-weight: 900;
        }
        .button-container {
            text-align: center;
            margin: 32px 0;
        }
        .btn {
            display: inline-block; 
            padding: 14px 32px; 
            background-color: #4f46e5; 
            color: #ffffff !important; 
            text-decoration: none; 
            border-radius: 14px; 
            font-weight: bold;
            font-size: 16px;
            box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2);
            transition: all 0.2s ease;
        }
        .bank-info {
            background-color: #fffbeb; 
            border: 1px solid #fde68a; 
            padding: 20px; 
            border-radius: 16px; 
            margin-bottom: 32px;
        }
        .bank-info h4 {
            margin-top: 0; 
            color: #b45309;
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 12px;
        }
        .bank-info p {
            margin: 6px 0;
            font-size: 14px;
            color: #78350f;
        }
        .services-section {
            border-top: 1px solid #e2e8f0;
            padding-top: 28px;
            margin-top: 28px;
        }
        .services-title {
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 16px;
        }
        .service-link {
            display: block;
            padding: 12px 16px;
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            color: #4f46e5;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 10px;
            transition: all 0.2s ease;
        }
        .service-link:hover {
            background-color: #f8fafc;
            border-color: #cbd5e1;
        }
        .footer { 
            font-size: 12px; 
            color: #94a3b8; 
            text-align: center; 
            margin-top: 40px;
            border-top: 1px solid #f1f5f9; 
            padding-top: 24px; 
        }
    </style>
</head>
<body>
    <div class="container">
        @php
            $logoSetting = \App\Models\Setting::where('key', 'site_logo')->value('value');
            $logoUrl = $logoSetting ? asset($logoSetting) : null;
        @endphp

        <div class="header">
            @if($logoUrl)
                <img src="{{ $logoUrl }}" class="logo" alt="{{ config('app.name') }}">
            @endif
            <h1>
                @if($locale === 'en')
                    Booking Confirmation #{{ $booking->id }}
                @else
                    Conferma Prenotazione #{{ $booking->id }}
                @endif
            </h1>
        </div>

        <div class="content">
            <p class="welcome-text">
                @if($locale === 'en')
                    Dear <strong>{{ $booking->customer->nome ?? 'Customer' }}</strong>,
                @else
                    Gentile <strong>{{ $booking->customer->nome ?? 'Cliente' }}</strong>,
                @endif
            </p>
            
            @if($isPending)
                @if($locale === 'en')
                    <p>We inform you that we have successfully received your booking request. Your booking is currently <strong>pending payment</strong> via Bank Transfer.</p>
                @else
                    <p>Ti informiamo che abbiamo ricevuto la tua richiesta di prenotazione. La tua prenotazione è attualmente <strong>in attesa di pagamento</strong> tramite Bonifico Bancario.</p>
                @endif

                <div class="bank-info">
                    @php
                        $bank_name = \App\Models\Setting::where('key', 'bonifico_banca')->value('value');
                        $bank_iban = \App\Models\Setting::where('key', 'bonifico_iban')->value('value');
                        $bank_holder = \App\Models\Setting::where('key', 'bonifico_intestazione')->value('value');
                    @endphp
                    <h4>
                        @if($locale === 'en')
                            Bank Transfer Details:
                        @else
                            Coordinate per il Bonifico:
                        @endif
                    </h4>
                    <p><strong>{{ $locale === 'en' ? 'Bank:' : 'Banca:' }}</strong> {{ $bank_name ?? 'N/D' }}</p>
                    <p><strong>IBAN:</strong> <span style="font-family: monospace; font-weight: bold;">{{ $bank_iban ?? 'N/D' }}</span></p>
                    <p><strong>{{ $locale === 'en' ? 'Account Holder:' : 'Intestato a:' }}</strong> {{ $bank_holder ?? 'N/D' }}</p>
                    <p style="margin-top: 12px; font-size: 13px; font-style: italic; opacity: 0.9;">
                        @if($locale === 'en')
                            * Please include the Booking Code <strong>#{{ $booking->id }}</strong> as the payment reference.
                        @else
                            * Si prega di inserire il Codice Prenotazione <strong>#{{ $booking->id }}</strong> nella causale del bonifico.
                        @endif
                    </p>
                </div>
            @else
                <p>
                    @if($locale === 'en')
                        We are pleased to confirm your booking at our property.
                    @else
                        Siamo lieti di confermarti la tua prenotazione presso la nostra struttura.
                    @endif
                </p>
            @endif
            
            <div class="booking-details">
                <h3>
                    @if($locale === 'en')
                        Stay Summary
                    @else
                        Riepilogo Soggiorno
                    @endif
                </h3>
                
                <div class="detail-row">
                    <span class="detail-label">{{ $locale === 'en' ? 'Property:' : 'Struttura:' }}</span>
                    <span class="detail-value">{{ $booking->structure->nome }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Check-in:</span>
                    <span class="detail-value">{{ \Carbon\Carbon::parse($booking->start_date)->format('d/m/Y') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Check-out:</span>
                    <span class="detail-value">{{ \Carbon\Carbon::parse($booking->end_date)->format('d/m/Y') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">{{ $locale === 'en' ? 'Guests:' : 'Ospiti:' }}</span>
                    <span class="detail-value">
                        @if($locale === 'en')
                            {{ $booking->adulti }} Adult{{ $booking->adulti > 1 ? 's' : '' }}
                            @if($booking->bambini > 0)
                                , {{ $booking->bambini }} Child{{ $booking->bambini > 1 ? 'ren' : 'd' }}
                            @endif
                        @else
                            {{ $booking->adulti }} Adult{{ $booking->adulti > 1 ? 'i' : 'o' }}
                            @if($booking->bambini > 0)
                                , {{ $booking->bambini }} Bambin{{ $booking->bambini > 1 ? 'i' : 'o' }}
                            @endif
                        @endif
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">{{ $locale === 'en' ? 'Payment Status:' : 'Stato Pagamento:' }}</span>
                    <span class="detail-value">
                        @if($booking->stato_pagamento === 'pagato')
                            {{ $locale === 'en' ? 'Paid' : 'Pagato' }}
                        @elseif($booking->stato_pagamento === 'rimborsato')
                            {{ $locale === 'en' ? 'Refunded' : 'Rimborsato' }}
                        @else
                            {{ $locale === 'en' ? 'Pending' : 'In attesa' }}
                        @endif
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">{{ $locale === 'en' ? 'Payment Method:' : 'Metodo Pagamento:' }}</span>
                    <span class="detail-value">
                        @if($booking->metodo_pagamento === 'stripe')
                            Credit Card (Stripe)
                        @elseif($booking->metodo_pagamento === 'paypal')
                            PayPal
                        @elseif($booking->metodo_pagamento === 'bonifico')
                            {{ $locale === 'en' ? 'Bank Transfer' : 'Bonifico Bancario' }}
                        @else
                            {{ ucfirst($booking->metodo_pagamento) }}
                        @endif
                    </span>
                </div>
                
                <div class="price-row">
                    <span class="price-label">Total:</span>
                    <span class="price-value">€{{ number_format($booking->totale_prezzo, 2, ',', '.') }}</span>
                </div>
            </div>

            @if($booking->stripe_payment_link && $booking->stato_pagamento != 'pagato')
                <div class="button-container">
                    <p style="margin-bottom: 16px; font-size: 14px; color: #475569;">
                        @if($locale === 'en')
                            You can complete your payment securely by clicking the button below:
                        @else
                            Puoi completare il pagamento in totale sicurezza cliccando sul pulsante sottostante:
                        @endif
                    </p>
                    <a href="{{ $booking->stripe_payment_link }}" class="btn">
                        @if($locale === 'en')
                            Pay Now with Stripe
                        @else
                            Paga Ora con Stripe
                        @endif
                    </a>
                </div>
            @endif

            <p style="font-size: 15px; color: #475569;">
                @if($locale === 'en')
                    If you need to edit your booking or have any special requests, please do not hesitate to contact us.
                @else
                    Se hai bisogno di modificare la tua prenotazione o hai richieste speciali, non esitare a contattarci.
                @endif
            </p>

            <!-- EXTRA SERVICES SECTION -->
            <div class="services-section">
                <div class="services-title">
                    @if($locale === 'en')
                        Useful Services & Information
                    @else
                        Servizi Utili & Informazioni
                    @endif
                </div>

                <a href="{{ url('/come-arrivare-ostuni') }}" class="service-link" target="_blank">
                    📍 @if($locale === 'en')
                        How to get to the property - Guide & Directions
                    @else
                        Come arrivare in struttura - Guida & Indicazioni
                    @endif
                </a>

                <a href="{{ url('/richieste/richiedi-transfer') }}" class="service-link" target="_blank">
                    🚗 @if($locale === 'en')
                        Need a transfer? Book a private shuttle
                    @else
                        Hai bisogno di un transfer? Richiedi una navetta privata
                    @endif
                </a>

                <a href="{{ url('/richieste/noleggio-auto') }}" class="service-link" target="_blank">
                    🔑 @if($locale === 'en')
                        Need to rent a car? Check our fleet
                    @else
                        Hai bisogno di noleggiare un'auto? Scopri le nostre tariffe
                    @endif
                </a>
            </div>

        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. {{ $locale === 'en' ? 'All rights reserved.' : 'Tutti i diritti riservati.' }}</p>
        </div>
    </div>
</body>
</html>
