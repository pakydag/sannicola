<!DOCTYPE html>
<html>
<head>
    <title>Benvenuto in {{ config('app.name') }}</title>
</head>
<body style="font-family: sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px;">
        <h2 style="color: #4A90E2;">Ciao {{ $user->name }},</h2>
        <p>È stato creato per te un nuovo account Agente su <strong>{{ config('app.name') }}</strong>.</p>
        
        <div style="background-color: #f9f9f9; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <p><strong>Dati di accesso:</strong></p>
            <p>Email: {{ $user->email }}</p>
            <p>Password Temporanea: <span style="font-family: monospace; background: #eee; padding: 2px 5px;">{{ $password }}</span></p>
        </div>

        <p>Ti consigliamo di accedere immediatamente e cambiare la password nelle impostazioni del tuo profilo.</p>
        
        <div style="text-align: center; margin-top: 30px;">
            <a href="{{ route('login') }}" style="background-color: #4A90E2; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold;">Accedi al Pannello</a>
        </div>

        <p style="margin-top: 30px; font-size: 0.9em; color: #777;">
            Se non hai richiesto questo account, contatta l'amministratore di sistema.
        </p>
    </div>
</body>
</html>
