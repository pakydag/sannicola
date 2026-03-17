<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f4f7f6; }
        .container { max-width: 600px; margin: 20px auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header { background: #4f46e5; color: #fff; padding: 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; text-transform: uppercase; letter-spacing: 1px; }
        .content { padding: 30px; }
        .order-info { margin-bottom: 30px; border-bottom: 2px solid #f0f0f0; pb: 20px; }
        .order-info p { margin: 5px 0; font-size: 14px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th { text-align: left; background: #f9fafb; padding: 12px; font-size: 12px; text-transform: uppercase; color: #6b7280; border-bottom: 1px solid #e5e7eb; }
        .table td { padding: 12px; border-bottom: 1px solid #f3f4f6; font-size: 14px; }
        .total-row { font-weight: bold; background: #f9fafb; }
        .footer { background: #f9fafb; padding: 20px; text-align: center; font-size: 12px; color: #9ca3af; }
        .btn { display: inline-block; padding: 15px 30px; background: #4f46e5; color: #fff !important; text-decoration: none; border-radius: 6px; font-weight: bold; margin-top: 20px; text-transform: uppercase; font-size: 14px; }
        .payment-box { background: #eff6ff; border: 1px dashed #bfdbfe; padding: 20px; border-radius: 8px; margin-top: 30px; text-align: center; }
        .bonifico-info { text-align: left; background: #fff; padding: 15px; border-radius: 4px; border: 1px solid #e5e7eb; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Riepilogo Ordine B2B</h1>
        </div>
        <div class="content">
            <p>Gentile <strong>{{ $order->customer->business_name }}</strong>,</p>
            <p>Ti inviamo il riepilogo dell'ordine effettuato tramite il nostro portale B2B.</p>

            <div class="order-info">
                <p><strong>Numero Ordine:</strong> #{{ $order->id }}</p>
                <p><strong>Data:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Agente:</strong> {{ $order->agent->name }} {{ $order->agent->surname }}</p>
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th>Prodotto</th>
                        <th style="text-align: center;">Qtà</th>
                        <th style="text-align: right;">Prezzo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>
                                {{ $item->product->name }}<br>
                                <small style="color: #6b7280;">{{ $item->variant->size }} / {{ $item->variant->color ?? 'Unico' }}</small>
                            </td>
                            <td style="text-align: center;">{{ $item->quantity }}</td>
                            <td style="text-align: right;">€ {{ number_format($item->price, 2, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    <tr class="total-row">
                        <td colspan="2" style="text-align: right; padding: 15px;">TOTALE ORDINE</td>
                        <td style="text-align: right; padding: 15px; font-size: 18px; color: #4f46e5;">€ {{ number_format($order->total_amount, 2, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>

            @if($paymentMethod !== 'none')
            <div class="payment-box">
                @if($paymentMethod === 'stripe')
                    <p style="margin-bottom: 10px;">Puoi procedere al pagamento sicuro tramite <strong>Carta di Credito</strong> utilizzando il link configurato:</p>
                    <a href="{{ $paymentLink }}" class="btn">Paga con Stripe</a>
                @elseif($paymentMethod === 'paypal')
                    <p style="margin-bottom: 10px;">Puoi procedere al pagamento tramite <strong>PayPal</strong> cliccando il pulsante qui sotto:</p>
                    <a href="{{ $paymentLink }}" class="btn">Paga con PayPal</a>
                @elseif($paymentMethod === 'bonifico')
                    <p>Modalità di pagamento: <strong>Bonifico Bancario</strong></p>
                    <div class="bonifico-info">
                        @php $settings = \App\Models\Setting::all()->pluck('value', 'key'); @endphp
                        <p><strong>Intestato a:</strong> {{ $settings['bonifico_intestazione'] ?? 'N.D.' }}</p>
                        <p><strong>Banca:</strong> {{ $settings['bonifico_banca'] ?? 'N.D.' }}</p>
                        <p><strong>IBAN:</strong> <span style="font-family: monospace; font-size: 16px; font-weight: bold;">{{ $settings['bonifico_iban'] ?? 'N.D.' }}</span></p>
                        <p><strong>Causale:</strong> Saldo Ordine B2B #{{ $order->id }}</p>
                    </div>
                @endif
            </div>
            @endif

            @if($order->notes)
                <div style="margin-top: 20px; padding: 15px; background: #fffbeb; border-left: 4px solid #f59e0b; font-size: 13px;">
                    <strong>Note:</strong> {{ $order->notes }}
                </div>
            @endif
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name') }} - Portale B2B Agenti.
        </div>
    </div>
</body>
</html>
