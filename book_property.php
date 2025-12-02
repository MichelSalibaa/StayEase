<?php
session_start();
require "includes/db_connect.php";

if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to book a property.");
}

$user_id     = $_SESSION['user_id'];
$property_id = intval($_POST['property_id']);
$start_date  = $_POST['check_in'];
$end_date    = $_POST['check_out'];

// validate dates
if (empty($start_date) || empty($end_date)) {
    die("Please select valid dates.");
}

if ($start_date >= $end_date) {
    die("End date must be after start date.");
}

// OPTIONAL: price calculation
// get price per night
$priceQuery = $conn->query("SELECT price FROM properties WHERE id = $property_id");
$property   = $priceQuery->fetch_assoc();
$pricePerNight = $property['price'];

// number of nights
$nights = (strtotime($end_date) - strtotime($start_date)) / 86400;
$total_price = $nights * $pricePerNight;

// default booking status
$status = "pending";

// insert into bookings table
$stmt = $conn->prepare("
    INSERT INTO bookings (property_id, user_id, start_date, end_date, total_price, status)
    VALUES (?, ?, ?, ?, ?, ?)
");

$stmt->bind_param("iissds", 
    $property_id, 
    $user_id, 
    $start_date, 
    $end_date, 
    $total_price, 
    $status
);

$stmt->execute();

echo "
    <div style='padding:30px; text-align:center; font-family:Arial;'>
        <h2>🎉 Thank you for your reservation!</h2>
        <p>Your booking has been saved successfully.</p>
        <p><strong>Total price:</strong> $$total_price</p>
        <p><strong>Disclaimer:</strong> Payment will be made on check-in day at the property.</p>

        <a href='my_bookings.php' 
           style='display:inline-block;margin-top:20px;padding:12px 20px;background:#1e73be;color:#fff;border-radius:8px;text-decoration:none;'>
           View My Bookings
        </a>
    </div>
";
