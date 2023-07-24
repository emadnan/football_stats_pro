<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
</head>
<body>
    <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
        <h2>OTP Verification</h2>
        <p>Hello {{ $userName }},</p>
        <p>Your One-Time Password (OTP) for verification is: <strong>{{ $otp }}</strong></p>
        <p>This OTP is valid for a limited time and should be used for a single session only.</p>
        <p>If you did not request this OTP, please ignore this email.</p>
        <p>Thank you!</p>
        <p>Your Company Name</p>
    </div>
</body>
</html>
