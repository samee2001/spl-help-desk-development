<form action="login.php" method="POST">
    <h2>Log in</h2>
    <div class="inputBox">
        <input type="email" name="email" required>
        <span>E-mail</span>
        <i></i>
    </div>
    <div class="inputBox">
        <input type="password" name="password" required>
        <span>Password</span>
        <i></i>
    </div>
    <div class="links">
        <a href="#">Forgot Password</a>
        <!-- <a href="register.php">Register</a> -->
    </div>
    <input type="submit" id="submit" value="Login">
    <p style="color: white; margin-top: 20px;">Don't Have have an account? <a href="register.php" style="text-decoration: none; color: #6666ff">Register</a></p>

</form>