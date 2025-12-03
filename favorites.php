<?php
require "includes/db_connect.php";
require "includes/auth_check.php";
$page_title = "Your Favorites";
require "includes/header.php";

$user_id = $_SESSION["user_id"];

$sql = "
SELECT p.* 
FROM favorites f
JOIN properties p ON f.property_id = p.id
WHERE f.user_id = $user_id
";

$result = $conn->query($sql);
?>

<h1>Your Favorites</h1>

<div class="cards">
<?php while($row = $result->fetch_assoc()): ?>
    <div class="card">
        <div class="card-img-wrap">
            <img src="<?php echo $row['main_image']; ?>">

            <div class="heart-btn" data-id="<?php echo $row['id']; ?>">♥</div>
        </div>

        <h3><?php echo $row['title']; ?></h3>
        <p class="location"><?php echo $row['city']; ?> · <?php echo $row['type']; ?></p>
        <p class="price">$<?php echo $row['price']; ?> / night</p>

        <a class="book-btn" href="property.php?id=<?php echo $row['id']; ?>">View</a>
    </div>
<?php endwhile; ?>
</div>

<?php require "includes/footer.php"; ?>
