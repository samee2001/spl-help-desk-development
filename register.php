<?php
session_start();

// Include the database connection
include 'configs/db_connection.php';

if (isset($_POST['register_btn'])) {
    // Get form data
    $name = $_POST['reg_name'];
    $email = $_POST['reg_email'];
    $password = $_POST['reg_password'];
    $confirm_password = $_POST['reg_confirm_password'];

    // Check if the email is in the required format (xxx@sadaharitha.com)
    if (!preg_match('/^[a-zA-Z0-9._%+-]+@sadaharitha\.com$/', $email)) {
        $_SESSION['error'] = "Invalid email format. Please use a valid email like xxx@sadaharitha.com.";
        header("Location: register.php"); // Redirect back to the registration page
        exit();
    }

    // Step 2: Check if the email exists in the tb_employee table
    $query = "SELECT emp_id FROM tb_employee WHERE emp_email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Step 3: If email doesn't exist in tb_employee, show an error
    if ($result->num_rows == 0) {
        $_SESSION['error'] = "You are not an employee, Contact the Admin..🙂";
        header("Location: register.php"); // Redirect back to the registration page
        exit();
    }

    // Step 4: Get the corresponding employee ID from tb_employee
    $employee = $result->fetch_assoc();
    $employee_id = $employee['emp_id'];

    // Step 5: Check if the email already exists in tb_user table
    $query_user = "SELECT * FROM tb_user WHERE ur_email = ?";
    $stmt_user = $conn->prepare($query_user);
    $stmt_user->bind_param("s", $email);
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();


    //If email already exists, show an error
    if ($result_user->num_rows > 0) {
        $_SESSION['error'] = "The email address is already registered. So, Login";
        header("Location: register.php"); // Redirect back to the registration page
        exit();
    }

    // Check if passwords match
    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match.";
        header("Location: register.php");
        exit();
    }

    // Hash the password before storing it
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert the new user into the database
    $insert_query = "INSERT INTO tb_user (ur_name, ur_email, ur_password, emp_id) VALUES (?, ?, ?,?)";
    $insert_stmt = $conn->prepare($insert_query);
    $insert_stmt->bind_param("sssi", $name, $email, $hashed_password, $employee_id);

    if ($insert_stmt->execute()) {
        $_SESSION['success'] = "Registration successful! Please log in.";
        //header("Location: login.php"); // Redirect to login page after successful registration
        //exit();
    } else {
        $_SESSION['error'] = "Error: " . $insert_stmt->error;
        header("Location: register.php"); // Redirect back to registration page
        exit();
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/register.css">
</head>

<body>

    <div class="box">
        <span class="borderLine"></span>
        <?php
        if (isset($_SESSION['error'])) {
            echo '<div id="error-message" style="position: absolute; width: 100%; top: 95px; z-index: 3; color: #ff2770; text-align: center; padding: 0 40px; box-sizing: border-box;">' . htmlspecialchars($_SESSION['error']) .
                '<button type="button" class="close-btn" onclick="closeMessage(\'error-message\')">&times;</button></div>';
            unset($_SESSION['error']);
        }

        if (isset($_SESSION['success'])) {
            echo '<div id="success-message" style="position: absolute; width: 100%; top: 95px; z-index: 3; color: rgb(145, 255, 198); text-align: center; padding: 0 40px; box-sizing: border-box;">' . htmlspecialchars($_SESSION['success']) .
                '<button type="button" class="close-btn" onclick="closeMessage(\'success-message\')">&times;</button></div>';
            unset($_SESSION['success']);
        }
        // Include the registration form component
        include 'components/register_form.php';
        ?>
    </div>
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