<form action="change_password.php" method="POST">
    <h2>Change Password</h2>
    <div class="inputBox">
        <br>
        <br>
        <label style="color: white;">New Password</label>
        <input type="password" name="forgot_new_password" required style="margin-bottom: 30px;">
        <br>
        <i></i>
        <label style="color: white;">Confirm new Password</label>
        <input type="password" name="forgot_confirm_password" required>
        <i></i>
    </div>
    <input type="submit" id="submit" value="Change">
    <a href="login.php" style="color: white; text-decoration: none; margin-top: 10px;">Back to Login</a>
</form>