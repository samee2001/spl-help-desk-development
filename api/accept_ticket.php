<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_email'])) {
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit;
}
if (empty($_POST['ticket_id'])) {
    echo json_encode(['success' => false, 'error' => 'No ticket ID']);
    exit;
}

require_once 'configs/db_connection.php';

$ticketId = intval($_POST['ticket_id']);
$userEmail = $_SESSION['user_email'];

// Get assignee's name
$stmt = $conn->prepare("SELECT ur_name, ur_id FROM tb_user WHERE ur_email = ?");
$stmt->bind_param('s', $userEmail);
$stmt->execute();
$stmt->bind_result($userName, $userId);
$stmt->fetch();
$stmt->close();

if (!$userId) {
    echo json_encode(['success' => false, 'error' => 'User not found']);
    exit;
}

// Assign the ticket
$stmt2 = $conn->prepare("UPDATE tb_ticket SET tk_assignee = ? WHERE tk_id = ?");
$stmt2->bind_param('ii', $userId, $ticketId);
$success = $stmt2->execute();
$stmt2->close();

echo json_encode([
    'success' => $success,
    'assignee_name' => $userName ?: $userEmail,
]);
