<?php
// send_email.php

// Include PHPMailer files manually (since you didn't use Composer)
require_once __DIR__ . '/../PHPMailer/src/Exception.php';  // Include Exception class
require_once __DIR__ . '/../PHPMailer/src/PHPMailer.php';   // Include PHPMailer class
require_once __DIR__ . '/../PHPMailer/src/SMTP.php';        // Include SMTP class

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include the email configuration file
include_once __DIR__ . '/../configs/email_config.php';

function sendTicketChangeEmail($toEmail, $subject, $bodyHtml) {
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
        $mail->Subject = $subject;  // Subject of the email
        $mail->Body = $bodyHtml;  // Body content of the email
        // Send the email
        $mail->send();
        return true;  // Email sent successfully
    } catch (Exception $e) {
        error_log('Mailer Error: ' . $mail->ErrorInfo);
        return false;  // If email fails to send
    }
}
?>
