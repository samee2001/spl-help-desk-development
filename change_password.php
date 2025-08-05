<?php
session_start();
include 'configs/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newPassword = $_POST['forgot_new_password'] ?? ''; 
    $confirmNewPassword = $_POST['forgot_confirm_password'] ?? '';
    
    // Validate that both passwords are provided
    if (empty($newPassword) || empty($confirmNewPassword)) {
        $_SESSION['error'] = "Both password fields are required.";
        header("Location: change_password.php");
        exit();
    }
    
    // Check if passwords match
    if ($confirmNewPassword == $newPassword) {
        // Validate password strength (optional)
        if (strlen($newPassword) < 6) {
            $_SESSION['error'] = "Password must be at least 6 characters long.";
            header("Location: change_password.php");
            exit();
        }
        
        // Hash the new password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        // Get the email from session (assuming it was set during password reset process)
        $email = $_SESSION['reset_email'] ?? ''; //in here the data is not assigned to the $email.

        if (empty($email)) {
            $_SESSION['error'] = "Email not found. Please try the password reset process again.";
            header("Location: forgot_password.php");
            exit();
        }
        
        // Update the password in the database
        $updateQuery = "UPDATE tb_user SET ur_password = ? WHERE ur_email = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("ss", $hashedPassword, $email);
        
        if ($updateStmt->execute()) {
            if ($updateStmt->affected_rows > 0) {
                $_SESSION['success'] = "Password updated successfully! You can now login with your new password.";
                // Clear the reset email from session
                unset($_SESSION['reset_email']);
                header("Location: login.php");
                exit();
            } else {
                $_SESSION['error'] = "Email not found in our records.";
                header("Location: change_password.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "Database error occurred. Please try again.";
            header("Location: change_password.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Passwords do not match. Please try again.";
        header("Location: change_password.php");
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="css/change_password.css">
</head>

<body>
    <div class="box">
        <span class="borderLine"></span>
        <?php

        if (isset($_SESSION['success'])) {
            echo '<div id="success-message" style="position: absolute; width: 100%; top: 70px; z-index: 3; color: rgb(145, 255, 198); text-align: center; padding: 0 40px; box-sizing: border-box;">' . htmlspecialchars($_SESSION['success']) .
                '<button type="button" class="close-btn" onclick="closeMessage(\'success-message\')">&times;</button></div>';
            unset($_SESSION['success']);
        }

        if (isset($_SESSION['error'])) {
            echo '<div id="error-message" style="position: absolute; width: 100%; top: 70px; z-index: 3; color: #ff2770; text-align: center; padding: 0 40px; box-sizing: border-box;">' . htmlspecialchars($_SESSION['error']) .
                '<button type="button" class="close-btn" onclick="closeMessage(\'error-message\')">&times;</button></div>';
            unset($_SESSION['error']);
        }
        // inlude the form component for the forgot password page
        include 'components/change_password_form.php';
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