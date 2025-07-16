<?php
session_start();
include '../configs/db_connection.php'; // Adjust path if needed

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_email'])) {
    echo json_encode(['success' => false, 'message' => 'Authentication required. Please log in.']);
    exit();
}

// Get the POST data sent as JSON
$data = json_decode(file_get_contents('php://input'), true);
$ticket_id = $data['ticket_id'] ?? null;

if (!$ticket_id) {
    echo json_encode(['success' => false, 'message' => 'Ticket ID is missing.']);
    exit();
}

$user_email = $_SESSION['user_email'];
$user_id = null;
$user_name = null;

// 1. Get the logged-in user's ID and name from their email
$stmt_user = $conn->prepare("SELECT ur_id, ur_name FROM tb_user WHERE ur_email = ?");
$stmt_user->bind_param("s", $user_email);
$stmt_user->execute();
$result_user = $stmt_user->get_result();

if ($result_user->num_rows === 1) {
    $user = $result_user->fetch_assoc();
    $user_id = $user['ur_id'];
    $user_name = $user['ur_name'];
} else {
    echo json_encode(['success' => false, 'message' => 'User not found in the database.']);
    exit();
}
$stmt_user->close();

// 2. Update the ticket with the new assignee ID and set the updated timestamp
$stmt_update = $conn->prepare("UPDATE tb_ticket SET tk_assignee = ?, tk_updated_at = NOW() WHERE tk_id = ? AND tk_assignee IS NULL");
$stmt_update->bind_param("ii", $user_id, $ticket_id);

if ($stmt_update->execute()) {
    if ($stmt_update->affected_rows > 0) {
        echo json_encode(['success' => true, 'assignee_name' => htmlspecialchars($user_name)]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Ticket could not be assigned. It might already be taken by another user.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Database update failed.']);
}

$stmt_update->close();
$conn->close();
?>

