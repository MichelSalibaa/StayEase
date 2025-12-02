<?php
require "includes/auth_check.php"; // session + logged-in check
require "includes/db_connect.php";

$user_id = $_SESSION['user_id'];

// Get form inputs
$current_password = $_POST['current_password'];
$new_password     = $_POST['new_password'];
$confirm_password = $_POST['confirm_password'];

// 1️⃣ Validate fields
if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
    die("All password fields are required.");
}

if ($new_password !== $confirm_password) {
    echo "<script>
            alert('New passwords do not match.');
            window.location.href = 'profile.php';
          </script>";
    exit;
}

// 2️⃣ Fetch user's current password hash
$stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("User not found.");
}

$stored_hash = $user['password'];

// 3️⃣ Verify current password
if (!password_verify($current_password, $stored_hash)) {
    echo "<script>
            alert('Incorrect current password.');
            window.location.href = 'profile.php';
          </script>";
    exit;
}

// 4️⃣ Hash the new password
$new_hash = password_hash($new_password, PASSWORD_DEFAULT);

// 5️⃣ Update the password in the database
$update = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
$update->bind_param("si", $new_hash, $user_id);

if ($update->execute()) {
    echo "<script>
            alert('Password updated successfully!');
            window.location.href = 'profile.php';
          </script>";
} 
else {
    echo "Error updating password: " . $conn->error;
}
