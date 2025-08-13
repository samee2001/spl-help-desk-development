<?php
session_start();
include '../configs/db_connection.php';
// Include and use your existing email sending function
include '../email/send_email.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $ticket_id = $data['ticket_id'] ?? '';
    $message = $data['message'] ?? '';
    $sender_email = $_SESSION['user_email'] ?? '';

    if (empty($ticket_id) || empty($message) || empty($sender_email)) {
        echo json_encode(['success' => false, 'error' => 'Missing required fields']);
        exit;
    }

    // Get ticket creator email
    $creator_stmt = $conn->prepare("SELECT tk_creator FROM tb_ticket WHERE tk_id = ?");
    $creator_stmt->bind_param("s", $ticket_id);
    $creator_stmt->execute();
    $creator_result = $creator_stmt->get_result();

    if ($creator_result->num_rows === 0) {
        echo json_encode(['success' => false, 'error' => 'Ticket not found']);
        exit;
    }

    $ticket_data = $creator_result->fetch_assoc();
    $creator_email = $ticket_data['tk_creator'];

    // Validate email address
    if (!filter_var($creator_email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'error' => 'Invalid creator email address']);
        exit;
    }

    // Insert message into conversation table
    $insert_stmt = $conn->prepare("INSERT INTO tb_ticket_conversation (tk_id, sender_email, receiver_email, message, sent_at) VALUES (?, ?, ?, ?, NOW())");
    $insert_stmt->bind_param("ssss", $ticket_id, $sender_email, $creator_email, $message);

    if ($insert_stmt->execute()) {
        // Send email notification to creator
        $subject = "New message on Ticket #$ticket_id";
        $email_body = include '../email/message_body.php';



        // Send the email
        $email_sent = sendEmail($creator_email, $subject, $email_body);

        if ($email_sent) {
            echo json_encode(['success' => true, 'message' => 'Message sent successfully and email notification delivered']);
        } else {
            // Log the error for debugging
            error_log("Failed to send email to: $creator_email for ticket: $ticket_id");
            echo json_encode(['success' => true, 'message' => 'Message sent successfully but email notification failed']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to save message']);
    }

    $insert_stmt->close();
    $creator_stmt->close();
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $ticket_id = $_GET['ticket_id'] ?? '';

    if (empty($ticket_id)) {
        echo json_encode(['success' => false, 'error' => 'Ticket ID required']);
        exit;
    }

    // Get conversation history
    $stmt = $conn->prepare("SELECT sender_email, message, sent_at FROM tb_ticket_conversation WHERE tk_id = ? ORDER BY sent_at ASC");
    $stmt->bind_param("s", $ticket_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $conversation = [];
    while ($row = $result->fetch_assoc()) {
        $conversation[] = [
            'sender_email' => $row['sender_email'],
            'message' => $row['message'],
            'sent_at' => $row['sent_at'],
            'is_creator' => $row['sender_email'] === $_SESSION['user_email']
        ];
    }

    echo json_encode(['success' => true, 'conversation' => $conversation]);
    $stmt->close();
}

$conn->close();
