<?php
// Include the email configuration file
include'../configs/db_connection.php';// your DB connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate & sanitize inputs
    $organization = isset($_POST['organization']) ? intval($_POST['organization']) : null;
    $contact      = isset($_POST['contact']) ? intval($_POST['contact']) : null;
    $assignee     = isset($_POST['assignee']) ? intval($_POST['assignee']) : null;
    $priority     = isset($_POST['priority']) ? trim($_POST['priority']) : null;
    $category     = isset($_POST['category']) ? intval($_POST['category']) : null;

    // Example: ticket_id could come from hidden input or URL
    $ticket_id    = isset($_POST['ticket_id']) ? intval($_POST['ticket_id']) : null;

    if ($organization && $contact && $assignee && $priority && $category && $ticket_id) {
        // Prepare update query
        $sql = "UPDATE tb_ticket 
                SET org_id = ?, 
                    ur_id = ?, 
                    tk_assignee = ?, 
                    tk_priority = ?, 
                    cat_id = ?
                WHERE tk_id = ?";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("iiissi", $organization, $contact, $assignee, $priority, $category, $ticket_id);

            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Ticket updated successfully!']);
                header('Location: ../index.php');
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Update failed.']);
                //header('Location: ../index.php');
            }
            $stmt->close();
        } else {
            echo json_encode(['status' => 'error', 'message' => 'SQL prepare failed.']);
           // header('Location: ../index.php');
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid input values.']);
        //header('Location: ../index.php');
    }
}
$conn->close();
?>