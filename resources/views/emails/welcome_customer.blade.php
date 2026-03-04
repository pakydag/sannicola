<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 10px; }
        .header { text-align: center; margin-bottom: 30px; }
        .content { margin-bottom: 30px; }
        .footer { font-size: 12px; color: #999; text-align: center; border-top: 1px solid #eee; padding-top: 20px; }
        .button { display: inline-block; padding: 12px 24px; background-color: #4f46e5; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Benvenuto/a su {{ config('app.name') }}!</h1>
        </div>
        <div class="content">
            <p>Gentile <strong>{{ $customer->nome }} {{ $customer->cognome }}</strong>,</p>
            <p>Grazie per aver scelto il nostro portale. Il tuo account è stato creato con successo.</p>
            
            @if($password)
                <p>Queste sono le tue credenziali di accesso:</p>
                <ul>
                    <li><strong>E-mail:</strong> {{ $customer->email }}</li>
                    <li><strong>Password temporanea:</strong> {{ $password }}</li>
                </ul>
                <p>Ti consigliamo di cambiare la tua password dopo il primo accesso dalla sezione profilo.</p>
            @else
                <p>Puoi accedere al tuo account utilizzando l'email <strong>{{ $customer->email }}</strong> e la password scelta durante la registrazione.</p>
            @endif

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ url('/login') }}" class="button">Accedi al tuo Account</a>
            </div>
            
            <p>Se hai domande o bisogno di assistenza, il nostro team è a tua completa disposizione.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Tutti i diritti riservati.</p>
        </div>
    </div>
</body>
</html>
