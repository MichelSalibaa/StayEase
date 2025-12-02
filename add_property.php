<?php
require "includes/auth_check.php";
require "includes/db_connect.php";

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $owner_id = $_SESSION["user_id"];
    $title = $_POST["title"];
    $description = $_POST["description"];
    $price = $_POST["price"];
    $city = $_POST["city"];
    $address = $_POST["address"];
    $type = $_POST["type"];
    $max_guests = $_POST["max_guests"];
    $image_url = $_POST["image_url"]; // later you can add upload support

    $stmt = $conn->prepare("INSERT INTO properties 
    (owner_id, title, description, price, city, address, type, max_guests, main_image)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("issdsssiss", $owner_id, $title, $description, $price, $city, $address, $type, $max_guests, $image_url);
    $stmt->execute();

    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Property</title>
</head>
<body>

<h2>Add New Property</h2>

<form method="POST">
    Title: <input type="text" name="title"><br><br>
    Description:<br> <textarea name="description"></textarea><br><br>
    Price: <input type="number" name="price"><br><br>
    City: <input type="text" name="city"><br><br>
    Address: <input type="text" name="address"><br><br>
    Type: <input type="text" name="type"><br><br>
    Max Guests: <input type="number" name="max_guests"><br><br>
    Image URL: <input type="text" name="image_url"><br><br>
    <button type="submit">Save Property</button>
</form>

</body>
</html>
