<?php
require "includes/auth_check.php";
require "includes/db_connect.php";
require "includes/header.php";

$user_id = $_SESSION['user_id'];

$result = $conn->query("
    SELECT b.*, 
           p.title, 
           p.city, 
           p.main_image,
           p.price
    FROM bookings b
    JOIN properties p ON b.property_id = p.id
    WHERE b.user_id = $user_id
    ORDER BY b.created_at DESC
");
?>

<h1>My Bookings</h1>

<div class="cards">

<?php while ($row = $result->fetch_assoc()): ?>
    <div class="card">
        <img src="<?php echo $row['main_image']; ?>" 
             style="width:100%; height:200px; object-fit:cover;">

        <h3><?php echo $row['title']; ?></h3>
        <p><?php echo $row['city']; ?></p>

        <p><strong>Check-in:</strong> <?php echo $row['start_date']; ?></p>
        <p><strong>Check-out:</strong> <?php echo $row['end_date']; ?></p>
        <p><strong>Total price:</strong> $<?php echo $row['total_price']; ?></p>
        <p><strong>Status:</strong> <?php echo ucfirst($row['status']); ?></p>
    </div>
<?php endwhile; ?>

</div>
