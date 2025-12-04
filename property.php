<?php
require "includes/db_connect.php";
session_start();

if (!isset($_GET['id'])) {
    die("Property not found.");
}

$property_id = intval($_GET['id']);

// -----------------------------
// FETCH PROPERTY
// -----------------------------
$stmt = $conn->prepare("
    SELECT p.*, u.name AS owner_name
    FROM properties p
    LEFT JOIN users u ON p.owner_id = u.id
    WHERE p.id = ?
");
$stmt->bind_param("i", $property_id);
$stmt->execute();
$property = $stmt->get_result()->fetch_assoc();

if (!$property) {
    die("Property not found.");
}

// -----------------------------
// FETCH EXISTING BOOKINGS
// -----------------------------
$bookStmt = $conn->prepare("
    SELECT start_date, end_date
    FROM bookings
    WHERE property_id = ?
    AND status IN ('pending', 'confirmed')
");
$bookStmt->bind_param("i", $property_id);
$bookStmt->execute();
$bookingsResult = $bookStmt->get_result();

$booked_ranges = [];
while ($b = $bookingsResult->fetch_assoc()) {
    $booked_ranges[] = [
        'start' => $b['start_date'],
        'end'   => $b['end_date']
    ];
}

// -----------------------------
// READ BOOKING ERROR (FROM PHP)
// -----------------------------
$booking_error = "";
$open_booking  = false;

if (isset($_SESSION['booking_error'])) {
    $booking_error = $_SESSION['booking_error'];
    unset($_SESSION['booking_error']);
}

if (isset($_GET['open_booking']) && $_GET['open_booking'] == '1') {
    $open_booking = true;
}

// -----------------------------
// PAGE HEADER
// -----------------------------
$page_title = $property['title'];
require "includes/header.php";
?>

<script>
    // Expose booked ranges to app.js
    window.bookedRanges = <?php echo json_encode($booked_ranges); ?>;
</script>

<!-- HERO IMAGE -->
<div class="property-hero">
    <img src="<?php echo $property['main_image']; ?>" alt="Property Image">
</div>

<!-- PROPERTY CONTENT -->
<div class="property-container">

    <!-- Title + Favorite -->
    <div class="property-header">
        <div>
            <h1><?php echo $property['title']; ?></h1>
            <p class="location-text">
                <?php echo $property['city']; ?> · <?php echo ucfirst($property['type']); ?>
            </p>
        </div>

        <!-- Favorite Button -->
        <div class="property-heart"
             data-id="<?php echo $property_id; ?>"
             data-favorited="<?php echo isset($_SESSION['user_id']) ? 'ok' : 'no'; ?>">
            ♡
        </div>
    </div>

    <!-- QUICK FACTS -->
    <div class="property-facts">
        <span>👥 <?php echo $property['max_guests']; ?> guests</span>
        <span>🏘 Type: <?php echo ucfirst($property['type']); ?></span>
        <span>💲 $<?php echo number_format($property['price'], 2); ?> / night</span>
    </div>

    <hr class="divider">

    <!-- DESCRIPTION -->
    <div class="section">
        <h2>About this place</h2>
        <p><?php echo nl2br($property['description']); ?></p>
    </div>

    <hr class="divider">

    <!-- AMENITIES -->
    <div class="section">
        <h2>Amenities</h2>
        <ul class="amenities-list">
            <?php if ($property['has_wifi'])         echo "<li>📶 Wi-Fi</li>"; ?>
            <?php if ($property['has_parking'])      echo "<li>🅿 Parking</li>"; ?>
            <?php if ($property['has_ac'])           echo "<li>❄ Air Conditioning</li>"; ?>
            <?php if ($property['has_pool'])         echo "<li>🏊 Pool</li>"; ?>
            <?php if ($property['has_kitchen'])      echo "<li>🍽 Kitchen</li>"; ?>
            <?php if ($property['has_tv'])           echo "<li>📺 TV</li>"; ?>
            <?php if ($property['has_heating'])      echo "<li>🔥 Heating</li>"; ?>
            <?php if ($property['has_pet_friendly']) echo "<li>🐶 Pet Friendly</li>"; ?>
            <?php if ($property['has_fireplace'])    echo "<li>🪵 Fireplace</li>"; ?>
        </ul>
    </div>

    <hr class="divider">

    <!-- BOOKING BOX -->
    <div class="booking-box">
        <h2>$<?php echo number_format($property['price'], 2); ?> <span>/ night</span></h2>

        <button class="book-now-btn" id="openBookingPopup">
            Book Now
        </button>
    </div>
</div>

<!-- BOOKING POPUP -->
<div id="bookingModal" class="booking-modal">
    <div class="booking-content">
        <h2>Book This Property</h2>

        <!-- ERROR MESSAGE -->
        <div id="bookingError"
             style="color: red; margin-bottom: 10px; <?php echo empty($booking_error) ? 'display:none;' : 'display:block;'; ?>">
            <?php echo htmlspecialchars($booking_error); ?>
        </div>

        <!-- IMPORTANT: FIXED ACTION PATH -->
        <form action="backend/book_property.php" method="POST">
            <input type="hidden" name="property_id" value="<?php echo $property_id; ?>">

            <label>Check-in Date</label>
            <input type="date" id="checkIn" name="check_in" required>

            <label>Check-out Date</label>
            <input type="date" id="checkOut" name="check_out" required>

            <button type="submit" class="confirm-book-btn">
                Confirm Booking
            </button>

            <button type="button" class="close-popup" id="closeBookingPopup">
                Cancel
            </button>
        </form>
    </div>
</div>

<?php if ($open_booking): ?>
<script>
document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("bookingModal").style.display = "flex";
});
</script>
<?php endif; ?>

<?php require "includes/footer.php"; ?>
