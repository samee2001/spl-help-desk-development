<form action="register.php" method="POST">
    <h2>Register</h2>

    <div class="inputBox">
        <input type="text" name="reg_name" required>
        <span>Name</span>
        <i></i>
    </div>

    <div class="inputBox">
        <input type="email" name="reg_email" required>
        <span>E-mail</span>
        <i></i>
    </div>

    <div class="inputBox">
        <input type="password" name="reg_password" required>
        <span>Password</span>
        <i></i>
    </div>

    <div class="inputBox">
        <input type="password" name="reg_confirm_password" required>
        <span>Confirm Password</span>
        <i></i>
    </div>

    <div class="links">
        <!-- You can add links here like "Forgot Password" or "Login" -->
    </div>

    <input type="submit" id="submit" value="Submit" name="register_btn">
    <p style="color: white; margin-top: 20px;">Already have an account? <a href="login.php" style="text-decoration: none; color: #6666ff; ">Login</a></p>
</form>