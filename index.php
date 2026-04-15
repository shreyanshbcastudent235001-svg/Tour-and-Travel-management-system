<?php
session_start();
include 'include/db.php';
$q = $conn->query("SELECT * FROM packages ORDER BY created_at DESC");
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset='utf-8'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tour & Travel - Explore Amazing Destinations</title>
    <link href='assets/css/style.css' rel='stylesheet'>
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
            overflow-x: hidden;
        }

        /* Header Styles */
        header {
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

        nav a:hover::after {
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

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 5rem 0;
            text-align: center;
            margin-bottom: 3rem;
        }

        .hero-content {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }

        .hero-subtitle {
            font-size: 1.3rem;
            margin-bottom: 2.5rem;
            opacity: 0.9;
            line-height: 1.6;
        }

        .hero-stats {
            display: flex;
            justify-content: center;
            gap: 3rem;
            margin-top: 3rem;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 1rem;
            opacity: 0.8;
        }

        /* Main Content */
        main {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px 3rem;
        }

        /* Packages Section */
        .packages-section {
            padding: 4rem 0;
            background: #f8f9fa;
        }

        .section-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 1rem;
        }

        .section-subtitle {
            font-size: 1.1rem;
            color: #666;
            max-width: 600px;
            margin: 0 auto;
        }

        .packages-grid {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 2rem;
        }

        .package-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: var(--transition);
        }

        .package-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        .package-image {
            height: 200px;
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .package-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: var(--accent);
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .package-content {
            padding: 1.5rem;
        }

        .package-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .package-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--primary);
            line-height: 1.3;
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
            margin-bottom: 1rem;
            color: #666;
        }

        .package-description {
            color: #555;
            margin-bottom: 1.5rem;
            line-height: 1.5;
        }

        .package-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .package-rating {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #ffc107;
        }

        .package-link {
            display: inline-block;
            padding: 0.7rem 1.5rem;
            background: var(--secondary);
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 600;
            transition: var(--transition);
        }

        .package-link:hover {
            background: var(--primary);
            transform: translateY(-2px);
        }

        /* No Packages Message */
        .no-packages {
            grid-column: 1 / -1;
            text-align: center;
            padding: 3rem 2rem;
            background: white;
            border-radius: 10px;
            box-shadow: var(--shadow);
        }

        .no-packages h3 {
            font-size: 1.5rem;
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
            padding: 2rem 0;
            margin-top: 3rem;
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
            }

            .hero-title {
                font-size: 2.5rem;
            }

            .hero-subtitle {
                font-size: 1.1rem;
            }

            .hero-stats {
                flex-direction: column;
                gap: 1.5rem;
            }

            .packages-grid {
                grid-template-columns: 1fr;
            }

            .package-card {
                margin: 0 10px;
            }

            .footer-links {
                flex-direction: column;
                gap: 1rem;
            }
        }

        @media (max-width: 480px) {
            .hero-title {
                font-size: 2rem;
            }
            
            .package-image {
                height: 180px;
            }
            
            .package-content {
                padding: 1.2rem;
            }
            
            .package-footer {
                flex-direction: column;
                gap: 1rem;
                align-items: stretch;
            }
            
            .package-rating {
                justify-content: center;
            }
        }
    </style>
</head>
<body>

<!-- Header -->
<header>
    <div class="header-container">
        <div class="logo">
            <i class="fas fa-plane-departure"></i>
            <h1>Tour & Travel</h1>
        </div>

        <nav>
            <a href="index.php">Home</a>
            <a href="about.php">About</a>
            <a href="packages.php">Packages</a>
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

<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-content">
        <h1 class="hero-title">Discover Your Dream Destination</h1>
        <p class="hero-subtitle">Explore breathtaking locations with our carefully curated tour packages. From serene beaches to majestic mountains, your perfect vacation awaits.</p>
        <a href="packages.php" class="btn btn-primary" style="font-size: 1.1rem; padding: 1rem 2rem;">
            <i class="fas fa-compass"></i>
            Explore All Packages
        </a>
        
        <div class="hero-stats">
            <div class="stat-item">
                <div class="stat-number">50+</div>
                <div class="stat-label">Destinations</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">1000+</div>
                <div class="stat-label">Happy Travelers</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">24/7</div>
                <div class="stat-label">Support</div>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<main>
    <!-- Packages Section -->
    <section class="packages-section">
        <div class="section-header">
            <h2 class="section-title">Featured Packages</h2>
            <p class="section-subtitle">Discover our most popular travel packages curated for unforgettable experiences</p>
        </div>

        <div class="packages-grid">
        <?php 
        if($q->num_rows > 0): 
            while($p = $q->fetch_assoc()): 
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
            <div class="package-card">
                <div class="package-image" style="background-image: url('<?php echo $bg_image; ?>');">
                    <div class="package-badge">Popular</div>
                </div>
                <div class="package-content">
                    <div class="package-header">
                        <h3 class="package-title"><?php echo htmlspecialchars($p['title']); ?></h3>
                        <div class="package-price">₹<?php echo number_format($p['price'],2); ?></div>
                    </div>
                    <div class="package-duration">
                        <i class="far fa-calendar-alt"></i>
                        <span><?php echo (int)$p['days']; ?> Days / <?php echo (int)$p['nights']; ?> Nights</span>
                    </div>
                    <p class="package-description">
                        <?php if(!empty($p['description'])): ?>
                            <?php echo substr(htmlspecialchars($p['description']), 0, 120); ?>...
                        <?php else: ?>
                            Experience the perfect getaway with our exclusive tour package. Book now for an unforgettable journey.
                        <?php endif; ?>
                    </p>
                    <div class="package-footer">
                        <div class="package-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                            <span>(4.5)</span>
                        </div>
                        <a href='package-details.php?id=<?php echo $p['id']; ?>' class='package-link'>
                            View Details
                        </a>
                    </div>
                </div>
            </div>
        <?php 
            endwhile; 
        else: 
        ?>
            <div class="no-packages">
                <h3>No packages available at the moment.</h3>
                <p>Please check back later for exciting tour packages.</p>
            </div>
        <?php endif; ?>
        </div>
        
        <div style="text-align: center; margin-top: 3rem;">
            <a href="packages.php" class="btn btn-primary" style="font-size: 1.1rem; padding: 1rem 2.5rem;">
                <i class="fas fa-eye"></i>
                View All Packages
            </a>
        </div>
    </section>
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
        <p>© 2025 Tour & Travel Website. All rights reserved.</p>
    </div>
</footer>

</body>
</html>