<?php
include '../configs/db_connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tk_id = $_POST['tk_id'] ?? null;
    $status_name = $_POST['status_name'] ?? null;

    if (!$tk_id || !$status_name) {
        echo json_encode(['success' => false, 'error' => 'Missing parameters']);
        exit;
    }

    // Prepare and execute the update
    $stmt = $conn->prepare("UPDATE tb_ticket SET status_name = ? WHERE tk_id = ?");
    $stmt->bind_param('si', $status_name, $tk_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}
?>
