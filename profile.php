<?php
session_start();
include 'include/db.php';
if(!isset($_SESSION['user_id'])){ header('Location: login.php'); exit; }
$uid = (int)$_SESSION['user_id'];
$res = $conn->query("SELECT * FROM users WHERE id={$uid}");
$u = $res->fetch_assoc();
$msg = '';
if($_SERVER['REQUEST_METHOD']=='POST'){
    $name = $conn->real_escape_string($_POST['name']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $conn->query("UPDATE users SET name='{$name}', phone='{$phone}' WHERE id={$uid}");
    $msg = 'Profile updated successfully!';
    $_SESSION['user_name'] = $name;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset='utf-8'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Tour & Travel</title>
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

        .profile-container {
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 3rem;
            margin-bottom: 3rem;
        }

        .profile-sidebar {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: var(--shadow);
            height: fit-content;
            text-align: center;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, var(--secondary), var(--primary));
            border-radius: 50%;
            margin: 0 auto 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2.5rem;
            border: 4px solid white;
            box-shadow: var(--shadow);
        }

        .profile-name {
            font-size: 1.4rem;
            color: var(--primary);
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .profile-email {
            color: #666;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }

        .profile-stats {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-top: 2rem;
        }

        .stat-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.8rem 0;
            border-bottom: 1px solid #eee;
        }

        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }

        .stat-value {
            font-weight: 600;
            color: var(--primary);
        }

        .profile-form-container {
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
            display: flex;
            align-items: center;
            gap: 10px;
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

        .btn-form {
            padding: 1rem 2rem;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
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

        .btn-outline-form {
            background: transparent;
            color: var(--primary);
            border: 2px solid var(--primary);
            text-decoration: none;
        }

        .btn-outline-form:hover {
            background: var(--primary);
            color: white;
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

        .account-actions {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1.5rem;
            margin-top: 2rem;
            border-left: 4px solid var(--secondary);
        }

        .account-actions h4 {
            color: var(--primary);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
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

            .profile-container {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .profile-sidebar {
                order: 2;
            }

            .profile-form-container {
                order: 1;
            }

            .page-title {
                font-size: 2rem;
            }

            .form-title {
                font-size: 1.5rem;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn-form {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .page-title {
                font-size: 1.8rem;
            }

            .profile-form-container, .profile-sidebar {
                padding: 1.5rem;
            }

            .breadcrumb {
                font-size: 0.9rem;
            }

            .profile-avatar {
                width: 100px;
                height: 100px;
                font-size: 2rem;
            }
        }

        .user-welcome {
            color: var(--light);
            font-size: 0.9rem;
            margin-right: 1rem;
        }

        .member-since {
            background: linear-gradient(135deg, var(--secondary), var(--primary));
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            margin-top: 1rem;
            display: inline-block;
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
            
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href='my-bookings.php'>My Bookings</a>
                <a href='profile.php' class="active">Profile</a>
                <div class="auth-buttons">
                    <span class="user-welcome">Welcome, <?php echo htmlspecialchars($u['name']); ?>!</span>
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
            <span>My Profile</span>
        </div>
        <h1 class="page-title">My Profile</h1>
        <p class="page-subtitle">Manage your personal information and account settings</p>
    </div>
</section>

<!-- Main Content -->
<main>
    <?php if($msg): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <div>
                <strong>Success!</strong> <?php echo htmlspecialchars($msg); ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="profile-container">
        <!-- Profile Sidebar -->
        <div class="profile-sidebar">
            <div class="profile-avatar">
                <i class="fas fa-user"></i>
            </div>
            
            <h2 class="profile-name"><?php echo htmlspecialchars($u['name']); ?></h2>
            <p class="profile-email"><?php echo htmlspecialchars($u['email']); ?></p>
            
            <div class="member-since">
                <i class="fas fa-star"></i> Member since <?php echo date('M Y', strtotime($u['created_at'])); ?>
            </div>

            <div class="profile-stats">
                <?php
                // Get booking stats
                $bookingRes = $conn->query("SELECT COUNT(*) as total_bookings, 
                                           SUM(travelers) as total_travelers 
                                           FROM bookings WHERE user_id={$uid}");
                $stats = $bookingRes->fetch_assoc();
                ?>
                <div class="stat-item">
                    <span class="stat-label">Total Bookings</span>
                    <span class="stat-value"><?php echo $stats['total_bookings']; ?></span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Travelers Booked</span>
                    <span class="stat-value"><?php echo $stats['total_travelers'] ?: '0'; ?></span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Member Status</span>
                    <span class="stat-value" style="color: var(--success);">Active</span>
                </div>
            </div>
        </div>

        <!-- Profile Form -->
        <div class="profile-form-container">
            <h2 class="form-title">
                <i class="fas fa-user-edit"></i> Edit Profile
            </h2>
            
            <form method='post'>
                <div class="form-group">
                    <label for="name">
                        <i class="fas fa-user"></i> Full Name
                    </label>
                    <input type="text" name='name' id='name' class="form-control" 
                           value='<?php echo htmlspecialchars($u['name']); ?>' required>
                </div>

                <div class="form-group">
                    <label for="email">
                        <i class="fas fa-envelope"></i> Email Address
                    </label>
                    <input type="email" class="form-control" value='<?php echo htmlspecialchars($u['email']); ?>' 
                           disabled style="background: #e9ecef; cursor: not-allowed;">
                    <small style="color: #666; margin-top: 0.5rem; display: block;">
                        <i class="fas fa-info-circle"></i> Email address cannot be changed
                    </small>
                </div>

                <div class="form-group">
                    <label for="phone">
                        <i class="fas fa-phone"></i> Phone Number
                    </label>
                    <input type="tel" name='phone' id='phone' class="form-control" 
                           value='<?php echo htmlspecialchars($u['phone']); ?>' 
                           placeholder="Enter your phone number">
                </div>

                <button type='submit' class="btn-form btn-primary-form">
                    <i class="fas fa-save"></i> Save Changes
                </button>
                
                <a href="index.php" class="btn-form btn-outline-form">
                    <i class="fas fa-arrow-left"></i> Back to Home
                </a>
            </form>

            <div class="account-actions">
                <h4><i class="fas fa-cog"></i> Account Actions</h4>
                <div class="action-buttons">
                    <a href="my-bookings.php" class="btn-form btn-outline-form" style="flex: 1;">
                        <i class="fas fa-suitcase"></i> View My Bookings
                    </a>
                    <a href="change-password.php" class="btn-form btn-outline-form" style="flex: 1;">
                        <i class="fas fa-lock"></i> Change Password
                    </a>
                </div>
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