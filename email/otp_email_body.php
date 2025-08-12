<?php
return "
<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Password Reset OTP</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .logo { max-width: 200px; height: auto; }
        .otp-box { 
            background: #f8f9fa; 
            border: 2px solid #007bff; 
            border-radius: 10px; 
            padding: 20px; 
            text-align: center; 
            margin: 20px 0; 
        }
        .otp-code { 
            font-size: 32px; 
            font-weight: bold; 
            color: #007bff; 
            letter-spacing: 5px; 
            margin: 10px 0; 
        }
        .warning { 
            background: #fff3cd; 
            border: 1px solid #ffeaa7; 
            border-radius: 5px; 
            padding: 15px; 
            margin: 20px 0; 
        }
        .footer { 
            text-align: center; 
            margin-top: 30px; 
            padding-top: 20px; 
            border-top: 1px solid #eee; 
            color: #666; 
            font-size: 14px; 
        }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <img src='cid:sadaharitha_logo' alt='Sadaharitha Logo' class='logo'>
            <h2>Password Reset Request</h2>
        </div>
        
        <p>{{greeting}}</p>
        
        <p>We received a request to reset your password. Please use the following OTP (One-Time Password) to complete your password reset:</p>
        
        <div class='otp-box'>
            <h3>Your OTP Code</h3>
            <div class='otp-code'>{{otp}}</div>
            <p><strong>This OTP will expire in 10 minutes.</strong></p>
        </div>
        
        <div class='warning'>
            <strong>Important:</strong>
            <ul>
                <li>This OTP is valid for 10 minutes only</li>
                <li>Do not share this OTP with anyone</li>
                <li>If you didn't request this password reset, please ignore this email</li>
            </ul>
        </div>
        
        <p>If you have any questions or need assistance, please contact our support team.</p>
        
        <div class='footer'>
            <p>This is an automated message from Sadaharitha Help Desk System.</p>
            <p>Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
";
?>