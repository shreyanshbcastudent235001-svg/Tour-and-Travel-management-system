<?php
session_start();
include 'include/db.php';

$q = $conn->query("SELECT * FROM packages ORDER BY created_at DESC");
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Tour Packages - Tour & Travel</title>
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
            padding: 4rem 0;
            text-align: center;
            margin-bottom: 3rem;
        }

        .page-header-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .page-title {
            font-size: 3rem;
            margin-bottom: 1rem;
            font-weight: 700;
        }

        .page-subtitle {
            font-size: 1.3rem;
            margin-bottom: 1.5rem;
            opacity: 0.9;
        }

        .breadcrumb {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
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

        /* Main Content */
        main {
            flex: 1;
            max-width: 1200px;
            margin: 0 auto 3rem;
            padding: 0 20px;
        }

        /* Packages Grid */
        .packages-container {
            width: 100%;
        }

        .packages {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .package {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: var(--transition);
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .package:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        .package-image {
            height: 220px;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.6rem;
            font-weight: 600;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7);
            position: relative;
        }

        .package-image::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, rgba(0,0,0,0.2), rgba(0,0,0,0.5));
        }

        .package-image-content {
            position: relative;
            z-index: 2;
            text-align: center;
            padding: 1rem;
        }

        .package-content {
            padding: 1.8rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .package h3 {
            font-size: 1.4rem;
            margin-bottom: 1rem;
            color: var(--primary);
            line-height: 1.3;
            min-height: 3.5rem;
        }

        .package-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #eee;
        }

        .package-price {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--accent);
        }

        .package-duration {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #666;
            font-weight: 500;
        }

        .package-duration i {
            color: var(--secondary);
            font-size: 1.1rem;
        }

        .package-description {
            color: #555;
            margin-bottom: 1.5rem;
            line-height: 1.5;
            flex: 1;
        }

        .package-link {
            display: inline-block;
            padding: 0.8rem 1.5rem;
            background: var(--secondary);
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            transition: var(--transition);
            text-align: center;
            width: 100%;
            border: none;
            cursor: pointer;
            font-size: 1rem;
        }

        .package-link:hover {
            background: var(--primary);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .package-link i {
            margin-left: 5px;
            transition: var(--transition);
        }

        .package-link:hover i {
            transform: translateX(3px);
        }

        /* No Packages Message */
        .no-packages {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow);
            grid-column: 1 / -1;
        }

        .no-packages i {
            font-size: 4rem;
            color: #ddd;
            margin-bottom: 1.5rem;
        }

        .no-packages h3 {
            font-size: 1.8rem;
            color: var(--primary);
            margin-bottom: 1rem;
        }

        .no-packages p {
            color: #666;
            font-size: 1.1rem;
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

            .page-title {
                font-size: 2.2rem;
            }

            .page-subtitle {
                font-size: 1.1rem;
            }

            .packages {
                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
                gap: 1.5rem;
            }

            .package-image {
                height: 200px;
            }

            .package-content {
                padding: 1.5rem;
            }

            .package-meta {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.8rem;
            }

            .footer-links {
                flex-direction: column;
                gap: 1rem;
            }
        }

        @media (max-width: 480px) {
            .packages {
                grid-template-columns: 1fr;
            }

            .package-image {
                height: 180px;
                font-size: 1.3rem;
            }

            .package-content {
                padding: 1.2rem;
            }

            .page-title {
                font-size: 1.8rem;
            }
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
        <h1 class="page-title">Explore All Tour Packages</h1>
        <p class="page-subtitle">Discover our carefully curated collection of amazing travel experiences</p>
    </div>
</section>

<!-- Main Content -->
<main>
    <div class="packages-container">
        <div class='packages'>
        <?php 
        if($q->num_rows > 0): 
            while($p = $q->fetch_assoc()): 
                // Generate background image based on package title
                $bg_image = "https://images.unsplash.com/photo-1488646953014-85cb44e25828?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80";
                
                if(stripos($p['title'], 'goa') !== false) {
                    $bg_image = "https://images.unsplash.com/photo-1512343879784-a960bf40e7f2?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80";
                } elseif(stripos($p['title'], 'manali') !== false) {
                    $bg_image = "https://images.unsplash.com/photo-1599661048171-5d5c0ce32596?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80";
                } elseif(stripos($p['title'], 'ladakh') !== false) {
                    $bg_image = "https://images.unsplash.com/photo-1580136579312-94651dfd596d?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80";
                } elseif(stripos($p['title'], 'kerala') !== false) {
                    $bg_image = "https://images.unsplash.com/photo-1580619305218-8423a7ef79b4?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80";
                }
        ?>
            <div class='package'>
                <div class='package-image' style="background-image: url('<?php echo $bg_image; ?>');">
                    <div class="package-image-content">
                        <?php echo htmlspecialchars($p['title']); ?>
                    </div>
                </div>
                <div class='package-content'>
                    <h3><?php echo htmlspecialchars($p['title']); ?></h3>
                    <div class="package-meta">
                        <div class='package-price'>₹<?php echo number_format($p['price'],2); ?></div>
                        <div class='package-duration'>
                            <i class="far fa-calendar-alt"></i>
                            <span><?php echo (int)$p['days']; ?> Days</span>
                        </div>
                    </div>
                    <?php if(!empty($p['description'])): ?>
                        <div class='package-description'>
                            <?php echo substr(htmlspecialchars($p['description']), 0, 120); ?>...
                        </div>
                    <?php else: ?>
                        <div class='package-description'>
                            Experience the perfect getaway with our exclusive tour package. Book now for an unforgettable journey.
                        </div>
                    <?php endif; ?>
                    <a href='package-details.php?id=<?php echo $p['id']; ?>' class='package-link'>
                        View Details <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        <?php 
            endwhile; 
        else: 
        ?>
            <div class='no-packages'>
                <i class="fas fa-map-marked-alt"></i>
                <h3>No Packages Available</h3>
                <p>We're currently updating our tour packages. Please check back later for exciting travel opportunities.</p>
            </div>
        <?php endif; ?>
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