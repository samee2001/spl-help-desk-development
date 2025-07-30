<?php
session_start(); // Start the session to manage user login state
include 'configs/db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_email'])) {
    header('Location: login.php');
    exit();
}

// $errors = [];
// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $organization = $_POST['organization'] ?? '';
//     $description = $_POST['description'] ?? '';
//     $contact = $_POST['contact'] ?? '';
//     $summary = $_POST['summary'] ?? '';
//     $assignee = $_POST['assignee'] ?? '';
//     $priority = $_POST['priority'] ?? '';
//     $category = $_POST['category'] ?? '';
//     $creator = $_SESSION['user_email'];

//     date_default_timezone_set('Asia/Colombo');
//     $created_at = date('F j, Y h:i A');

//     // Validate required fields
//     if (!$organization || !$contact || !$summary || !$category) {
//         $errors[] = "Please fill in all required fields.";
//     }

//     // Handle file uploads
//     $uploaded_files = [];
//     if (!empty($_FILES['attachments']['name'][0])) {
//         $allowed_types = ['application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'image/jpeg', 'image/png', 'image/gif'];
//         $upload_dir = 'uploads/';
//         if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

//         foreach ($_FILES['attachments']['tmp_name'] as $key => $tmp_name) {
//             $file_name = basename($_FILES['attachments']['name'][$key]);
//             $file_type = $_FILES['attachments']['type'][$key];
//             $file_tmp = $_FILES['attachments']['tmp_name'][$key];

//             if (!in_array($file_type, $allowed_types)) {
//                 $errors[] = "$file_name is not an allowed file type.";
//                 continue;
//             }

//             $target_path = $upload_dir . uniqid() . '_' . $file_name;
//             if (move_uploaded_file($file_tmp, $target_path)) {
//                 $uploaded_files[] = $target_path;
//             } else {
//                 $errors[] = "Failed to upload $file_name.";
//             }
//         }
//     }

//     // If no errors, insert into database
//     if (empty($errors)) {
//         $attachments = implode(',', $uploaded_files); // Store as comma-separated paths
//         $stmt = $conn->prepare("INSERT INTO tb_ticket (tk_summary, tk_description, tk_assignee,tk_creator,tk_priority, tk_created_at, org_id, ur_id,  cat_id) VALUES (?, ?, ?, ?,  ?, ?, ?,?,?)");
//         $stmt->bind_param("ssssssiii", $summary, $description, $assignee, $creator, $priority, $created_at, $organization, $contact,  $category);

//         if ($stmt->execute()) {
//             // Redirect or show success
//             header("Location: index.php");
//             exit;
//         } else {
//             $errors[] = "Database error: " . $conn->error;
//         }
//     }
// }
?>
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
        <!-- Display errors if any -->
        <div style="position: relative;">
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger alert-dismissible fade show position-absolute top-0 start-50 translate-middle-x mt-3 shadow" style="z-index: 1050; min-width: 300px; max-width: 500px;" role="alert">
                    <?php foreach ($errors as $e) echo htmlspecialchars($e) . "<br>"; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
        </div>
        <div class="row g-0">
            <!-- Sidebar -->
            <?php include 'components/side_bar.php'; ?>

            <!-- Main Content -->
            <main class="col-md-10 p-4">
                <!-- Toolbar -->
                <div>
                    <?php include 'components/tool_bar.php'; ?>
                    <script>
                        // Pass the selected status to the table via URL parameter
                        document.querySelector('.form-select').addEventListener('change', function() {
                            var status = this.value;
                            window.location.href = 'index.php?status=' + status;
                        });
                    </script>
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
                <form id="newTicketForm" action="email/form_handler.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="organization">Organization:</label>
                            <select class="form-select" id="organization" name="organization" required>
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
                            <select class="form-select" id="contact" name="contact" required>
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
                            <textarea id="description" name="description" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="summary">Summary:</label>
                            <textarea id="summary" name="summary" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="assignee">Assignee:</label>
                            <select class="form-select" id="assignee" name="assignee" required>
                                <option value="">Select assignee</option>
                                <?php
                                $result = mysqli_query($conn, "SELECT emp_name, emp_id, emp_email FROM tb_employee");
                                if ($result) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo '<option value="' . htmlspecialchars($row['emp_id']) . '">' . htmlspecialchars($row['emp_email']) . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="priority">Priority:</label>
                            <select class="form-select" id="priority" name="priority" required>
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="category">Category:</label>
                            <select class="form-select" id="category" name="category" required>
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
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <span id="submitBtnSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                <span id="submitBtnText">Create</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.js"></script>
    <script src="js/new_ticket.js"></script>
    <script>
        document.getElementById('newTicketForm').addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent default form submission

            var form = e.target;
            var formData = new FormData(form);
            var submitBtn = document.getElementById('submitBtn');
            var spinner = document.getElementById('submitBtnSpinner');
            var btnText = document.getElementById('submitBtnText');
            // Show spinner and disable button
            spinner.classList.remove('d-none');
            btnText.textContent = 'Creating...';
            submitBtn.disabled = true;

            fetch(form.action, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    // Find the alert placeholder
                    var alertPlaceholder = document.querySelector('.alert-danger');
                    if (!alertPlaceholder) {
                        // If no alert is visible, create one
                        alertPlaceholder = document.createElement('div');
                        alertPlaceholder.className = 'alert alert-dismissible fade show position-absolute top-0 start-50 translate-middle-x mt-3 shadow';
                        alertPlaceholder.style.zIndex = '1050';
                        alertPlaceholder.style.minWidth = '300px';
                        alertPlaceholder.style.maxWidth = '500px';
                        alertPlaceholder.setAttribute('role', 'alert');

                        // Find a container to append the new alert to
                        var container = document.querySelector('.container-fluid.p-0');
                        if (container) {
                            container.insertBefore(alertPlaceholder, container.firstChild);
                        }
                    }

                    // Update alert content and class
                    alertPlaceholder.innerHTML = data.message + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';

                    if (data.success) {
                        alertPlaceholder.classList.remove('alert-danger');
                        alertPlaceholder.classList.add('alert-success');
                        form.reset(); // Clear the form on success
                        var modal = bootstrap.Modal.getInstance(document.getElementById('newTicketModal'));
                        modal.hide();
                        setTimeout(function() {
                            location.reload();
                        }, 1500); // 1.5 seconds delay before reload
                    } else {
                        alertPlaceholder.classList.remove('alert-success');
                        alertPlaceholder.classList.add('alert-danger');
                    }
                    // Hide spinner and enable button
                    spinner.classList.add('d-none');
                    btnText.textContent = 'Submit';
                    submitBtn.disabled = false;
                })
                .catch(error => {
                    console.error('Error:', error);
                    // You can also show an error message to the user here
                    // Hide spinner and enable button
                    spinner.classList.add('d-none');
                    btnText.textContent = 'Submit';
                    submitBtn.disabled = false;
                });
        });
    </script>
    <!-- Modal for displaying messages -->
    <div class="modal fade" id="popupMessage" tabindex="-1" aria-labelledby="popupMessageLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="popupMessageLabel">Notification</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Ticket Updated Successfully....
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="popupCloseBtn">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var closeBtn = document.getElementById("popupCloseBtn");
            if (closeBtn) {
                closeBtn.addEventListener("click", function() {
                    location.reload();
                });
            }
        });
    </script>
</body>

</html>