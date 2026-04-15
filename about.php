<?php
session_start();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Tour & Travel</title>
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

        /* Main Content */
        main {
            flex: 1;
            max-width: 1200px;
            margin: 0 auto;
            padding: 3rem 20px;
        }

        /* Hero Section */
        .about-hero {
            text-align: center;
            margin-bottom: 4rem;
            padding: 3rem 0;
        }

        .about-hero h1 {
            font-size: 3rem;
            color: var(--primary);
            margin-bottom: 1rem;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .about-hero p {
            font-size: 1.3rem;
            color: #666;
            max-width: 700px;
            margin: 0 auto;
        }

        /* Content Sections */
        .content-section {
            background: white;
            border-radius: 12px;
            padding: 3rem;
            margin-bottom: 3rem;
            box-shadow: var(--shadow);
            transition: var(--transition);
        }

        .content-section:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .section-title {
            font-size: 2.2rem;
            color: var(--primary);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .section-title i {
            color: var(--secondary);
        }

        .section-content {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #555;
        }

        .section-content p {
            margin-bottom: 1.5rem;
        }

        /* Features Grid */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .feature-card {
            background: #f8f9fa;
            padding: 2rem;
            border-radius: 10px;
            text-align: center;
            transition: var(--transition);
            border-left: 4px solid var(--secondary);
        }

        .feature-card:hover {
            transform: translateY(-5px);
            background: white;
            box-shadow: var(--shadow);
        }

        .feature-icon {
            font-size: 2.5rem;
            color: var(--secondary);
            margin-bottom: 1rem;
        }

        .feature-card h4 {
            font-size: 1.3rem;
            color: var(--primary);
            margin-bottom: 1rem;
        }

        .feature-card p {
            color: #666;
            line-height: 1.6;
        }

        /* Stats Section */
        .stats-section {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 4rem 2rem;
            border-radius: 12px;
            text-align: center;
            margin: 4rem 0;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            max-width: 800px;
            margin: 0 auto;
        }

        .stat-item {
            padding: 1.5rem;
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        /* Team Section */
        .team-section {
            text-align: center;
        }

        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .team-member {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: var(--shadow);
            transition: var(--transition);
        }

        .team-member:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .member-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--secondary), var(--primary));
            margin: 0 auto 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2.5rem;
        }

        .member-name {
            font-size: 1.4rem;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }

        .member-role {
            color: var(--secondary);
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .member-bio {
            color: #666;
            line-height: 1.6;
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
            }

            .about-hero h1 {
                font-size: 2.2rem;
            }

            .about-hero p {
                font-size: 1.1rem;
            }

            .content-section {
                padding: 2rem;
            }

            .section-title {
                font-size: 1.8rem;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .stat-number {
                font-size: 2.2rem;
            }

            .footer-links {
                flex-direction: column;
                gap: 1rem;
            }
        }

        @media (max-width: 480px) {
            .about-hero h1 {
                font-size: 1.8rem;
            }

            .content-section {
                padding: 1.5rem;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .team-grid {
                grid-template-columns: 1fr;
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
            <a href="about.php" style="color: var(--light);">About</a>
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

<!-- Main Content -->
<main>
    <!-- Hero Section -->
    <section class="about-hero">
        <h1>About Tour & Travel</h1>
        <p>Your trusted partner in creating unforgettable travel experiences since 2015</p>
    </section>

    <!-- Our Story Section -->
    <section class="content-section">
        <h2 class="section-title">
            <i class="fas fa-history"></i> Our Story
        </h2>
        <div class="section-content">
            <p>
                Founded in 2015, Tour & Travel has been at the forefront of creating exceptional travel experiences 
                for thousands of satisfied customers. What started as a small local agency has grown into a trusted 
                name in the travel industry, known for our commitment to quality and customer satisfaction.
            </p>
            <p>
                Our journey began with a simple mission: to make travel accessible, enjoyable, and memorable for everyone. 
                Over the years, we've expanded our offerings while maintaining our core values of integrity, reliability, 
                and personalized service.
            </p>
        </div>
    </section>

    <!-- Mission Section -->
    <section class="content-section">
        <h2 class="section-title">
            <i class="fas fa-bullseye"></i> Our Mission
        </h2>
        <div class="section-content">
            <p>
                We are a professional tour and travel agency offering holiday packages,
                adventure trips, and customized travel plans. Our mission is to make your 
                journey comfortable, affordable, and memorable.
            </p>
            <p>
                We believe that travel has the power to transform lives, broaden perspectives, 
                and create lasting memories. That's why we're committed to providing exceptional 
                service and creating travel experiences that exceed your expectations.
            </p>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="content-section">
        <h2 class="section-title">
            <i class="fas fa-star"></i> Why Choose Us?
        </h2>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-rupee-sign"></i>
                </div>
                <h4>Affordable Packages</h4>
                <p>Get the best value for your money with our competitively priced packages without compromising on quality.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-hotel"></i>
                </div>
                <h4>Best Hotels</h4>
                <p>Stay at carefully selected premium hotels that guarantee comfort and excellent service throughout your journey.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-headset"></i>
                </div>
                <h4>24/7 Support</h4>
                <p>Round-the-clock customer support to assist you anytime, anywhere during your travel experience.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-map-marked-alt"></i>
                </div>
                <h4>Custom Planning</h4>
                <p>Personalized tour planning to match your preferences, schedule, and budget requirements perfectly.</p>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-number">8+</div>
                <div class="stat-label">Years Experience</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">5000+</div>
                <div class="stat-label">Happy Travelers</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">50+</div>
                <div class="stat-label">Destinations</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">24/7</div>
                <div class="stat-label">Customer Support</div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="content-section team-section">
        <h2 class="section-title">
            <i class="fas fa-users"></i> Meet Our Team
        </h2>
        <div class="team-grid">
            <div class="team-member">
                <div class="member-avatar">
                    <i class="fas fa-user-tie"></i>
                </div>
                <h3 class="member-name">Rajesh Kumar</h3>
                <div class="member-role">Founder & CEO</div>
                <p class="member-bio">With over 15 years in the travel industry, Rajesh leads our team with passion and expertise.</p>
            </div>
            
            <div class="team-member">
                <div class="member-avatar">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <h3 class="member-name">Priya Sharma</h3>
                <div class="member-role">Travel Consultant</div>
                <p class="member-bio">Priya specializes in creating customized itineraries that match your dream vacation.</p>
            </div>
            
            <div class="team-member">
                <div class="member-avatar">
                    <i class="fas fa-user-cog"></i>
                </div>
                <h3 class="member-name">Amit Patel</h3>
                <div class="member-role">Operations Manager</div>
                <p class="member-bio">Amit ensures smooth operations and exceptional service delivery for all our clients.</p>
            </div>
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
        <p>© 2025 Tour & Travel. All rights reserved.</p>
    </div>
</footer>

</body>
</html>