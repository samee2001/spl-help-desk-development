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

            // Prepare and send the email
            $subject = 'New Task Assigned';
            $body = "Hello, you have been assigned a new task. Here are the details:<br><br>";
            $body .= "Organization: $org_name<br>";
            $body .= "Contact: $contact_name<br>";
            $body .= "Description: $description<br>";
            $body .= "Summary: $summary<br>";
            $body .= "Priority: $priority<br>";
            $body .= "Category: $cat_name<br>";
            $body = "Don't reply to this e-mail <br>";
            $body .   

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
