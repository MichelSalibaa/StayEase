<?php
$page_title = "Home";
require 'includes/header.php';
require 'includes/db_connect.php';


// ----------------------------
// 1) READ FILTER FROM URL
// ----------------------------
$filter = isset($_GET['type']) ? $_GET['type'] : 'all';

$whereSQL = "";

if ($filter !== 'all') {
    $whereSQL = "WHERE type = '" . $conn->real_escape_string($filter) . "'";
}


// ----------------------------
// 2) BUILD THE CORRECT SQL QUERY
// ----------------------------
$query = "SELECT * FROM properties $whereSQL ORDER BY created_at DESC LIMIT 50";
$result = $conn->query($query);

?>

<h1>Latest Listings</h1>

<!-- ----------------------------
     FILTER BUTTONS
---------------------------- -->
<div class="filter-bar">

    <a href="index.php?type=all" class="filter-btn <?php echo ($filter=='all' ? 'active' : ''); ?>">✨ All listings</a>

    <a href="index.php?type=guesthouse" class="filter-btn <?php echo ($filter=='guesthouse' ? 'active' : ''); ?>">🏠 Guesthouses</a>

    <a href="index.php?type=apartment" class="filter-btn <?php echo ($filter=='apartment' ? 'active' : ''); ?>">🏢 Apartments</a>

    <a href="index.php?type=camping" class="filter-btn <?php echo ($filter=='camping' ? 'active' : ''); ?>">🏕 Camping</a>

</div>



<!-- ----------------------------
     PROPERTY CARDS
---------------------------- -->
<div class="cards">
<?php while ($row = $result->fetch_assoc()): ?>
    <div class="card">

        <div class="card-img-wrap">
            <img src="<?php echo $row['main_image']; ?>" alt="Property Image">

            <!-- heart overlay icon -->
            <div class="heart-btn" data-id="<?php echo $row['id']; ?>">
                <?php 
                    if (isset($_SESSION['user_id'])) {
                        $u = $_SESSION['user_id'];
                        $p = $row['id'];
                        $fav_check = $conn->query("SELECT id FROM favorites WHERE user_id=$u AND property_id=$p");
                        echo ($fav_check->num_rows > 0) ? "♥" : "♡";
                    } else {
                        echo "♡";
                    }
                ?>
            </div>
        </div>

        <h3><?php echo $row['title']; ?></h3>

        <p class="location">
            <?php echo $row['city']; ?> · <?php echo $row['type']; ?>
        </p>

        <p class="details">
            <?php echo $row['max_guests']; ?> guests · <?php echo $row['type']; ?>
        </p>

        <p class="price">
            $<?php echo number_format($row['price'], 2); ?> / night
        </p>

        <a class="book-btn" href="property.php?id=<?php echo $row['id']; ?>">Book now</a>

    </div>
<?php endwhile; ?>
</div>

<?php require 'includes/footer.php'; ?>
