<?php
require __DIR__ . "/../includes/auth_check.php";   // ensures user is logged in + starts session
require __DIR__ . "/../includes/db_connect.php";

$user_id = $_SESSION['user_id'];

// Get form data
$name  = trim($_POST['name']);
$email = trim($_POST['email']);
$phone = trim($_POST['phone']);
$bio   = trim($_POST['bio']);

// Validate required fields
if (empty($name) || empty($email)) {
    die("Name and email are required.");
}

// Update query
$stmt = $conn->prepare("
    UPDATE users 
    SET name = ?, email = ?, phone = ?, bio = ?
    WHERE id = ?
");

$stmt->bind_param("ssssi", $name, $email, $phone, $bio, $user_id);

if ($stmt->execute()) {
    // Update session name so navbar updates automatically
    $_SESSION['user_name'] = $name;

    // Success message
    echo "
        <script>
        alert('Your profile has been updated successfully!');
        window.location.href = '../profile.php';
        </script>
    ";
} else {
    echo "Error updating profile: " . $conn->error;
}
