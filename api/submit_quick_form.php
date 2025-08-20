<?php
// Include DB + mailer
include '../configs/db_connection.php';
include '../configs/email_config.php'; 
include '../email/send_ticket_change_mail.php';// make sure this defines sendTicketChangeEmail()

// Enable exceptions for mysqli so we can use try/catch
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

/**
 * Load and cache an .sql file by base name (without extension)
 */
function load_sql(string $name): string
{
    static $cache = [];
    if (!isset($cache[$name])) {
        $path = __DIR__ . '/../queries/' . basename($name) . '.sql';
        $sql  = @file_get_contents($path);
        if ($sql === false) {
            throw new Exception("SQL file not found or unreadable: " . $path);
        }
        $cache[$name] = trim($sql);
    }
    return $cache[$name];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    // Validate & sanitize inputs
    $organization = isset($_POST['organization']) ? intval($_POST['organization']) : null;
    $contact      = isset($_POST['contact'])      ? intval($_POST['contact'])      : null;
    $assignee     = isset($_POST['assignee'])     ? intval($_POST['assignee'])     : null;
    $priority     = isset($_POST['priority'])     ? trim((string)$_POST['priority']) : null;
    $category     = isset($_POST['category'])     ? intval($_POST['category'])     : null;
    $ticket_id    = isset($_POST['ticket_id'])    ? intval($_POST['ticket_id'])    : null;

    if ($organization && $contact && $assignee && $priority && $category && $ticket_id) {
        try {
            // 1) get current assignee
            $selAssigneeSql = load_sql('select_ticket_assignee');
            $selStmt = $conn->prepare($selAssigneeSql);
            $selStmt->bind_param("i", $ticket_id);
            $selStmt->execute();
            $selStmt->bind_result($currentAssigneeId);
            $hasRow = $selStmt->fetch();
            $selStmt->close();

            if (!$hasRow) {
                echo json_encode(['status' => 'error', 'message' => 'Ticket not found.']);
                $conn->close();
                exit;
            }

            // 2) update ticket
            $sql = load_sql('update_ticket');
            $stmt = $conn->prepare($sql);
            // org (i), contact (i), assignee (i), priority (s), category (i), ticket_id (i)
            $stmt->bind_param("iiisii", $organization, $contact, $assignee, $priority, $category, $ticket_id);
            $stmt->execute();
            $stmt->close();

            // 3) log (non-blocking)
            try {
                $logSql = load_sql('insert_ticket_log');
                $logStmt = $conn->prepare($logSql);
                $logStmt->bind_param("iiiisi", $ticket_id, $organization, $contact, $assignee, $priority, $category);
                $logStmt->execute();
                $logStmt->close();
            } catch (Exception $e) {
                error_log("Ticket log insert failed for tk_id {$ticket_id}: " . $e->getMessage());
            }

            // 4) email only if assignee changed
            if ((int)$currentAssigneeId !== (int)$assignee) {
                try {
                    // get new assignee email + name
                    $selEmailSql = load_sql('select_ur_email'); // should return (email, full_name)
                    $emStmt = $conn->prepare($selEmailSql);
                    $emStmt->bind_param("i", $assignee);
                    $emStmt->execute();
                    $assigneeEmail = null;
                    $assigneeName  = null;
                    $emStmt->bind_result($assigneeEmail, $assigneeName);
                    $emailRow = $emStmt->fetch();
                    $emStmt->close();

                    if ($emailRow && filter_var($assigneeEmail, FILTER_VALIDATE_EMAIL)) {
                        // Lookup category name for email template
                        $categoryName = null;
                        if ($category) {
                            $catStmt = $conn->prepare("SELECT cat_name FROM tb_category WHERE cat_id = ?");
                            $catStmt->bind_param("i", $category);
                            $catStmt->execute();
                            $catStmt->bind_result($categoryName);
                            $catStmt->fetch();
                            $catStmt->close();
                        }
                        // Now include the template
                        ob_start();
                        include __DIR__ . '/../email/ticket_change_mail_body.php';
                        $bodyHtml = ob_get_clean();

                        $subject = "Ticket #{$ticket_id} assigned to you";
                        sendTicketChangeEmail($assigneeEmail, $subject, $bodyHtml);
                    }
                } catch (Exception $e) {
                    error_log("Lookup/send assignee email failed (ur_id {$assignee}): " . $e->getMessage());
                }
            }

            echo json_encode(['status' => 'success', 'message' => 'Ticket updated successfully!']);
        } catch (Exception $e) {
            error_log("DB Error (update): " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Database error occurred.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid input values.']);
    }
}

$conn->close();
