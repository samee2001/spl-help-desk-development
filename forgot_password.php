<?php

session_start();
// Check if user is logged in
include 'configs/db_connection.php';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $resetEmail = $_POST['forgot_emp_email'] ?? '';
        $resetPassword = $_POST['forgot_new_password'] ?? '';
        $resetConfirmPassword = $_POST['forgot_confirm_password'] ?? '';

        // Check if resetEmail exists in the database
        $stmt = $conn->prepare("SELECT ur_email FROM tb_user WHERE ur_email = ?");
        if (!$stmt) {
            throw new Exception("Database error: " . $conn->error);
        }
        $stmt->bind_param("s", $resetEmail);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $_SESSION['reset_email'] = $resetEmail; // Set the reset email in session for the next step

            // Check if passwords match
            if ($resetPassword === $resetConfirmPassword) {
                // Validate password strength (optional)
                if (strlen($resetPassword) <= 6) {
                    $_SESSION['error'] = "Password must be at least 6 characters long.";
                    header("Location: forgot_password.php");
                    exit();
                }

                // Hash the new password
                $hashedPassword = password_hash($resetPassword, PASSWORD_DEFAULT);

                // Get the email from session (assuming it was set during password reset process)
                // $email = $_SESSION['reset_email'] ?? ''; //in here the data is not assigned to the $email.

                // Update the password in the database
                $updateQuery = "UPDATE tb_user SET ur_password = ? WHERE ur_email = ?";
                $updateStmt = $conn->prepare($updateQuery);
                $updateStmt->bind_param("ss", $hashedPassword, $resetEmail);

                if ($updateStmt->execute()) {
                    if ($updateStmt->affected_rows > 0) {
                        $_SESSION['success'] = "Password updated successfully! You can now login with your new password.";
                        // Clear the reset email from session
                        unset($_SESSION['reset_email']);
                        header("Location: login.php");
                        exit();
                    } else {
                        $_SESSION['error'] = "Email not found in our records.";
                        header("Location: forgot_password.php");
                        exit();
                    }
                } else {
                    $_SESSION['error'] = "Database error occurred. Please try again.";
                    header("Location: forgot_password.php");
                    exit();
                }
            }
        } else {
            // If email is not found in the database
            $_SESSION['error'] = "No account found with that email. Please try again.";
            header("Location: forgot_password.php");
            exit();
        }
    }
} catch (Exception $e) {
    $_SESSION['error'] = "An error occurred: " . $e->getMessage();
    header("Location: forgot_password.php");
    exit();
}
?>








<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="css/forgot_password.css">
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
        include 'components/forgot_password_form.php';
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