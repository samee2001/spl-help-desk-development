<?php
session_start(); // Start the session to manage user login state
include 'configs/db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_email'])) {
    header('Location: login.php');
    exit();
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $organization = $_POST['organization'] ?? '';
    $description = $_POST['description'] ?? '';
    $contact = $_POST['contact'] ?? '';
    $summary = $_POST['summary'] ?? '';
    $assignee = $_POST['assignee'] ?? '';
    $priority = $_POST['priority'] ?? '';
    $category = $_POST['category'] ?? '';

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
        $stmt = $conn->prepare("INSERT INTO tb_ticket (tk_summary, tk_description, tk_priority, tk_created_at, org_id, ur_id,  cat_id) VALUES (?, ?, ?, ?,  ?, ?, ?)");
        $stmt->bind_param("ssssiii", $summary, $description, $priority, $created_at, $organization, $contact,  $category);

        if ($stmt->execute()) {
            // Redirect or show success
            header("Location: index.php");
            exit;
        } else {
            $errors[] = "Database error: " . $conn->error;
        }
    }
}
?>
<!-- Display errors if any -->
<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <?php foreach ($errors as $e) echo htmlspecialchars($e) . "<br>"; ?>
    </div>
<?php endif; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Helpdesk Interface</title>
    <!-- <link rel="stylesheet" href="style.css">  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        td a {
            text-decoration: none !important;
            color: black;
        }
    </style>
</head>

<body>
    <div class="container-fluid p-0">
        <!-- Header -->
        <div class="mb-3">
            <?php include 'components/nav_bar.php'; ?>
        </div>

        <div class="row g-0">
            <!-- Sidebar -->
            <?php include 'components/side_bar.php'; ?>

            <!-- Main Content -->
            <main class="col-md-10 p-4">
                <!-- Toolbar -->
                <div>
                    <?php include 'components/tool_bar.php'; ?>

                </div>
                <!-- Table -->
                <div>
                    <?php include 'components/table.php'; ?>
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap Modal for New Ticket -->
    <div class="modal fade" id="newTicketModal" tabindex="-1" aria-labelledby="newTicketModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newTicketModalLabel">Create New Ticket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="organization">Organization:</label>
                            <select class="form-select" id="organization" name="organization">
                                <option value="">Select organization</option>
                                <?php
                                $result = mysqli_query($conn, "SELECT org_name, org_id FROM tb_organization");
                                if ($result) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo '<option value="' . htmlspecialchars($row['org_id']) . '">' . htmlspecialchars($row['org_name']) . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="contact">Contact:</label>
                            <select class="form-select" id="contact" name="contact">
                                <option value="">Select contact</option>
                                <?php
                                $result = mysqli_query($conn, "SELECT ur_name, ur_id FROM tb_user");
                                if ($result) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo '<option value="' . htmlspecialchars($row['ur_id']) . '">' . htmlspecialchars($row['ur_name']) . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="description">Description:</label>
                            <textarea id="description" name="description" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="summary">Summary:</label>
                            <textarea id="summary" name="summary" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="assignee">Assignee:</label>
                            <select class="form-select" id="assignee" name="assignee">
                                <option value="">Select assignee</option>
                                <?php
                                $result = mysqli_query($conn, "SELECT ur_name, ur_id, ur_email FROM tb_user");
                                if ($result) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo '<option value="' . htmlspecialchars($row['ur_id']) . '">' . htmlspecialchars($row['ur_email']) . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="priority">Priority:</label>
                            <select class="form-select" id="priority" name="priority">
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="category">Category:</label>
                            <select class="form-select" id="category" name="category">
                                <option value="">Select category</option>
                                <?php
                                $result = mysqli_query($conn, "SELECT cat_name, cat_id FROM tb_category");
                                if ($result) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo '<option value="' . htmlspecialchars($row['cat_id']) . '">' . htmlspecialchars($row['cat_name']) . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <input type="file" id="fileInput" name="attachments[]" multiple style="display: none;">
                            <button type="button" id="attachBtn" class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center">
                                <i class="fas fa-paperclip me-2"></i> Attach files
                            </button>
                            <!-- File list will be rendered here -->
                            <div id="fileList" class="mt-2"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.js"></script>
    <script src="js/new_ticket.js"></script>
</body>

</html>