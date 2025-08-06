<?php

session_start();
// Check if user is logged in
include 'configs/db_connection.php';

// Include the OTP email function
include_once 'email/send_otp_email.php';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $resetEmail = $_POST['forgot_emp_email'] ?? '';

        // Check if resetEmail exists in the database
        $stmt = $conn->prepare("SELECT ur_email FROM tb_user WHERE ur_email = ?");
        if (!$stmt) {
            throw new Exception("Database error: " . $conn->error);
        }
        $stmt->bind_param("s", $resetEmail);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Generate OTP
            $otp = rand(100000, 999999);
            $_SESSION['reset_email'] = $resetEmail;
            $_SESSION['reset_otp'] = $otp;
            $_SESSION['reset_otp_expires'] = time() + 600; // 10 minutes
            // Send OTP to email
            $emailSent = sendOTPEmail($resetEmail, $otp);
            
            if (!$emailSent) {
                $_SESSION['error'] = "Failed to send OTP email. Please try again.";
                header("Location: forgot_password.php");
                exit();
            }

            $_SESSION['success'] = "An OTP has been sent to your email address.";
            header("Location: enter_otp.php");
            exit();
        } else {
            $_SESSION['error'] = "No account found with that email address.";
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