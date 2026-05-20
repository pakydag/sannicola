<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 10px; }
        .header { text-align: center; border-bottom: 2px solid #6366f1; padding-bottom: 10px; margin-bottom: 20px; }
        .btn { display: inline-block; padding: 12px 24px; background-color: #6366f1; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; }
        .footer { margin-top: 30px; font-size: 12px; color: #777; border-top: 1px solid #eee; padding-top: 10px; }
        .password-box { background: #f3f4f6; border: 1px border-dashed #cbd5e1; padding: 15px; border-radius: 8px; font-family: monospace; font-size: 18px; text-align: center; letter-spacing: 2px; font-weight: bold; margin: 20px 0; color: #1e1b4b; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Reimpostazione Password</h2>
        </div>

        <p>Gentile {{ $customer->nome }} {{ $customer->cognome }},</p>
        
        <p>Abbiamo ricevuto una richiesta di reimpostazione della password per il tuo account associato all'Area Booking.</p>
        
        <p>Abbiamo generato per te una nuova password temporanea di accesso:</p>
        
        <div class="password-box">
            {{ $tempPassword }}
        </div>
        
        <p>Puoi accedere all'Area Booking cliccando sul pulsante sottostante utilizzando la tua e-mail e questa password temporanea. Ti consigliamo vivamente di cambiarla all'interno della sezione "Il mio Profilo" subito dopo aver effettuato l'accesso.</p>
        
        <p style="text-align: center; margin: 30px 0;">
            <a href="{{ route('public.booking.login.view') }}" class="btn">Accedi all'Area Booking</a>
        </p>

        <div class="footer">
            <p>Se non hai richiesto tu questa reimpostazione, ti preghiamo di ignorare questa email.</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}</p>
        </div>
    </div>
</body>
</html>
