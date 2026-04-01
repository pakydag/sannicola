<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #eee; border-radius: 5px; }
        .header { background: #4f46e5; color: white; padding: 15px; border-radius: 5px 5px 0 0; text-align: center; }
        .content { padding: 20px; }
        .footer { font-size: 12px; color: #777; text-align: center; margin-top: 20px; }
        .field { margin-bottom: 15px; }
        .label { font-weight: bold; color: #4f46e5; display: block; }
        .value { color: #111; font-size: 16px; }
        .btn { display: inline-block; padding: 10px 20px; background: #4f46e5; color: white !important; text-decoration: none; border-radius: 5px; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Nuovo Ticket Assistenza AI #{{ $ticket->id }}</h1>
        </div>
        <div class="content">
            <div class="field">
                <span class="label">Reparto Assegnato:</span>
                <span class="value">{{ $ticket->department ? $ticket->department->name : 'N/A' }}</span>
            </div>
            <div class="field">
                <span class="label">Azienda:</span>
                <span class="value">{{ $ticket->company_name }}</span>
            </div>
            <div class="field">
                <span class="label">Cliente:</span>
                <span class="value">{{ $ticket->customer_name }}</span>
            </div>
            @if($ticket->phone)
            <div class="field">
                <span class="label">Telefono:</span>
                <span class="value">{{ $ticket->phone }}</span>
            </div>
            @endif
            @if($ticket->email)
            <div class="field">
                <span class="label">Email:</span>
                <span class="value">{{ $ticket->email }}</span>
            </div>
            @endif
            <div class="field" style="margin-top: 20px; padding-top: 10px; border-top: 1px solid #eee;">
                <span class="label">Descrizione Problema:</span>
                <p class="value">{{ $ticket->description }}</p>
            </div>

            <center>
                <a href="{{ route('admin.vapi.tickets.show', $ticket) }}" class="btn">Visualizza Ticket in Amministrazione</a>
            </center>
        </div>
        <div class="footer">
            Questo è un messaggio automatico generato dal sistema {{ config('app.name') }}.
        </div>
    </div>
</body>
</html>
