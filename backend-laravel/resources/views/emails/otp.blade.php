<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; }
        .container { max-width: 480px; margin: auto; background: #fff; border-radius: 8px; padding: 32px; }
        .otp-box {
            font-size: 36px; font-weight: bold; letter-spacing: 12px;
            text-align: center; padding: 20px; background: #f0fdf4;
            border-radius: 8px; margin: 24px 0; border: 2px dashed #059669;
            color: #065f46;
        }
        .footer { color: #999; font-size: 12px; text-align: center; margin-top: 24px; }
    </style>
</head>
<body>
    <div class="container">
        <h2 style="color:#059669;">GIZENIA.AI</h2>
        <p>Gunakan kode OTP berikut untuk reset password kamu.</p>
        <p>Kode berlaku selama <strong>5 menit</strong>.</p>
        <div class="otp-box">{{ $otp }}</div>
        <p>Jangan bagikan kode ini kepada siapapun.</p>
        <div class="footer">Jika tidak merasa melakukan ini, abaikan email ini.</div>
    </div>
</body>
</html>