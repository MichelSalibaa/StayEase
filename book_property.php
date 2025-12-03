<?php
session_start();
require "includes/db_connect.php";

// (optional while debugging – you can remove later)
ini_set('display_errors', 1);
error_reporting(E_ALL);

/**
 * Helper: send user back to property page with an error message
 */
function back_with_error($property_id, $message) {
    $_SESSION['booking_error'] = $message;
    header("Location: property.php?id=" . $property_id . "&open_booking=1");
    exit;
}

// 1) Must be logged in
if (!isset($_SESSION['user_id'])) {
    $pid = isset($_POST['property_id']) ? (int)$_POST['property_id'] : 0;
    back_with_error($pid, "You must be logged in to book a property.");
}

// 2) Must be POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

$user_id     = $_SESSION['user_id'];
$property_id = isset($_POST['property_id']) ? (int)$_POST['property_id'] : 0;
$start_date  = $_POST['check_in']  ?? '';
$end_date    = $_POST['check_out'] ?? '';

if (!$property_id) {
    header("Location: index.php");
    exit;
}

// 3) Basic date validation
if (empty($start_date) || empty($end_date)) {
    back_with_error($property_id, "Please select valid dates.");
}

if ($start_date >= $end_date) {
    back_with_error($property_id, "Check-out date must be after check-in date.");
}

// 4) Check overlapping bookings
$overlapStmt = $conn->prepare("
    SELECT COUNT(*) AS cnt
    FROM bookings
    WHERE property_id = ?
      AND status IN ('pending', 'confirmed')
      AND NOT (end_date <= ? OR start_date >= ?)
");
$overlapStmt->bind_param("iss", $property_id, $start_date, $end_date);
$overlapStmt->execute();
$overlapResult = $overlapStmt->get_result()->fetch_assoc();

if ($overlapResult['cnt'] > 0) {
    back_with_error($property_id, "These dates are already booked. Please choose other dates.");
}

// 5) Get price per night
$priceStmt = $conn->prepare("SELECT price FROM properties WHERE id = ?");
$priceStmt->bind_param("i", $property_id);
$priceStmt->execute();
$priceRow = $priceStmt->get_result()->fetch_assoc();

if (!$priceRow) {
    back_with_error($property_id, "Property not found.");
}

$pricePerNight = (float) $priceRow['price'];

// 6) Calculate total price
$nights = (strtotime($end_date) - strtotime($start_date)) / 86400;
if ($nights <= 0) {
    back_with_error($property_id, "Check-out date must be after check-in date.");
}

$total_price = $nights * $pricePerNight;

// 7) Insert booking
$status = "pending";

$stmt = $conn->prepare("
    INSERT INTO bookings (property_id, user_id, start_date, end_date, total_price, status)
    VALUES (?, ?, ?, ?, ?, ?)
");
$stmt->bind_param(
    "iissds",
    $property_id,
    $user_id,
    $start_date,
    $end_date,
    $total_price,
    $status
);
$stmt->execute();

// 8) Redirect to My Bookings with success message
$_SESSION['booking_success'] = "Your booking has been created! Total price: $" . number_format($total_price, 2);
header("Location: my_bookings.php");
exit;
