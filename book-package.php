<?php
session_start();
include 'include/db.php';
if(!isset($_GET['id']) || !is_numeric($_GET['id'])){ header('Location: index.php'); exit; }
$package_id = (int)$_GET['id'];
$res = $conn->query("SELECT * FROM packages WHERE id={$package_id}");
if($res->num_rows==0){ echo 'Package not found'; exit; }
$p = $res->fetch_assoc();
if(!isset($_SESSION['user_id'])){ header('Location: login.php?redirect=book-package.php?id={$package_id}'); exit; }
$msg = '';
if($_SERVER['REQUEST_METHOD']=='POST'){
    $travel_date = $conn->real_escape_string($_POST['travel_date']);
    $travelers = (int)$_POST['travelers'];
    $message = $conn->real_escape_string($_POST['message']);
    $uid = (int)$_SESSION['user_id'];
    $conn->query("INSERT INTO bookings (user_id, package_id, travel_date, travelers, message) VALUES ({$uid}, {$package_id}, '{$travel_date}', {$travelers}, '{$message}')");
    header('Location: thanks.php'); exit;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset='utf-8'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book <?php echo htmlspecialchars($p['title']); ?> - Tour & Travel</title>
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

        .booking-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            margin-bottom: 3rem;
        }

        .package-summary {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: var(--shadow);
            height: fit-content;
        }

        .package-image {
            height: 200px;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 1.5rem;
            background: linear-gradient(45deg, var(--secondary), var(--primary));
        }

        .package-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .package-title {
            font-size: 1.8rem;
            color: var(--primary);
            margin-bottom: 1rem;
        }

        .package-meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #eee;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #666;
        }

        .meta-item i {
            color: var(--secondary);
        }

        .package-price {
            font-size: 2rem;
            font-weight: 700;
            color: var(--accent);
            text-align: center;
            margin: 1.5rem 0;
        }

        .booking-form-container {
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
            min-height: 120px;
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

        .form-note {
            background: #e8f4fd;
            border-left: 4px solid var(--secondary);
            padding: 1rem;
            border-radius: 4px;
            margin-top: 1.5rem;
            font-size: 0.9rem;
            color: #2c3e50;
        }

        .form-note i {
            color: var(--secondary);
            margin-right: 5px;
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

            .booking-container {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .package-summary {
                order: 2;
            }

            .booking-form-container {
                order: 1;
            }

            .page-title {
                font-size: 2rem;
            }

            .package-title {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 480px) {
            .page-title {
                font-size: 1.8rem;
            }

            .booking-form-container {
                padding: 1.5rem;
            }

            .package-summary {
                padding: 1.5rem;
            }
        }

        .default-image {
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            font-weight: 600;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7);
            background: linear-gradient(45deg, var(--secondary), var(--primary));
            height: 100%;
        }

        .total-price {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            text-align: center;
            margin-top: 1rem;
            border: 2px dashed var(--secondary);
        }

        .total-price .label {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 0.5rem;
        }

        .total-price .amount {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--accent);
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
        <div class="breadcrumb">
            <a href="index.php"><i class="fas fa-home"></i> Home</a>
            <span>/</span>
            <a href="packages.php">Packages</a>
            <span>/</span>
            <a href="package-details.php?id=<?php echo $package_id; ?>"><?php echo htmlspecialchars($p['title']); ?></a>
            <span>/</span>
            <span>Book Now</span>
        </div>
        <h1 class="page-title">Book Your Adventure</h1>
        <p class="page-subtitle">Complete your booking for <?php echo htmlspecialchars($p['title']); ?></p>
    </div>
</section>

<!-- Main Content -->
<main>
    <div class="booking-container">
        <!-- Package Summary -->
        <div class="package-summary">
            <div class="package-image">
                <?php if($p['image'] != ""): ?>
                    <img src="uploads/packages/<?php echo $p['image']; ?>" alt="<?php echo htmlspecialchars($p['title']); ?>">
                <?php else: ?>
                    <div class="default-image">
                        <?php echo htmlspecialchars($p['title']); ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <h2 class="package-title"><?php echo htmlspecialchars($p['title']); ?></h2>
            
            <div class="package-meta">
                <div class="meta-item">
                    <i class="far fa-calendar-alt"></i>
                    <span><?php echo $p['days']; ?> Days / <?php echo $p['nights']; ?> Nights</span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-users"></i>
                    <span>Group Tour</span>
                </div>
            </div>
            
            <div class="package-price">
                ₹<?php echo number_format($p['price'], 2); ?>
                <div style="font-size: 0.9rem; color: #666; font-weight: normal;">per person</div>
            </div>
            
            <div class="total-price">
                <div class="label">Estimated Total (1 Traveler)</div>
                <div class="amount">₹<?php echo number_format($p['price'], 2); ?></div>
            </div>
        </div>

        <!-- Booking Form -->
        <div class="booking-form-container">
            <h2 class="form-title">
                <i class="fas fa-ticket-alt"></i> Booking Details
            </h2>
            
            <form method='post'>
                <div class="form-group">
                    <label for="travel_date">
                        <i class="fas fa-calendar-day"></i> Travel Date
                    </label>
                    <input type='date' name='travel_date' id='travel_date' class="form-control" required 
                           min="<?php echo date('Y-m-d'); ?>">
                </div>

                <div class="form-group">
                    <label for="travelers">
                        <i class="fas fa-users"></i> Number of Travelers
                    </label>
                    <input type='number' name='travelers' id='travelers' value='1' min='1' max='20' 
                           class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="message">
                        <i class="fas fa-comment"></i> Special Requirements
                    </label>
                    <textarea name='message' id='message' class="form-control" 
                              placeholder="Any special requirements, dietary restrictions, or additional information..."></textarea>
                </div>

                <button type='submit' class="btn-form btn-primary-form">
                    <i class="fas fa-paper-plane"></i> Confirm Booking
                </button>
            </form>

            <div class="form-note">
                <i class="fas fa-info-circle"></i>
                Your booking will be confirmed immediately. Our team will contact you within 24 hours with further details.
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

<script>
    // Update total price when travelers change
    document.getElementById('travelers').addEventListener('input', function() {
        const travelers = parseInt(this.value) || 1;
        const pricePerPerson = <?php echo $p['price']; ?>;
        const totalPrice = travelers * pricePerPerson;
        
        document.querySelector('.total-price .amount').textContent = '₹' + totalPrice.toLocaleString('en-IN');
        document.querySelector('.total-price .label').textContent = 'Estimated Total (' + travelers + ' Traveler' + (travelers > 1 ? 's' : '') + ')';
    });

    // Set minimum date to today
    document.getElementById('travel_date').min = new Date().toISOString().split('T')[0];
</script>

</body>
</html>