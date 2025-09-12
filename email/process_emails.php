<?php
// email_processor.php
// This script connects to an IMAP mailbox, fetches unread emails,
// saves them to the database, and forwards them to agents based on keywords.

// --- Manual Autoloader for php-imap (replaces Composer) ---
spl_autoload_register(function ($class) {
    $prefix = 'PhpImap\\';
    $base_dir = __DIR__ . '/../php-imap/src/PhpImap/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

// --- Include Libraries and Configurations ---

// 1. PHPMailer Library
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require __DIR__ . '/../PHPMailer/src/Exception.php';
require __DIR__ . '/../PHPMailer/src/PHPMailer.php';
require __DIR__ . '/../PHPMailer/src/SMTP.php';

// 2. Your Configuration Files (with correct paths)
require __DIR__ . '/../configs/db_connection.php';      // Database connection
require __DIR__ . '/../configs/imap_config.php';      // IMAP configuration
require __DIR__ . '/../configs/email_config.php';     // SMTP configuration

// 3. Use the php-imap Mailbox class
use PhpImap\Mailbox;

// --- Main Configuration ---

// Agent forwarding rules from your new script
$forwarding_rules = [
    'software'   => 'padmall@sadaharitha.com',
    'network'    => 'wasanthas@sadaharitha.com',
    'commission' => 'jayanr@sadaharitha.com',
];

echo "Starting email processing...\n";

try {
    // Connect to the mailbox using constants from imap_config.php
    $mailbox = new Mailbox(
        IMAP_SERVER,
        IMAP_USERNAME,
        IMAP_PASSWORD,
        __DIR__ . '/../attachments' // Attachment directory
    );

    echo "Successfully connected to the mailbox.\n";

    // Search for all unread emails
    $mailIds = $mailbox->searchMailbox('UNSEEN');

    if (empty($mailIds)) {
        echo "No new unread emails found.\n";
        exit;
    }

    echo count($mailIds) . " unread emails found. Processing...\n";

    foreach ($mailIds as $mailId) {
        $mail = $mailbox->getMail($mailId);

        $fromAddress = $mail->fromAddress;
        $subject = $mail->subject ?? 'No Subject';
        $body = $mail->textHtml ?: nl2br($mail->textPlain ?? ''); // Get HTML body, fallback to plain text
        $receivedDate = date('Y-m-d H:i:s', strtotime($mail->date));

        echo "\n----------------------------------------\n";
        echo "Processing email from {$fromAddress} | Subject: {$subject}\n";

        // Determine forwarding address based on subject keywords
        $forward_to = null;
        foreach ($forwarding_rules as $keyword => $agent_email) {
            if (stripos($subject, $keyword) !== false) {
                $forward_to = $agent_email;
                echo "Keyword '{$keyword}' found. Will forward to: {$agent_email}\n";
                break;
            }
        }

        // Insert email into the database FIRST
        try {
            $sql = "INSERT INTO tb_email_logs (from_address, subject, body, received_at, forwarded_to) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            // Use "s" for all types as they are all strings here
            $stmt->bind_param("sssss", $fromAddress, $subject, $body, $receivedDate, $forward_to);

            if ($stmt->execute()) {
                echo "Successfully saved email to database.\n";
            } else {
                echo "Error saving email to database: " . $stmt->error . "\n";
            }
            $stmt->close();
        } catch (Exception $db_e) {
            echo "Database Error: " . $db_e->getMessage() . "\n";
        }

        // Forward the email if a matching agent was found
        if ($forward_to) {
            $forwarder = new PHPMailer(true);
            try {
                // Server settings from email_config.php
                $forwarder->isSMTP();
                $forwarder->Host = SMTP_HOST;
                $forwarder->SMTPAuth = true;
                $forwarder->Username = SMTP_USERNAME;
                $forwarder->Password = SMTP_PASSWORD;
                $forwarder->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $forwarder->Port = SMTP_PORT;

                // This is the fix for the "certificate verify failed" error
                $forwarder->SMTPOptions = [
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    ]
                ];

                // Sender and recipient
                $forwarder->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
                $forwarder->addAddress($forward_to);
                $forwarder->addReplyTo($fromAddress); // Allow agent to reply directly

                // Content
                $forwarder->isHTML(true); // Send as HTML
                $forwarder->Subject = "Fwd: " . $subject;
                $forwarder->Body    = "<b>--- Original Message From: {$fromAddress} ---</b><br><hr>" . $body;
                $forwarder->AltBody = "--- Original Message From: {$fromAddress} ---\n\n" . strip_tags($body);

                $forwarder->send();
                echo "Email successfully forwarded to {$forward_to}.\n";

            } catch (Exception $e) {
                echo "PHPMailer Error: Could not forward email. {$forwarder->ErrorInfo}\n";
            }
        } else {
            echo "No matching keyword found. Email was logged but not forwarded.\n";
        }

        // Mark the email as read
        $mailbox->markMailAsRead($mailId);
        echo "Marked email as read.\n";
    }

} catch (Exception $e) {
    die("An error occurred: " . $e->getMessage() . "\n");
}

echo "\n----------------------------------------\n";
echo "Email processing complete.\n";
?>