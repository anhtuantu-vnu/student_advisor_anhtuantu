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
                <p>{{ $content }}</p>
            </div>
            <div style="font-size: 16px; font-weight: bold; margin-top: 50px">
                Best regards,<br />
                {{ $fromName }}
            </div>
        </div>
    </main>
</body>

</html>
