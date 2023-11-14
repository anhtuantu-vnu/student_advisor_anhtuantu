<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        .mail-body {
            height: 300px;
            background-color: #ecf0f1;
            padding: 10px;
        }
        .btn_accept {
            padding: 12px 32px;
            border-radius: 4px;
            border: none;
            background-color: #104ac3;
            color: white !important;
            text-decoration: none;
        }
        .btn_accept:hover {
            cursor: pointer;
            background-color: #ffffff;
            font-weight: bold;
            color: #104ac3 !important;
        }
    </style>
</head>

<body class="bg-white">
    <main>
        <div class="mail-body">
            <div>
                <p>Hi {{$data['fist_name']}},</p>
            </div>
            <div style="font-size: 16px;">
                <p>{{$data['author']}} inivited you to plan <b>{{$data['plan_name']}}</b></p>
                <p>Click the button below to join the plan</p>
                <a href="{{ $data['url'] }}" class="btn_accept" target="_blank">Accept</a>
            </div>
            <div style="font-size: 16px; margin-top: 40px">
                Best regards,<br />
                <b>{{$data['author']}}</b>
            </div>
        </div>
    </main>
</body>

</html>
