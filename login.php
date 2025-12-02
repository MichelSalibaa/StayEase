<?php
require "includes/header.php";
?>

<link rel="stylesheet" href="assets/css/login.css">

<div class="login-container">
    <div class="login-box">
        
        <h1>Welcome Back</h1>
        <p class="subtitle">Log in to continue</p>

        <?php if (isset($_GET['error'])): ?>
            <div class="error-msg"><?php echo $_GET['error']; ?></div>
        <?php endif; ?>

        <form action="process_login.php" method="POST">

            <label>Email</label>
            <input type="email" name="email" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <button type="submit" class="login-btn">Log In</button>
        </form>

        <p class="signup-text">
            Don’t have an account?
            <a href="register.php">Create one</a>
        </p>
    </div>
</div>

<?php require "includes/footer.php"; ?>
