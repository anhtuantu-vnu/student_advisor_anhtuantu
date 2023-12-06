<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        .mail-body {
            height: 300px;
            background-color: #ecf0f1;
            padding: 10px;
        }
    </style>
</head>

<body class="bg-white">
    <main>
        <div class="mail-body">
            <div>
                <p>Hi {{ $toName }},</p>
            </div>
            <div style="font-size: 16px; font-weight: bold">
                <p>{{ $fromTitle }}. {{ $fromName }} inivited you to
                    @if ($event->type == 'meeting')
                        a meeting
                    @else
                        an event
                    @endif
                    <a href="{{ $event_url }}" target="_blank">{{ $event->name }}</a>. Please review and respond to
                    the invitation.
                </p>
            </div>
            <div style="font-size: 16px; font-weight: bold; margin-top: 50px">
                Best regards,<br />
                {{ $fromName }}
            </div>
        </div>
    </main>
</body>

</html>
