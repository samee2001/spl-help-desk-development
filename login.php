<?php
// Start session to manage user login state
session_start();

include 'configs/db_connection.php';

$error = ''; // Initialize error variable

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';


    if (empty($email) || empty($password)) {
        $error = "Email and password are required.";
    } else {
        // Use prepared statements to prevent SQL injection
        $stmt = $conn->prepare("SELECT ur_password, ur_email FROM tb_user WHERE ur_email = ?");
        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();

                // Verify hashed password
                if (password_verify($password, $user['ur_password'])) {
                    // Password is correct, create session variables
                    session_regenerate_id(true); // Prevent session fixation
                    $_SESSION['loggedin'] = true;
                    $_SESSION['user_email'] = $email;
                    $_SESSION['session_id'] = session_id(); // Store session ID
                    // Redirect to the main application page
                    header("Location: index.php");
                    exit();
                }
            }
            // Generic error message for security to prevent user enumeration
            $error = "Invalid email or password.";
        } else {
            $error = "Database error. Please try again later.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/login.css">
</head>

<body>
    <div class="box">
        <span class="borderLine"></span>
        <?php
        // Display error message if login fails
        if (!empty($error)) {
            echo '<div style="position: absolute; width: 100%; top: 95px; z-index: 3; color: #ff2770; text-align: center; padding: 0 40px; box-sizing: border-box;">' . htmlspecialchars($error) . '</div>';
        }
        include 'components/login_form.php';


        ?>
    </div>
</body>

</html>