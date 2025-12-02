<?php
require "includes/auth_check.php";
require "includes/db_connect.php";

$id = $_GET["id"];

// Load current property
$stmt = $conn->prepare("SELECT * FROM properties WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$property = $stmt->get_result()->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = $_POST["title"];
    $description = $_POST["description"];
    $price = $_POST["price"];
    $city = $_POST["city"];
    $address = $_POST["address"];
    $type = $_POST["type"];
    $max_guests = $_POST["max_guests"];
    $image_url = $_POST["image_url"];

    $stmt = $conn->prepare("UPDATE properties 
        SET title=?, description=?, price=?, city=?, address=?, type=?, max_guests=?, main_image=?
        WHERE id=?");

    $stmt->bind_param("ssdsssisi", $title, $description, $price, $city, $address, $type, $max_guests, $image_url, $id);
    $stmt->execute();

    header("Location: dashboard.php");
    exit();
}
?>
