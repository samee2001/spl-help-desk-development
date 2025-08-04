<?php
session_start();
// Include the database connection
include 'configs/db_connection.php';
include 'email/send_confirmation_email.php';


if (isset($_POST['register_employees'])) {
    // Get the input values
    $emp_name = $_POST['reg_emp_name'];
    $emp_email = $_POST['reg_emp_email'];
    $emp_designation = $_POST['reg_emp_des'];
    $emp_organization = $_POST['reg_emp_org'];

    // Check if the email is in the required format (xxx@sadaharitha.com)
    if (!preg_match('/^[a-zA-Z0-9._%+-]+@gmail\.com$/', $emp_email)) {
        $_SESSION['error'] = "Please use a valid email like xxx@sadaharitha.com.";
        header("Location: employee_registration.php"); // Redirect back to the registration page
        exit();
    }

    // Step 2: Check if the email exists in the tb_employee table
    $query_register = "SELECT emp_email FROM tb_employee WHERE emp_email = ?";
    $stmt = $conn->prepare($query_register);
    $stmt->bind_param("s", $emp_email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the employee already exists in tb_employee table
    if ($result->num_rows > 0) {
        $_SESSION['error'] = "This Employee is Already Added..🤔";
        header("Location: employee_registration.php"); // Redirect back to the registration page
        exit();
    }
    date_default_timezone_set("Asia/Colombo");
    $created_date = date("Y-m-d H:i:s");
    // Step 3: Insert the new user into the tb_employee table
    $insert_query_register = "INSERT INTO tb_employee (emp_name, emp_email, emp_designation, org_id, created_at) VALUES (?, ?,?,?,?)";
    $insert_stmt = $conn->prepare($insert_query_register);
    $insert_stmt->bind_param("sssis", $emp_name, $emp_email, $emp_designation, $emp_organization, $created_date);

    if ($insert_stmt->execute()) {
        // Send confirmation email
        $subject = "Welcome to Sadaharitha - Employee Registration Confirmation";
        
        // Get organization name for the email
        $org_query = "SELECT org_name FROM tb_organization WHERE org_id = ?";
        $org_stmt = $conn->prepare($org_query);
        $org_stmt->bind_param("i", $emp_organization);
        $org_stmt->execute();
        $org_result = $org_stmt->get_result();
        $org_row = $org_result->fetch_assoc();
        $emp_organization_name = $org_row['org_name'];
        
        // Start output buffering to capture the HTML template
        ob_start();
        include 'email/confirmation_mail_body.php';
        $message = ob_get_clean();
        
        $emailSent = sendConfirmationEmail($emp_email, $subject, $message);
        
        if ($emailSent) {
            $_SESSION['success'] = "Registration successful! Employee added..🙂";
        } else {
            $_SESSION['success'] = "Registration successful! Employee added, but email notification failed.";
        }

        // Reset the form and display success message
        echo "<script>document.getElementById('register-form').reset();</script>";
        header("Location: employee_registration.php"); // Redirect to registration page after successful registration
        exit();
    } else {
        // If insertion fails, display an error
        $_SESSION['error'] = "Error: " . $insert_stmt->error;
        header("Location: employee_registration.php"); // Redirect back to registration page
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Employees</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/add_emp_form.css">
</head>

<body>
    <?php include 'components/nav_bar.php'; ?>
    <div class="box">
        <span class="borderLine"></span>
        <?php

        if (isset($_SESSION['error'])) {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert" style="position: absolute; top: 95px; z-index: 3; width: 30%; text-align: center; margin-bottom: 10px;">
            ' . htmlspecialchars($_SESSION['error']) . '
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
            unset($_SESSION['error']);
        }

        if (isset($_SESSION['success'])) {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert" style="position: absolute; top: 95px; z-index: 3; width: 30%; text-align: center;">
            ' . htmlspecialchars($_SESSION['success']) . '
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
            unset($_SESSION['success']);
        }
        include 'components/add_emp_form.php';
        ?>
    </div>
    <?php include 'components/footer_bar.php'; ?>
    <script>
        function closeMessage(messageId) {
            var messageElement = document.getElementById(messageId);
            if (messageElement) {
                messageElement.style.display = 'none';
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>