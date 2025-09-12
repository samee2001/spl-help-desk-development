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

    if ($status_name === 'Delete') {
        // Delete the ticket
        $stmt = $conn->prepare("DELETE FROM tb_ticket WHERE tk_id = ?");
        $stmt->bind_param('i', $tk_id);
        if ($stmt->execute()) {
            // Send response
            echo json_encode(['success' => true, 'message' => 'Ticket deleted and action logged']);
        } else {
            echo json_encode(['success' => false, 'error' => $stmt->error]);
        }

        $stmt->close();
        exit;
    } else {
        // Prepare and execute the update
        $stmt = $conn->prepare("UPDATE tb_ticket SET status_name = ? WHERE tk_id = ?");
        $stmt->bind_param('si', $status_name, $tk_id);

        if ($stmt->execute()) {
            // Log the update
            date_default_timezone_set('Asia/Colombo');
            $date = date('F j, Y');
            $log_stmt = $conn->prepare("INSERT INTO tb_ticket_log (tk_id, status_name, changed_at) VALUES (?, ?, ?)");
            $log_stmt->bind_param('iss', $tk_id, $status_name, $date);
            $log_stmt->execute();
            $log_stmt->close();

            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $stmt->error]);
        }
        $stmt->close();
        $conn->close();
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}
