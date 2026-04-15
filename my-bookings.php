<?php
session_start();
include 'include/db.php';
if(!isset($_SESSION['user_id'])){ header('Location: login.php'); exit; }
$uid = (int)$_SESSION['user_id'];
$res = $conn->query("SELECT b.*, p.title, p.price, p.days, p.image FROM bookings b JOIN packages p ON p.id=b.package_id WHERE b.user_id={$uid} ORDER BY b.created_at DESC");
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset='utf-8'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings - Tour & Travel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #2c3e50;
            --secondary: #3498db;
            --accent: #e74c3c;
            --light: #ecf0f1;
            --dark: #2c3e50;
            --success: #2ecc71;
            --warning: #f39c12;
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

        .bookings-container {
            width: 100%;
        }

        .bookings-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .bookings-stats {
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: var(--shadow);
            text-align: center;
            min-width: 150px;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }

        .bookings-grid {
            display: grid;
            gap: 1.5rem;
        }

        .booking-card {
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow);
            overflow: hidden;
            transition: var(--transition);
        }

        .booking-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .booking-header {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .booking-id {
            font-size: 0.9rem;
            opacity: 0.8;
        }

        .booking-title {
            font-size: 1.4rem;
            font-weight: 600;
            margin: 0;
        }

        .booking-content {
            padding: 1.5rem;
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 2rem;
        }

        .booking-image {
            height: 150px;
            border-radius: 8px;
            overflow: hidden;
            background: linear-gradient(45deg, var(--secondary), var(--primary));
        }

        .booking-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .booking-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .detail-item {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .detail-label {
            font-size: 0.9rem;
            color: #666;
            font-weight: 500;
        }

        .detail-value {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--primary);
        }

        .status-badge {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-confirmed {
            background: #d4edda;
            color: #155724;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        .booking-footer {
            padding: 1rem 1.5rem;
            background: #f8f9fa;
            border-top: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .booking-actions {
            display: flex;
            gap: 1rem;
        }

        .btn-sm {
            padding: 0.5rem 1rem;
            border-radius: 4px;
            font-size: 0.9rem;
            text-decoration: none;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-info {
            background: var(--secondary);
            color: white;
            border: none;
        }

        .btn-info:hover {
            background: #2980b9;
            transform: translateY(-1px);
        }

        .btn-danger {
            background: var(--accent);
            color: white;
            border: none;
        }

        .btn-danger:hover {
            background: #c0392b;
            transform: translateY(-1px);
        }

        .no-bookings {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow);
        }

        .no-bookings i {
            font-size: 4rem;
            color: #ddd;
            margin-bottom: 1.5rem;
        }

        .no-bookings h3 {
            font-size: 1.8rem;
            color: var(--primary);
            margin-bottom: 1rem;
        }

        .no-bookings p {
            color: #666;
            font-size: 1.1rem;
            margin-bottom: 2rem;
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

            .booking-content {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .booking-image {
                height: 200px;
            }

            .booking-details {
                grid-template-columns: 1fr;
            }

            .bookings-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .bookings-stats {
                width: 100%;
                justify-content: space-between;
            }

            .page-title {
                font-size: 2rem;
            }

            .booking-footer {
                flex-direction: column;
                align-items: flex-start;
            }

            .booking-actions {
                width: 100%;
                justify-content: space-between;
            }
        }

        @media (max-width: 480px) {
            .page-title {
                font-size: 1.8rem;
            }

            .booking-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .bookings-stats {
                flex-direction: column;
                gap: 1rem;
            }

            .stat-card {
                width: 100%;
            }

            .breadcrumb {
                font-size: 0.9rem;
            }
        }

        .default-image {
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1rem;
            font-weight: 600;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7);
            background: linear-gradient(45deg, var(--secondary), var(--primary));
            height: 100%;
            padding: 1rem;
            text-align: center;
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
            <a href="contact.php">Contact</a>
            <a href="my-bookings.php" class="active">My Bookings</a>
            
            <?php if(isset($_SESSION['user_id'])): ?>
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
            <span>My Bookings</span>
        </div>
        <h1 class="page-title">My Bookings</h1>
        <p class="page-subtitle">Manage and view all your travel bookings in one place</p>
    </div>
</section>

<!-- Main Content -->
<main>
    <div class="bookings-container">
        <?php 
        $totalBookings = $res->num_rows;
        $confirmedBookings = 0;
        $pendingBookings = 0;
        
        // Count statuses
        if($totalBookings > 0) {
            $res->data_seek(0); // Reset pointer
            while($row = $res->fetch_assoc()) {
                if($row['status'] == 'confirmed') $confirmedBookings++;
                if($row['status'] == 'pending') $pendingBookings++;
            }
            $res->data_seek(0); // Reset pointer again for display
        }
        ?>

        <div class="bookings-header">
            <div class="bookings-stats">
                <div class="stat-card">
                    <div class="stat-number"><?php echo $totalBookings; ?></div>
                    <div class="stat-label">Total Bookings</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $confirmedBookings; ?></div>
                    <div class="stat-label">Confirmed</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $pendingBookings; ?></div>
                    <div class="stat-label">Pending</div>
                </div>
            </div>
        </div>

        <div class="bookings-grid">
        <?php if($res->num_rows > 0): ?>
            <?php while($row = $res->fetch_assoc()): 
                $statusClass = 'status-' . $row['status'];
            ?>
                <div class="booking-card">
                    <div class="booking-header">
                        <div>
                            <div class="booking-id">Booking #<?php echo $row['id']; ?></div>
                            <h3 class="booking-title"><?php echo htmlspecialchars($row['title']); ?></h3>
                        </div>
                        <span class="status-badge <?php echo $statusClass; ?>">
                            <?php echo ucfirst($row['status']); ?>
                        </span>
                    </div>
                    
                    <div class="booking-content">
                        <div class="booking-image">
                            <?php if($row['image'] != ""): ?>
                                <img src="uploads/packages/<?php echo $row['image']; ?>" alt="<?php echo htmlspecialchars($row['title']); ?>">
                            <?php else: ?>
                                <div class="default-image">
                                    <?php echo htmlspecialchars($row['title']); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="booking-details">
                            <div class="detail-item">
                                <span class="detail-label">Travel Date</span>
                                <span class="detail-value">
                                    <i class="far fa-calendar-alt"></i>
                                    <?php echo date('M j, Y', strtotime($row['travel_date'])); ?>
                                </span>
                            </div>
                            
                            <div class="detail-item">
                                <span class="detail-label">Travelers</span>
                                <span class="detail-value">
                                    <i class="fas fa-users"></i>
                                    <?php echo $row['travelers']; ?> Person<?php echo $row['travelers'] > 1 ? 's' : ''; ?>
                                </span>
                            </div>
                            
                            <div class="detail-item">
                                <span class="detail-label">Duration</span>
                                <span class="detail-value">
                                    <i class="far fa-clock"></i>
                                    <?php echo $row['days']; ?> Days
                                </span>
                            </div>
                            
                            <div class="detail-item">
                                <span class="detail-label">Total Amount</span>
                                <span class="detail-value">
                                    <i class="fas fa-rupee-sign"></i>
                                    ₹<?php echo number_format($row['price'] * $row['travelers'], 2); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="booking-footer">
                        <div class="booking-date">
                            <small>Booked on: <?php echo date('M j, Y g:i A', strtotime($row['created_at'])); ?></small>
                        </div>
                        <div class="booking-actions">
                            <a href="package-details.php?id=<?php echo $row['package_id']; ?>" class="btn-sm btn-info">
                                <i class="fas fa-eye"></i> View Package
                            </a>
                            <?php if($row['status'] == 'pending'): ?>
                                <a href="#" class="btn-sm btn-danger">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="no-bookings">
                <i class="fas fa-suitcase-rolling"></i>
                <h3>No Bookings Yet</h3>
                <p>You haven't made any bookings yet. Start exploring our amazing packages!</p>
                <a href="packages.php" class="btn btn-primary">
                    <i class="fas fa-map-marked-alt"></i> Explore Packages
                </a>
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