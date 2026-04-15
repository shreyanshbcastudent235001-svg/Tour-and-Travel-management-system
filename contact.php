<?php
session_start();

// If form submitted
$message_sent = false;

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    // Simple validation
    $name    = trim($_POST['name']);
    $email   = trim($_POST['email']);
    $message = trim($_POST['message']);

    if($name != "" && $email != "" && $message != ""){
        $message_sent = true;
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Tour & Travel</title>
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

        .logo a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
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
            align-items: center;
        }

        .btn {
            padding: 0.5rem 1.2rem;
            border-radius: 4px;
            font-weight: 600;
            text-decoration: none;
            transition: var(--transition);
            display: inline-block;
            text-align: center;
        }

        .btn-outline {
            border: 2px solid white;
            color: white;
            background: transparent;
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
            flex-wrap: wrap;
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

        .contact-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            margin-bottom: 3rem;
        }

        .contact-info {
            background: white;
            border-radius: 12px;
            padding: 2.5rem;
            box-shadow: var(--shadow);
            height: fit-content;
        }

        .contact-title {
            font-size: 1.8rem;
            color: var(--primary);
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--secondary);
        }

        .contact-methods {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .contact-method {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            padding: 1rem;
            border-radius: 8px;
            transition: var(--transition);
        }

        .contact-method:hover {
            background: #f8f9fa;
            transform: translateX(5px);
        }

        .contact-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--secondary), var(--primary));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .contact-details h4 {
            color: var(--primary);
            margin-bottom: 0.5rem;
            font-size: 1.1rem;
        }

        .contact-details p {
            color: #666;
            line-height: 1.5;
        }

        .contact-link {
            color: var(--secondary);
            text-decoration: none;
            transition: var(--transition);
        }

        .contact-link:hover {
            color: var(--primary);
            text-decoration: underline;
        }

        .contact-form-container {
            background: white;
            border-radius: 12px;
            padding: 2.5rem;
            box-shadow: var(--shadow);
        }

        .form-title {
            font-size: 1.8rem;
            color: var(--primary);
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--secondary);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-group label i {
            color: var(--secondary);
            width: 20px;
        }

        .form-control {
            width: 100%;
            padding: 1rem;
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            font-size: 1rem;
            transition: var(--transition);
            background-color: #f8f9fa;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--secondary);
            background-color: white;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }

        textarea.form-control {
            min-height: 150px;
            resize: vertical;
        }

        .btn-form {
            width: 100%;
            padding: 1.2rem;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 1rem;
        }

        .btn-primary-form {
            background: var(--accent);
            color: white;
        }

        .btn-primary-form:hover {
            background: #c0392b;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(231, 76, 60, 0.3);
        }

        .alert {
            padding: 1rem 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            border-left: 4px solid;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alert-success {
            background: #d4edda;
            border-color: var(--success);
            color: #155724;
        }

        .alert-success i {
            color: var(--success);
        }

        .office-hours {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1.5rem;
            margin-top: 2rem;
            border-left: 4px solid var(--secondary);
        }

        .office-hours h4 {
            color: var(--primary);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .office-hours ul {
            list-style: none;
            padding: 0;
        }

        .office-hours li {
            padding: 0.5rem 0;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            color: #555;
        }

        .office-hours li:last-child {
            border-bottom: none;
        }

        /* Map Section */
        .map-section {
            background: white;
            border-radius: 12px;
            padding: 2.5rem;
            box-shadow: var(--shadow);
            margin-top: 2rem;
        }

        .map-placeholder {
            background: linear-gradient(45deg, var(--secondary), var(--primary));
            height: 300px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            text-align: center;
            margin-top: 1rem;
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

            .auth-buttons {
                justify-content: center;
                width: 100%;
            }

            .contact-container {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .contact-info {
                order: 2;
            }

            .contact-form-container {
                order: 1;
            }

            .page-title {
                font-size: 2rem;
            }

            .contact-title, .form-title {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 480px) {
            .page-title {
                font-size: 1.8rem;
            }

            .contact-form-container, .contact-info {
                padding: 1.5rem;
            }

            .breadcrumb {
                font-size: 0.9rem;
            }

            .contact-method {
                flex-direction: column;
                text-align: center;
            }

            .contact-icon {
                align-self: center;
            }
        }

        .user-welcome {
            color: var(--light);
            font-size: 0.9rem;
            margin-right: 1rem;
        }
    </style>
</head>
<body>

<!-- Main Navigation Header -->
<header class="main-header">
    <div class="header-container">
        <div class="logo">
            <a href="index.php">
                <i class="fas fa-plane-departure"></i>
                <h1>Tour & Travel</h1>
            </a>
        </div>

        <nav>
            <a href="index.php">Home</a>
            <a href="about.php">About</a>
            <a href="packages.php">Packages</a>
            <a href="contact.php" class="active">Contact</a>
            
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href='my-bookings.php'>My Bookings</a>
                <a href='profile.php'>Profile</a>
                <div class="auth-buttons">
                    <span class="user-welcome">Welcome, User!</span>
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
        <div class="breadcrumb">
            <a href="index.php"><i class="fas fa-home"></i> Home</a>
            <span>/</span>
            <span>Contact Us</span>
        </div>
        <h1 class="page-title">Get In Touch</h1>
        <p class="page-subtitle">We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
    </div>
</section>

<!-- Main Content -->
<main>
    <?php if($message_sent): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <div>
                <strong>Thank you!</strong> Your message has been sent successfully. We will contact you soon.
            </div>
        </div>
    <?php endif; ?>

    <div class="contact-container">
        <!-- Contact Information -->
        <div class="contact-info">
            <h2 class="contact-title">
                <i class="fas fa-info-circle"></i> Contact Information
            </h2>
            
            <div class="contact-methods">
                <div class="contact-method">
                    <div class="contact-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div class="contact-details">
                        <h4>Phone</h4>
                        <p>Call us directly for immediate assistance</p>
                        <a href="tel:+919876543210" class="contact-link">+91 98765 43210</a>
                    </div>
                </div>
                
                <div class="contact-method">
                    <div class="contact-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="contact-details">
                        <h4>Email</h4>
                        <p>Send us an email anytime</p>
                        <a href="mailto:support@tourtravel.com" class="contact-link">support@tourtravel.com</a>
                    </div>
                </div>
                
                <div class="contact-method">
                    <div class="contact-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="contact-details">
                        <h4>Address</h4>
                        <p>Visit our office</p>
                        <p>123 Travel Street, Bhubaneswar<br>Odisha, India - 751001</p>
                    </div>
                </div>
                
                <div class="contact-method">
                    <div class="contact-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="contact-details">
                        <h4>Live Chat</h4>
                        <p>Available 24/7 for your convenience</p>
                        <a href="#" class="contact-link">Start Chat</a>
                    </div>
                </div>
            </div>

            <div class="office-hours">
                <h4><i class="fas fa-business-time"></i> Office Hours</h4>
                <ul>
                    <li>
                        <span>Monday - Friday</span>
                        <span>9:00 AM - 7:00 PM</span>
                    </li>
                    <li>
                        <span>Saturday</span>
                        <span>10:00 AM - 5:00 PM</span>
                    </li>
                    <li>
                        <span>Sunday</span>
                        <span>Emergency Support Only</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="contact-form-container">
            <h2 class="form-title">
                <i class="fas fa-paper-plane"></i> Send us a Message
            </h2>
            
            <form method="post" action="contact.php">
                <div class="form-group">
                    <label for="name">
                        <i class="fas fa-user"></i> Your Name
                    </label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Enter your full name" required>
                </div>

                <div class="form-group">
                    <label for="email">
                        <i class="fas fa-envelope"></i> Your Email
                    </label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email address" required>
                </div>

                <div class="form-group">
                    <label for="message">
                        <i class="fas fa-comment"></i> Your Message
                    </label>
                    <textarea name="message" id="message" class="form-control" placeholder="Tell us how we can help you..." required></textarea>
                </div>

                <button type="submit" class="btn-form btn-primary-form">
                    <i class="fas fa-paper-plane"></i> Send Message
                </button>
            </form>
        </div>
    </div>

    <!-- Map Section -->
    <div class="map-section">
        <h2 class="contact-title">
            <i class="fas fa-map"></i> Find Us
        </h2>
        <p>Visit our office in Raipur for personalized travel consultation</p>
        <div class="map-placeholder">
            <div>
                <i class="fas fa-map-marked-alt" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                <p>Interactive Map<br><small>New Raipur, Chhattisgarh, India</small></p>
            </div>
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