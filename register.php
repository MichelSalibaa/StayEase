<?php
session_start();
require "includes/db_connect.php";  // we need DB here now

$errors = [];

// Handle form submit
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name             = trim($_POST["name"]);
    $email            = trim($_POST["email"]);
    $password         = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Basic required fields check
    if ($name === "" || $email === "" || $password === "" || $confirm_password === "") {
        $errors[] = "Please fill in all fields.";
    }

    // Password rule: at least 8 chars, 1 uppercase, 1 number
    $pattern = "/^(?=.*[A-Z])(?=.*\d).{8,}$/";
    if (!preg_match($pattern, $password)) {
        $errors[] = "Password must be at least 8 characters and include 1 uppercase letter and 1 number.";
    }

    // Confirm password
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    // Check if email already exists (optional but useful)
    if ($email !== "") {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $errors[] = "An account with this email already exists.";
        }
        $stmt->close();
    }

    // If everything is OK → insert user
    if (empty($errors)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("
            INSERT INTO users (name, email, password, created_at)
            VALUES (?, ?, ?, NOW())
        ");
        $stmt->bind_param("sss", $name, $email, $hash);
        $stmt->execute();
        $stmt->close();

        // Redirect to login with a success flag
        header("Location: login.php?registered=1");
        exit();
    }
}

// Page title (optional)
$page_title = "Register";
require "includes/header.php";
?>

<div class="register-container">
    <div class="register-box">
        <h2>Create Account</h2>
        <p class="subtitle">Join RentEase to book and list properties</p>

        <?php if (!empty($errors)): ?>
            <div class="error-msg" style="margin-bottom:15px;">
                <?php foreach ($errors as $err): ?>
                    <p><?php echo htmlspecialchars($err); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="register.php" method="POST">
            <label>Name</label>
            <input type="text" name="name"
                   value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>"
                   required>

            <label>Email</label>
            <input type="email" name="email"
                   value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                   required>

            <label>Password</label>
            <input type="password"
                   name="password"
                   required
                   pattern="^(?=.*[A-Z])(?=.*\d).{8,}$"
                   title="Must be at least 8 characters and include 1 uppercase letter and 1 number.">

            <small style="display:block; text-align:left; font-size:12px; color:#777; margin-top:4px; margin-bottom:12px;">
                Must contain at least 8 characters, 1 uppercase letter and 1 number.
            </small>

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
