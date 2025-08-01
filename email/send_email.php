<?php
// send_email.php

// Include PHPMailer files manually (since you didn't use Composer)
require_once '../PHPMailer/src/Exception.php';  // Include Exception class
require_once '../PHPMailer/src/PHPMailer.php';   // Include PHPMailer class
require_once '../PHPMailer/src/SMTP.php';        // Include SMTP class

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include the email configuration file
include_once('../configs/email_config.php');

function sendEmail($toEmail, $subject, $body) {
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


        // Embed the image
        // $mail->addEmbeddedImage('../assets/sadaharitha_email.jpg', 'featureimg');
        // Content
        $mail->isHTML(true);  // Send HTML email
        $mail->Subject = $subject;  // Subject of the email
        $mail->Body = $body;  // Body content of the email
        $mail->addEmbeddedImage('../assets/sadaharitha_email.jpg','sadaharitha_logo');
        // Send the email
        $mail->send();
        return true;  // Email sent successfully
    } catch (Exception $e) {
        error_log('Mailer Error: ' . $mail->ErrorInfo);
        return false;  // If email fails to send
    }
}
?>
