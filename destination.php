<?php
session_start();
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Destinations - Tour & Travel</title>
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>

<!-- Header -->
<header>
    <h1>Top Destinations</h1>
    <nav>
        <a href="index.php">Home</a> |
        <a href="about.php">About</a> |
        <a href="packages.php">Packages</a> |
        <a href="destination.php">Destinations</a> |
        <a href="contact.php">Contact</a> |

        <?php if(isset($_SESSION['user_id'])): ?>
            <a href='my-bookings.php'>My Bookings</a> |
            <a href='profile.php'>Profile</a> |
            <a href='logout.php'>Logout</a>
        <?php else: ?>
            <a href='login.php'>Login</a> |
            <a href='signup.php'>Signup</a>
        <?php endif; ?>
    </nav>
</header>

<!-- Main -->
<main>
    <h2>Popular Travel Destinations</h2>

    <div class="packages">

        <div class="package">
            <h3>Goa</h3>
            <p>Beaches, nightlife & water sports.</p>
            <a href="packages.php">View Packages</a>
        </div>

        <div class="package">
            <h3>Manali</h3>
            <p>Snow mountains, adventure & trekking.</p>
            <a href="packages.php">View Packages</a>
        </div>

        <div class="package">
            <h3>Kerala</h3>
            <p>Backwaters, houseboats & nature.</p>
            <a href="packages.php">View Packages</a>
        </div>

        <div class="package">
            <h3>Dubai</h3>
            <p>Skyscrapers, desert safari & shopping.</p>
            <a href="packages.php">View Packages</a>
        </div>

        <div class="package">
            <h3>Thailand</h3>
            <p>Beaches, nightlife & islands.</p>
            <a href="packages.php">View Packages</a>
        </div>

        <div class="package">
            <h3>Kashmir</h3>
            <p>Heaven on earth, mountains & lakes.</p>
            <a href="packages.php">View Packages</a>
        </div>

    </div>
</main>

<!-- Footer -->
<footer>
    <p>© 2025 Tour & Travel Website</p>
</footer>

</body>
</html>
