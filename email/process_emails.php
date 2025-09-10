<?php

// --- Manual Autoloader for php-imap (replaces Composer) ---
spl_autoload_register(function ($class) {
    // The namespace of the library
    $prefix = 'PhpImap\\';
    // The base directory for the library
    $base_dir = __DIR__ . '/../php-imap/src/PhpImap/';

    // Check if the class uses the namespace prefix
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return; // Not a class from this library, do nothing
    }

    // Get the relative class name (e.g., Mailbox)
    $relative_class = substr($class, $len);

    // Build the file path
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // If the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});

// --- Include Other Libraries and Configurations ---

// 1. PHPMailer Library (can be removed if not used elsewhere, but safe to keep)
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../PHPMailer/src/Exception.php';
require __DIR__ . '/../PHPMailer/src/PHPMailer.php';
require __DIR__ . '/../PHPMailer/src/SMTP.php';

// 2. Your Configuration Files
require __DIR__ . '/../configs/email_config.php'; // For sending emails (SMTP)
require __DIR__ . '/../configs/imap_config.php';  // For reading emails (IMAP)
require __DIR__ . '/../configs/db_connection.php'; // For database connection

// 3. Use the php-imap Mailbox class
use PhpImap\Mailbox;

// --- Script Logic ---

echo "Starting email logging process...\n";

try {
    // Connect to the mailbox using the php-imap library
    $mailbox = new Mailbox(
        IMAP_SERVER,      // IMAP server path from your config
        IMAP_USERNAME,    // Username from your config
        IMAP_PASSWORD,    // Password from your config
        __DIR__ . '/../attachments' // Optional: A directory to save email attachments
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
        $subject = $mail->subject ?? '[No Subject]'; // Use a fallback for safety
        $fromAddress = $mail->fromAddress;

        echo "\n----------------------------------------\n";
        echo "Processing email #{$mailId} from {$fromAddress} | Subject: {$subject}\n";

        // --- Database Insert Logic ---
        try {
            // Prepare the SQL statement to prevent SQL injection
            $sql = "INSERT INTO tb_email_logs (subject, from_address) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);

            // Bind the subject and sender's email to the statement
            $stmt->bind_param("ss", $subject, $fromAddress);

            // Execute the statement
            if ($stmt->execute()) {
                echo "Successfully saved subject to database.\n";
                // Mark email as read ONLY if it was saved to DB successfully
                $mailbox->markMailAsRead($mailId);
                echo "Marked email as read.\n";
            } else {
                echo "Error saving subject to database: " . $stmt->error . "\n";
            }
            $stmt->close();
        } catch (Exception $db_e) {
            echo "Database Error: " . $db_e->getMessage() . "\n";
        }
    }
} catch (Exception $e) {
    die("An error occurred while connecting or processing emails: " . $e->getMessage() . "\n");
}

echo "\n----------------------------------------\n";
echo "Email logging complete.\n";
