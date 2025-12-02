<?php
session_start();
require "includes/header.php";
?>

<div class="register-container">
    <div class="register-box">
        <h2>Create Account</h2>
        <p class="subtitle">Join RentEase to book and list properties</p>

        <form action="register_process.php" method="POST">

            <label>Name</label>
            <input type="text" name="name" required>

            <label>Email</label>
            <input type="email" name="email" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <label>Confirm Password</label>
            <input type="password" name="confirm_password" required>

            <button type="submit" class="register-btn-main">Create Account</button>
        </form>

        <div class="login-link">
            Already have an account? <a href="login.php">Log in</a>
        </div>
    </div>
</div>

<?php require "includes/footer.php"; ?>
