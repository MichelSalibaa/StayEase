<?php
$is_logged_in = isset($_SESSION["user_id"]);
?>

<nav class="navbar">
    <div class="nav-left">
        <a href="index.php" class="nav-logo">RentEase</a>
        <a href="listings.php" class="nav-link">Browse</a>
    </div>

    <div class="nav-right">
        <?php if ($is_logged_in): ?>
            <a href="dashboard.php" class="nav-link">Dashboard</a>
            <a href="favorites.php" class="nav-link">Favorites</a>
            <a href="my_bookings.php" class="nav-link">Bookings</a>
            <a href="profile.php" class="nav-link">Profile</a>
            <a href="logout.php" class="nav-btn logout-btn">Logout</a>
        <?php else: ?>
            <a href="login.php" class="nav-btn login-btn">Login</a>
            <a href="register.php" class="nav-btn register-btn">Sign Up</a>
        <?php endif; ?>
    </div>
</nav>
