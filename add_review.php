<?php
require "includes/auth_check.php";
require "includes/db_connect.php";

$user_id = $_SESSION["user_id"];
$property_id = $_POST["property_id"];
$rating = $_POST["rating"];
$comment = $_POST["comment"];

$stmt = $conn->prepare("INSERT INTO reviews (property_id, user_id, rating, comment)
VALUES (?, ?, ?, ?)");
$stmt->bind_param("iiis", $property_id, $user_id, $rating, $comment);
$stmt->execute();

header("Location: property.php?id=" . $property_id);
exit();
?>
