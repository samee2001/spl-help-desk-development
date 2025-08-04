<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Sadaharitha IT Help Desk</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }
        .header p {
            margin: 10px 0 0 0;
            font-size: 16px;
            opacity: 0.9;
        }
        .content {
            padding: 40px 30px;
        }
        .welcome-message {
            font-size: 18px;
            color: #2c3e50;
            margin-bottom: 25px;
        }
        .details {
            background-color: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin: 25px 0;
            border-radius: 0 8px 8px 0;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .detail-label {
            font-weight: bold;
            color: #555;
        }
        .detail-value {
            color: #2c3e50;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 25px 30px;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }
        .footer p {
            margin: 5px 0;
            color: #6c757d;
            font-size: 14px;
        }
        .logo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-bottom: 15px;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            margin: 20px 0;
            transition: all 0.3s ease;
        }
        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        @media only screen and (max-width: 600px) {
            .email-container {
                margin: 10px;
            }
            .content {
                padding: 20px 15px;
            }
            .header {
                padding: 20px 15px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <img src="cid:sadaharitha_logo" alt="Sadaharitha Logo" class="logo">
            <h1>Welcome to Sadaharitha IT Help Desk</h1>
            <p>Your registration has been completed successfully!</p>
        </div>
        
        <div class="content">
            <div class="welcome-message">
                Dear <?php echo htmlspecialchars($emp_name); ?>,
            </div>
            
            <p>Welcome to Sadaharitha! We are delighted to inform you that your employee registration has been completed successfully. You are now part of our team and can access our help desk system.</p>
            
            <div class="details">
                <div class="detail-row">
                    <span class="detail-label">Employee Name:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($emp_name); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Email Address:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($emp_email); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Designation:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($emp_designation); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Organization:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($emp_organization_name); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Registration Date:</span>
                    <span class="detail-value"><?php echo date('F j, Y'); ?></span>
                </div>
            </div>
            
            <p>You can now register and log in to our help desk system using your email address. If you have any questions or need assistance, please don't hesitate to contact our support team.</p>
            
            <div style="text-align: center;">
                <a href="http://localhost/spl_help_desk_development_v2/register.php" class="cta-button">Access Help Desk</a>
            </div>
        </div>
        
        <div class="footer">
            <p><strong>Sadaharitha Help Desk System</strong></p>
            <p>© <?php echo date('Y'); ?> Sadaharitha. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
