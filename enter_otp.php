<?php
session_start();

// if (!isset($_SESSION['reset_email'], $_SESSION['reset_otp'], $_SESSION['reset_otp_expires'])) {
//     // No OTP process started
//     header("Location: forgot_password.php");
//     exit();
// }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userOtp = $_POST['otp'] ?? '';
    if (
        isset($_SESSION['reset_otp'], $_SESSION['reset_otp_expires']) &&
        time() < $_SESSION['reset_otp_expires'] &&
        $userOtp == $_SESSION['reset_otp']
    ) {
        $_SESSION['otp_verified'] = true;
        // Optionally, unset the OTP so it can't be reused
        unset($_SESSION['reset_otp'], $_SESSION['reset_otp_expires']);
        header("Location: change_password.php");
        exit();
    } else {
        $_SESSION['error'] = "Invalid or expired OTP. Please try again.";
        header("Location: enter_otp.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter OTP</title>
    <link rel="stylesheet" href="css/verify_otp.css">
</head>

<body>
    <div class="box">
        <span class="borderLine"></span>
        <h2 style="color: #fff; text-align: center;">Enter OTP</h2>
        <?php
        if (isset($_SESSION['success'])) {
            echo '<div id="success-message" style=" color: rgb(145, 255, 198); text-align: center; margin-bottom: 16px;">' . htmlspecialchars($_SESSION['success']) . '</div>';
            unset($_SESSION['success']);
        }

        if (isset($_SESSION['error'])) {
            echo '<div id="error-message" style="color: #ff2770; text-align: center; margin-bottom: 16px;">' . htmlspecialchars($_SESSION['error']) . '</div>';
            unset($_SESSION['error']);
        }
        ?>
        <form action="enter_otp.php" method="POST" style="margin-top: 24px;">
            <div class="inputBox">
                <label style="color: white;">Enter the OTP</label>
                <input type="number" name="otp" required pattern="\\d{6}" maxlength="6" autocomplete="one-time-code">
            </div>
            <input type="submit" id="submit" value="Verify OTP">
            <a href="forgot_password.php" style="color: white; text-decoration: none; margin-top: 10px;">Back to Previous Page</a>
        </form>
    </div>
</body>

</html>