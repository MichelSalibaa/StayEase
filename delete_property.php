<?php
require "includes/auth_check.php";
require "includes/db_connect.php";

$id = $_GET["id"];

$stmt = $conn->prepare("DELETE FROM properties WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: dashboard.php");
exit();
?>
