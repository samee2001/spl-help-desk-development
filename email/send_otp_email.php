<?php
// send_otp_email.php

// Include PHPMailer files manually (since you didn't use Composer)
require_once __DIR__ . '/../PHPMailer/src/Exception.php';  // Include Exception class
require_once __DIR__ . '/../PHPMailer/src/PHPMailer.php';   // Include PHPMailer class
require_once __DIR__ . '/../PHPMailer/src/SMTP.php';        // Include SMTP class

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include the email configuration file
include_once(__DIR__ . '/../configs/email_config.php');


/**
 * Send OTP email to user for password reset
 * 
 * @param string $toEmail The recipient's email address
 * @param string $otp The OTP code to send
 * @param string $userName Optional user name for personalization
 * @return bool Returns true if email sent successfully, false otherwise
 */
function sendOTPEmail($toEmail, $otp) {
    $mail = new PHPMailer(true);  // Instantiate PHPMailer
    
    try {
        // Server settings
        $mail->isSMTP(); // Set mailer to use SMTP
        $mail->Host = SMTP_HOST;  // SMTP server
        $mail->SMTPAuth = true;  // Enable SMTP authentication
        $mail->Username = SMTP_USERNAME;  // Your Gmail address
        $mail->Password = SMTP_PASSWORD;  // Your Gmail app-specific password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  // Use TLS encryption
        $mail->Port = SMTP_PORT;  // Port 587 for TLS

        // Recipients
        $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);  // Sender's email and name
        $mail->addAddress($toEmail);  // Add recipient's email

        // Content
        $mail->isHTML(true);  // Send HTML email
        $mail->Subject = "Password Reset OTP - Sadaharitha";  // Subject of the email
        
        // Create the HTML email body by including the external HTML file
        $greeting = "Dear User,";
        $emailBody = include 'otp_email_body.php'; // Include the body of the email

        // Replace placeholders in the body
        $emailBody = str_replace('{{greeting}}', $greeting, $emailBody);
        $emailBody = str_replace('{{otp}}', $otp, $emailBody);

        // Set email body
        $mail->Body = $emailBody;
        
        // Add embedded image
        $mail->addEmbeddedImage(__DIR__ . '/../assets/sadaharitha_email.jpg', 'sadaharitha_logo');
        
        // Send the email
        $mail->send();
        return true;  // Email sent successfully
        
    } catch (Exception $e) {
        error_log('OTP Email Error: ' . $mail->ErrorInfo);
        return false;  // If email fails to send
    }
}
?>
