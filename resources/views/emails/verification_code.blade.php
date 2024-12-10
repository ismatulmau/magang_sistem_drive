<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode Verifikasi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .email-header {
            text-align: center;
            background-color: #007bff;
            padding: 10px;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            color: #ffffff;
            font-size: 24px;
        }
        .email-body {
            padding: 20px;
            color: #333333;
            line-height: 1.6;
        }
        .email-body p {
            margin: 0 0 20px;
        }
        .verification-code {
            display: block;
            width: fit-content;
            margin: 0 auto;
            background-color: #007bff;
            color: #ffffff;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 18px;
            text-align: center;
            font-weight: bold;
            letter-spacing: 2px;
        }
        .email-footer {
            text-align: center;
            font-size: 12px;
            color: #999999;
            padding: 10px 20px;
            border-top: 1px solid #eeeeee;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            Kode Verifikasi Anda
        </div>
        <div class="email-body">
            <p>Halo {{ $user->nama }},</p>
            <p>Terima kasih telah mendaftar di {{ config('app.name') }}. Untuk menyelesaikan proses pendaftaran Anda, silakan gunakan kode verifikasi di bawah ini:</p>
            <p class="verification-code">{{ $verificationCode }}</p>
            <p>Jika Anda tidak melakukan pendaftaran ini, abaikan saja email ini.</p>
        </div>
        <div class="email-footer">
            © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>
    </div>
</body>
</html>
