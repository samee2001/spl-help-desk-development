<?php
// Include the email configuration file
include_once('../configs/db_connection.php');// your DB connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate & sanitize inputs
    $organization = isset($_POST['organization']) ? intval($_POST['organization']) : null;
    $contact      = isset($_POST['contact']) ? intval($_POST['contact']) : null;
    $assignee     = isset($_POST['assignee']) ? intval($_POST['assignee']) : null;
    $priority     = isset($_POST['priority']) ? trim($_POST['priority']) : null;

    // Example: ticket_id could come from hidden input or URL
    $ticket_id    = isset($_POST['ticket_id']) ? intval($_POST['ticket_id']) : null;

    if ($organization && $contact && $assignee && $priority && $ticket_id) {
        // Prepare update query
        $sql = "UPDATE tb_ticket 
                SET organization_id = ?, 
                    contact_id = ?, 
                    assignee_id = ?, 
                    priority = ? 
                WHERE ticket_id = ?";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("iiisi", $organization, $contact, $assignee, $priority, $ticket_id);

            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Ticket updated successfully!']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Update failed.']);
            }
            $stmt->close();
        } else {
            echo json_encode(['status' => 'error', 'message' => 'SQL prepare failed.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid input values.']);
    }
}
$conn->close();

?>