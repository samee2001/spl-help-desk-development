<?php
// form_handler.php
session_start();

// Set header to return JSON
header('Content-Type: application/json');

// Include necessary files
include_once('send_email.php');
include_once('../configs/db_connection.php');
include_once('../configs/email_config.php');

// Check if form is submitted
$errors = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capture the form inputs
    $organization = $_POST['organization'] ?? '';
    $description = $_POST['description'] ?? '';
    $contact = $_POST['contact'] ?? '';
    $summary = $_POST['summary'] ?? '';
    $assignee = $_POST['assignee'] ?? '';
    $priority = $_POST['priority'] ?? '';
    $category = $_POST['category'] ?? '';
    $creator = $_SESSION['user_email'];

    //insert the ticket     🤨😁😁😀😀
    date_default_timezone_set('Asia/Colombo');
    $created_at = date('F j, Y h:i A');

    // Validate required fields
    if (!$organization || !$contact || !$summary || !$category) {
        $errors[] = "Please fill in all required fields.";
    }

    // Handle file uploads
    $uploaded_files = [];
    if (!empty($_FILES['attachments']['name'][0])) {
        $allowed_types = ['application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'image/jpeg', 'image/png', 'image/gif'];
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

        foreach ($_FILES['attachments']['tmp_name'] as $key => $tmp_name) {
            $file_name = basename($_FILES['attachments']['name'][$key]);
            $file_type = $_FILES['attachments']['type'][$key];
            $file_tmp = $_FILES['attachments']['tmp_name'][$key];

            if (!in_array($file_type, $allowed_types)) {
                $errors[] = "$file_name is not an allowed file type.";
                continue;
            }

            $target_path = $upload_dir . uniqid() . '_' . $file_name;
            if (move_uploaded_file($file_tmp, $target_path)) {
                $uploaded_files[] = $target_path;
            } else {
                $errors[] = "Failed to upload $file_name.";
            }
        }
    }

    // If no errors, insert into database
    if (empty($errors)) {
        $attachments = implode(',', $uploaded_files); // Store as comma-separated paths
        $stmt = $conn->prepare("INSERT INTO tb_ticket (tk_summary, tk_description, tk_assignee,tk_creator,tk_priority, tk_created_at, org_id, ur_id,  cat_id) VALUES (?, ?, ?, ?,  ?, ?, ?,?,?)");
        $stmt->bind_param("ssssssiii", $summary, $description, $assignee, $creator, $priority, $created_at, $organization, $contact,  $category);

        if ($stmt->execute()) {
            // Insert log into tb_ticket_log
            $last_ticket_id = $conn->insert_id;
            $log_status = 'Open';
            $log_time = date('Y-m-d H:i:s');
            $stmt_log = $conn->prepare("INSERT INTO tb_ticket_log (tk_id, status_name, changed_at) VALUES (?, ?, ?)");
            $stmt_log->bind_param("iss", $last_ticket_id, $log_status, $log_time);
            $stmt_log->execute();
            $stmt_log->close();

            // Get the assignee's email from the database
            $assigneeEmail = '';
            $result = mysqli_query($conn, "SELECT ur_email FROM tb_user WHERE ur_id = '$assignee'");
            if ($row = mysqli_fetch_assoc($result)) {
                $assigneeEmail = $row['ur_email'];
            }

            // Fetch org_name, cat_name, and ur_name for email
            $org_name = $cat_name = $contact_name = '';
            // Organization name
            $stmt_org = $conn->prepare("SELECT org_name FROM tb_organization WHERE org_id = ?");
            $stmt_org->bind_param("i", $organization);
            $stmt_org->execute();
            $stmt_org->bind_result($org_name);
            $stmt_org->fetch();
            $stmt_org->close();
            // Category name
            $stmt_cat = $conn->prepare("SELECT cat_name FROM tb_category WHERE cat_id = ?");
            $stmt_cat->bind_param("i", $category);
            $stmt_cat->execute();
            $stmt_cat->bind_result($cat_name);
            $stmt_cat->fetch();
            $stmt_cat->close();
            // Contact name
            $stmt_contact = $conn->prepare("SELECT ur_name FROM tb_user WHERE ur_id = ?");
            $stmt_contact->bind_param("i", $contact);
            $stmt_contact->execute();
            $stmt_contact->bind_result($contact_name);
            $stmt_contact->fetch();
            $stmt_contact->close();

            // Fetch assignee name
            $assignee_name = '';
            $stmt_assignee = $conn->prepare("SELECT ur_name FROM tb_user WHERE ur_id = ?");
            $stmt_assignee->bind_param("i", $assignee);
            $stmt_assignee->execute();
            $stmt_assignee->bind_result($assignee_name);
            $stmt_assignee->fetch();
            $stmt_assignee->close();
            // Prepare and send the email
            $subject = $summary;
            $body = "
                        <html>
                        <body>
                            <p>Hello, you have been assigned a new task. Here are the details:</p>
                            <br>
                            <p><b>From:</b> $org_name</p>
                            <p id='current-date-time'><b>Sent:</b> " . date('F j, Y h:i A') . "</p>
                            <p><b>Subject:</b> $summary</p> 
                            <p><b>Assignee:</b> $assignee_name</p>

                            <p><b>Contact:</b> $contact_name</p>
                            <p><b>Description:</b> $description</p>
                            <p><b>Summary:</b> $summary</p>
                            <p><b>Priority:</b> $priority</p>
                            <p><b>Category:</b> $cat_name</p>
                            <p><b>Created by:</b> " . $_SESSION['user_email'] . "</p>
                            <br><hr>
                            <img src='cid:sadaharitha_logo' alt='Logo' style='max-width:200px;'>
                            <br>
                            <p><b>­­­No: 06 A, Alfred Place, Colombo 03, Sri Lanka</b></p>
                            <p><b>Phone:</b> +94 115 234000 / +94 117 234800</p>
                            <p><b>Fax:</b> +94 112 564001</p>
                            <p><b>Mobile:</b> +94 773 671 399</p>
                            <p><b>Don't reply to this e-mail.</b></p>
                        </body>
                        </html>
                    ";

            $emailSent = sendEmail($assigneeEmail, $subject, $body);

            if ($emailSent) {
                echo json_encode(['success' => true, 'message' => 'Ticket created and email sent!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Ticket created, but failed to send email.']);
            }
            mysqli_close($conn);
            exit;
        } else {
            $errors[] = "Database error: " . $conn->error;
        }
    }

    // If there are errors, return them as JSON
    echo json_encode(['success' => false, 'message' => implode(' ', $errors)]);
    mysqli_close($conn);
    exit;
}

// Handle cases where the script is accessed directly
echo json_encode(['success' => false, 'message' => 'Invalid request.']);
