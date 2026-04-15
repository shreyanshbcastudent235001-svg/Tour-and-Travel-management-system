<?php
session_start();
include 'include/db.php';

if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: packages.php");
    exit;
}

$id = (int)$_GET['id'];
$res = $conn->query("SELECT * FROM packages WHERE id=$id");

if($res->num_rows == 0){
    echo "Package not found"; 
    exit;
}

$p = $res->fetch_assoc();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($p['title']); ?> - Tour & Travel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #2c3e50;
            --secondary: #3498db;
            --accent: #e74c3c;
            --light: #ecf0f1;
            --dark: #2c3e50;
            --success: #2ecc71;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f9f9f9;
            color: #333;
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Header Styles */
        .main-header {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 1rem 0;
            box-shadow: var(--shadow);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo i {
            font-size: 1.8rem;
        }

        .logo h1 {
            font-size: 1.8rem;
            font-weight: 700;
        }

        nav {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }

        nav a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            padding: 0.5rem 0;
            position: relative;
            transition: var(--transition);
        }

        nav a:hover {
            color: var(--light);
        }

        nav a.active {
            color: var(--light);
        }

        nav a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background-color: var(--light);
            transition: var(--transition);
        }

        nav a:hover::after,
        nav a.active::after {
            width: 100%;
        }

        .auth-buttons {
            display: flex;
            gap: 1rem;
        }

        .btn {
            padding: 0.5rem 1.2rem;
            border-radius: 4px;
            font-weight: 600;
            text-decoration: none;
            transition: var(--transition);
            display: inline-block;
        }

        .btn-outline {
            border: 2px solid white;
            color: white;
        }

        .btn-outline:hover {
            background: white;
            color: var(--primary);
        }

        .btn-primary {
            background: var(--accent);
            color: white;
            border: 2px solid var(--accent);
        }

        .btn-primary:hover {
            background: transparent;
            color: var(--accent);
        }

        /* Page Header */
        .page-header {
            background: linear-gradient(rgba(44, 62, 80, 0.9), rgba(52, 152, 219, 0.9)), url('https://images.unsplash.com/photo-1488646953014-85cb44e25828?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 3rem 0;
            text-align: center;
            margin-bottom: 3rem;
        }

        .page-header-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .breadcrumb {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .breadcrumb a {
            color: var(--light);
            text-decoration: none;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .breadcrumb a:hover {
            color: white;
            text-decoration: underline;
        }

        .page-title {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            font-weight: 700;
        }

        .page-subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
        }

        /* Main Content */
        main {
            flex: 1;
            max-width: 1200px;
            margin: 0 auto 3rem;
            padding: 0 20px;
        }

        .package-detail-container {
            width: 100%;
        }

        .package-detail {
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .package-hero {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            padding: 2rem;
        }

        .package-image {
            border-radius: 10px;
            overflow: hidden;
            height: 400px;
            background: linear-gradient(45deg, var(--secondary), var(--primary));
        }

        .package-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .package-info {
            padding: 1rem 0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .package-title {
            font-size: 2.2rem;
            color: var(--primary);
            margin-bottom: 1rem;
            line-height: 1.2;
        }

        .package-meta {
            display: flex;
            gap: 2rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid #eee;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 1.1rem;
        }

        .meta-item i {
            color: var(--secondary);
            font-size: 1.2rem;
        }

        .package-price {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--accent);
            margin-bottom: 1.5rem;
        }

        .package-description {
            margin-bottom: 2rem;
            line-height: 1.7;
            color: #555;
            font-size: 1.1rem;
        }

        .btn {
            padding: 1rem 2.5rem;
            border-radius: 6px;
            font-weight: 600;
            text-decoration: none;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-size: 1.1rem;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: var(--accent);
            color: white;
        }

        .btn-primary:hover {
            background: #c0392b;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(231, 76, 60, 0.3);
        }

        .btn-outline {
            background: transparent;
            color: var(--primary);
            border: 2px solid var(--primary);
        }

        .btn-outline:hover {
            background: var(--primary);
            color: white;
        }

        /* Itinerary Section */
        .itinerary-section {
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow);
            padding: 2.5rem;
            margin-bottom: 2rem;
        }

        .section-title {
            font-size: 1.8rem;
            color: var(--primary);
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--secondary);
            display: inline-block;
        }

        .itinerary-box {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            border-left: 4px solid var(--secondary);
            transition: var(--transition);
        }

        .itinerary-box:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .itinerary-day {
            font-weight: 700;
            margin-bottom: 0.8rem;
            color: var(--primary);
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .itinerary-day i {
            color: var(--secondary);
        }

        .itinerary-text {
            color: #555;
            line-height: 1.6;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            flex-wrap: wrap;
        }

        .cta-section {
            text-align: center;
            margin-top: 3rem;
            padding: 2rem;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 12px;
            color: white;
        }

        .cta-title {
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .cta-subtitle {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        /* Footer */
        footer {
            background: var(--dark);
            color: white;
            text-align: center;
            padding: 2.5rem 0;
            margin-top: auto;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        .footer-links a {
            color: var(--light);
            text-decoration: none;
            transition: var(--transition);
        }

        .footer-links a:hover {
            color: white;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .header-container {
                flex-direction: column;
                gap: 1rem;
            }

            nav {
                flex-wrap: wrap;
                justify-content: center;
                gap: 1rem;
            }

            .package-hero {
                grid-template-columns: 1fr;
                gap: 1.5rem;
                padding: 1.5rem;
            }

            .package-image {
                height: 300px;
            }

            .package-title {
                font-size: 1.8rem;
            }

            .package-meta {
                flex-direction: column;
                gap: 1rem;
            }

            .package-price {
                font-size: 2rem;
            }

            .itinerary-section {
                padding: 1.5rem;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            .page-title {
                font-size: 2rem;
            }
        }

        @media (max-width: 480px) {
            .package-image {
                height: 250px;
            }

            .package-title {
                font-size: 1.6rem;
            }

            .itinerary-section {
                padding: 1rem;
            }

            .page-title {
                font-size: 1.8rem;
            }
        }

        .default-image {
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            font-weight: 600;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7);
            background: linear-gradient(45deg, var(--secondary), var(--primary));
            height: 100%;
        }

        .inclusion-item {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 0.8rem;
            color: #555;
        }

        .inclusion-item i {
            color: var(--success);
            font-size: 1.1rem;
        }
    </style>
</head>
<body>

<!-- Main Navigation Header -->
<header class="main-header">
    <div class="header-container">
        <div class="logo">
            <i class="fas fa-plane-departure"></i>
            <h1>Tour & Travel</h1>
        </div>

        <nav>
            <a href="index.php">Home</a>
            <a href="about.php">About</a>
            <a href="packages.php" class="active">Packages</a>
            <a href="contact.php">Contact</a>
            
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href='my-bookings.php'>My Bookings</a>
                <a href='profile.php'>Profile</a>
                <div class="auth-buttons">
                    <a href='logout.php' class='btn btn-outline'>Logout</a>
                </div>
            <?php else: ?>
                <div class="auth-buttons">
                    <a href='login.php' class='btn btn-outline'>Login</a>
                    <a href='signup.php' class='btn btn-primary'>Sign Up</a>
                </div>
            <?php endif; ?>
        </nav>
    </div>
</header>

<!-- Page Header -->
<section class="page-header">
    <div class="page-header-container">
        <h1 class="page-title"><?php echo htmlspecialchars($p['title']); ?></h1>
        <p class="page-subtitle"><?php echo $p['days']; ?> Days / <?php echo $p['nights']; ?> Nights of unforgettable experience</p>
    </div>
</section>

<!-- Main Content -->
<main>
    <div class="package-detail-container">
        <div class="package-detail">
            <div class="package-hero">
                <div class="package-image">
                    <?php if($p['image'] != ""): ?>
                        <img src="uploads/packages/<?php echo $p['image']; ?>" alt="<?php echo htmlspecialchars($p['title']); ?>">
                    <?php else: ?>
                        <div class="default-image">
                            <?php echo htmlspecialchars($p['title']); ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="package-info">
                    <div>
                        <h1 class="package-title"><?php echo htmlspecialchars($p['title']); ?></h1>
                        
                        <div class="package-meta">
                            <div class="meta-item">
                                <i class="far fa-calendar-alt"></i>
                                <span><?php echo $p['days']; ?> Days / <?php echo $p['nights']; ?> Nights</span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-users"></i>
                                <span>Group Tour Available</span>
                            </div>
                        </div>
                        
                        <div class="package-price">
                            ₹<?php echo number_format($p['price'], 2); ?>
                        </div>
                        
                        <div class="package-description">
                            <?php echo nl2br(htmlspecialchars($p['description'])); ?>
                        </div>
                    </div>
                    
                    <div class="action-buttons">
                        <a href="book-package.php?id=<?php echo $p['id']; ?>" class="btn btn-primary">
                            <i class="fas fa-ticket-alt"></i> Book This Package
                        </a>
                        <a href="packages.php" class="btn btn-outline">
                            <i class="fas fa-arrow-left"></i> Back to Packages
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- DAY-WISE ITINERARY SECTION -->
        <?php if(!empty($p['itinerary'])): ?>
        <div class="itinerary-section">
            <h2 class="section-title">
                <i class="fas fa-route"></i> Day-Wise Itinerary
            </h2>
            
            <?php
            $lines = explode("\n", $p['itinerary']);
            $dayCount = 0;
            
            foreach($lines as $line):
                $line = trim($line);
                if($line == "") continue;
                
                // Check if line starts with "Day"
                if(stripos($line, "day") === 0) {
                    $dayCount++;
            ?>
                <div class="itinerary-box">
                    <div class="itinerary-day">
                        <i class="fas fa-map-marker-alt"></i>
                        <?php 
                        $dayPart = substr($line, 0, strpos($line, ":") + 1);
                        echo $dayPart; 
                        ?>
                    </div>
                    <div class="itinerary-text">
                        <?php 
                        $description = trim(substr($line, strpos($line, ":") + 1));
                        echo nl2br($description); 
                        ?>
                    </div>
                </div>
            <?php 
                } else {
                    // For lines that don't start with "Day", create a general info box
            ?>
                <div class="itinerary-box">
                    <div class="itinerary-text">
                        <?php echo nl2br($line); ?>
                    </div>
                </div>
            <?php
                }
            endforeach; 
            ?>
        </div>
        <?php endif; ?>

        <!-- Call to Action Section -->
        <div class="cta-section">
            <h2 class="cta-title">Ready for an Amazing Journey?</h2>
            <p class="cta-subtitle">Book now and create unforgettable memories with our expertly crafted tour package</p>
            <a href="book-package.php?id=<?php echo $p['id']; ?>" class="btn btn-primary" style="background: white; color: var(--primary); font-size: 1.2rem; padding: 1.2rem 3rem;">
                <i class="fas fa-ticket-alt"></i> Book Now - ₹<?php echo number_format($p['price'], 2); ?>
            </a>
        </div>
    </div>
</main>

<!-- Footer -->
<footer>
    <div class="footer-content">
        <div class="footer-links">
            <a href="index.php">Home</a>
            <a href="about.php">About</a>
            <a href="packages.php">Packages</a>
            <a href="contact.php">Contact</a>
            <a href="privacy.php">Privacy Policy</a>
            <a href="terms.php">Terms of Service</a>
        </div>
        <p>© 2025 Tour & Travel. All rights reserved.</p>
    </div>
</footer>

</body>
</html>