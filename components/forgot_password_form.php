<form action="forgot_password.php" method="POST">
    <h2>Reset Password</h2>
    <div class="inputBox">
        <br>
        <br>
        <label style="color: white;">Email</label>
        <input type="email" name="forgot_emp_email" required>
        <!-- <label style="color: white;">New Password</label>
        <input type="password" name="forgot_new_password" required >
        <label style="color: white;">Confirm Password</label>
        <input type="password" name="forgot_confirm_password" required> -->
    </div>
    <input type="submit" id="submit" value="Reset Password">
    <a href="login.php" style="color: white; text-decoration: none; margin-top: 10px;">Back to Login</a>
</form>