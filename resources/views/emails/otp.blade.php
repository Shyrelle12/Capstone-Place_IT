<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fdf2e9;
            color: #4a4a4a;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background-color: #ff7a1a;
            color: #ffffff;
            text-align: center;
            padding: 20px 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }
        .content {
            padding: 20px 15px;
        }
        .content p {
            margin: 10px 0;
        }
        .otp-box {
            margin: 20px auto;
            background-color: #fff8f0;
            border: 2px dashed #ff7a1a;
            color: #ff7a1a;
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            padding: 15px;
            border-radius: 8px;
        }
        .footer {
            background-color: #fef5ed;
            text-align: center;
            padding: 15px 10px;
            font-size: 14px;
            color: #9e9e9e;
            border-top: 1px solid #e0e0e0;
        }
        .footer a {
            color: #ff7a1a;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Verify Your Email</h1>
        </div>
        <div class="content">
            <p>Dear User,</p>
            <p>
                Thank you for signing up. To complete your registration, please enter the OTP code below on the verification page.
            </p>
            <div class="otp-box">
                {{ $otp }}
            </div>
            <p>
                This code is valid for the next 10 minutes. If you did not request this verification, please ignore this email.
            </p>
            <p>
                Thank you for choosing our platform!
            </p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} PlaceIt. All rights reserved.</p>
            <p>
                <a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a>
            </p>
        </div>
    </div>
</body>
</html>
