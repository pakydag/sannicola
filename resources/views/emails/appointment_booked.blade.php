<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #eee; border-radius: 5px; }
        .header { background: #10b981; color: white; padding: 15px; border-radius: 5px 5px 0 0; text-align: center; }
        .content { padding: 20px; }
        .footer { font-size: 12px; color: #777; text-align: center; margin-top: 20px; }
        .field { margin-bottom: 15px; }
        .label { font-weight: bold; color: #10b981; display: block; }
        .value { color: #111; font-size: 16px; }
        .btn { display: inline-block; padding: 10px 20px; background: #10b981; color: white !important; text-decoration: none; border-radius: 5px; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Nuovo Appuntamento AI #{{ $appointment->id }}</h1>
        </div>
        <div class="content">
            <div class="field">
                <span class="label">Data e Ora:</span>
                <span class="value">{{ date('d/m/Y H:i', strtotime($appointment->start_time)) }}</span>
            </div>
            <div class="field">
                <span class="label">Reparto:</span>
                <span class="value">{{ $appointment->department ? $appointment->department->name : 'N/A' }}</span>
            </div>
            <div class="field">
                <span class="label">Azienda:</span>
                <span class="value">{{ $appointment->contact ? $appointment->contact->company_name : 'N/A' }}</span>
            </div>
            <div class="field">
                <span class="label">Cliente:</span>
                <span class="value">{{ $appointment->contact ? ($appointment->contact->first_name . ' ' . $appointment->contact->last_name) : 'N/A' }}</span>
            </div>
            
            @if($appointment->description)
            <div class="field" style="margin-top: 20px; padding-top: 10px; border-top: 1px solid #eee;">
                <span class="label">Note Appuntamento:</span>
                <p class="value">{{ $appointment->description }}</p>
            </div>
            @endif

            <center>
                <a href="{{ route('admin.appointments.show', $appointment) }}" class="btn">Visualizza Appuntamento in Amministrazione</a>
            </center>
        </div>
        <div class="footer">
            Questo è un messaggio automatico generato dal sistema {{ config('app.name') }}.
        </div>
    </div>
</body>
</html>
