<?php
session_start();
include '../include/db.php';

// Admin Login Check
if(!isset($_SESSION['admin_id'])){
    header("Location: index.php");
    exit;
}

// Fetch counts
$packages = $conn->query("SELECT COUNT(*) AS total FROM packages")->fetch_assoc()['total'];
$bookings = $conn->query("SELECT COUNT(*) AS total FROM bookings")->fetch_assoc()['total'];
$users    = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];

// Fetch booking status counts
$bookingStats = $conn->query("
    SELECT status, COUNT(*) as count 
    FROM bookings 
    GROUP BY status
")->fetch_all(MYSQLI_ASSOC);

// Fetch recent bookings
$recentBookings = $conn->query("
    SELECT b.*, u.name as user_name, p.title as package_title 
    FROM bookings b 
    JOIN users u ON u.id = b.user_id 
    JOIN packages p ON p.id = b.package_id 
    ORDER BY b.created_at DESC 
    LIMIT 5
")->fetch_all(MYSQLI_ASSOC);

// Fetch monthly booking trends (last 6 months)
$monthlyBookings = $conn->query("
    SELECT 
        DATE_FORMAT(created_at, '%Y-%m') as month,
        COUNT(*) as count
    FROM bookings 
    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
    GROUP BY DATE_FORMAT(created_at, '%Y-%m')
    ORDER BY month
")->fetch_all(MYSQLI_ASSOC);

// Fetch popular packages
$popularPackages = $conn->query("
    SELECT p.title, COUNT(b.id) as booking_count
    FROM packages p 
    LEFT JOIN bookings b ON p.id = b.package_id 
    GROUP BY p.id, p.title 
    ORDER BY booking_count DESC 
    LIMIT 5
")->fetch_all(MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --success: #4cc9f0;
            --info: #4895ef;
            --warning: #f72585;
            --light: #f8f9fa;
            --dark: #212529;
            --sidebar-width: 250px;
            --header-height: 70px;
            --footer-height: 60px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f7fb;
            color: #333;
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--primary), var(--secondary));
            color: white;
            height: 100vh;
            position: fixed;
            overflow-y: auto;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            z-index: 100;
        }
        
        .sidebar-header {
            padding: 25px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-header h2 {
            font-size: 22px;
            font-weight: 600;
        }
        
        .sidebar-menu {
            padding: 20px 0;
        }
        
        .menu-item {
            padding: 14px 25px;
            display: flex;
            align-items: center;
            transition: all 0.3s;
            cursor: pointer;
            border-left: 4px solid transparent;
        }
        
        .menu-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
            border-left: 4px solid var(--success);
        }
        
        .menu-item.active {
            background-color: rgba(255, 255, 255, 0.15);
            border-left: 4px solid var(--success);
        }
        
        .menu-item i {
            margin-right: 12px;
            font-size: 18px;
            width: 24px;
            text-align: center;
        }
        
        .menu-item.logout {
            margin-top: 20px;
            color: #ff6b6b;
        }
        
        /* Main Content Styles */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        .header {
            height: var(--header-height);
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            position: sticky;
            top: 0;
            z-index: 99;
        }
        
        .header-left h1 {
            font-size: 24px;
            color: var(--dark);
            font-weight: 600;
        }
        
        .header-right {
            display: flex;
            align-items: center;
        }
        
        .admin-profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .admin-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }
        
        .content {
            flex: 1;
            padding: 30px;
            display: flex;
            flex-direction: column;
            gap: 30px;
        }
        
        /* Dashboard Cards */
        .stats-section {
            width: 100%;
        }
        
        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            width: 100%;
        }
        
        .card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s, box-shadow 0.3s;
            border-top: 4px solid var(--primary);
            height: 100%;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }
        
        .card.packages {
            border-top-color: var(--primary);
        }
        
        .card.bookings {
            border-top-color: var(--info);
        }
        
        .card.users {
            border-top-color: var(--warning);
        }
        
        .card.revenue {
            border-top-color: #4caf50;
        }
        
        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        
        .card-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            color: white;
        }
        
        .card.packages .card-icon {
            background-color: var(--primary);
        }
        
        .card.bookings .card-icon {
            background-color: var(--info);
        }
        
        .card.users .card-icon {
            background-color: var(--warning);
        }
        
        .card.revenue .card-icon {
            background-color: #4caf50;
        }
        
        .card h3 {
            font-size: 16px;
            color: #6c757d;
            font-weight: 500;
            margin-bottom: 5px;
        }
        
        .card-value {
            font-size: 32px;
            font-weight: 700;
            color: var(--dark);
        }
        
        .card-footer {
            margin-top: 15px;
            font-size: 14px;
            color: #6c757d;
            display: flex;
            align-items: center;
        }
        
        .card-footer i {
            margin-right: 5px;
        }
        
        /* Charts Section */
        .charts-section {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 25px;
            width: 100%;
        }
        
        .chart-container {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            width: 100%;
        }
        
        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .chart-header h3 {
            font-size: 18px;
            color: var(--dark);
            font-weight: 600;
        }
        
        .chart-wrapper {
            position: relative;
            height: 300px;
            width: 100%;
        }
        
        /* Recent Activity */
        .activity-container {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            width: 100%;
        }
        
        .activity-list {
            margin-top: 15px;
        }
        
        .activity-item {
            display: flex;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .activity-item:last-child {
            border-bottom: none;
        }
        
        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: white;
            font-size: 16px;
            flex-shrink: 0;
        }
        
        .activity-icon.booking {
            background-color: var(--info);
        }
        
        .activity-icon.user {
            background-color: var(--warning);
        }
        
        .activity-icon.package {
            background-color: var(--primary);
        }
        
        .activity-details {
            flex: 1;
            min-width: 0;
        }
        
        .activity-title {
            font-weight: 600;
            margin-bottom: 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .activity-time {
            font-size: 12px;
            color: #6c757d;
        }
        
        .activity-status {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            flex-shrink: 0;
            margin-left: 10px;
        }
        
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .status-confirmed {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        
        .status-completed {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        /* Popular Packages */
        .popular-packages {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            width: 100%;
        }
        
        .package-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .package-item:last-child {
            border-bottom: none;
        }
        
        .package-name {
            font-weight: 500;
            flex: 1;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-right: 10px;
        }
        
        .package-count {
            background-color: var(--primary);
            color: white;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            flex-shrink: 0;
        }
        
        /* Analytics Section */
        .analytics-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
            width: 100%;
        }
        
        /* Footer Styles */
        .footer {
            background-color: white;
            padding: 20px 30px;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
            border-top: 1px solid #eaeaea;
            width: 100%;
            margin-top: auto;
        }
        
        /* Responsive Design */
        @media (max-width: 1200px) {
            .charts-section {
                grid-template-columns: 1fr;
            }
            
            .analytics-section {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 992px) {
            .sidebar {
                width: 70px;
                overflow: visible;
            }
            
            .sidebar-header h2, .menu-item span {
                display: none;
            }
            
            .menu-item {
                justify-content: center;
                padding: 18px 0;
            }
            
            .menu-item i {
                margin-right: 0;
                font-size: 20px;
            }
            
            .main-content {
                margin-left: 70px;
            }
            
            .dashboard-cards {
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            }
        }
        
        @media (max-width: 768px) {
            .dashboard-cards {
                grid-template-columns: 1fr;
            }
            
            .header {
                padding: 0 15px;
            }
            
            .content {
                padding: 20px 15px;
                gap: 20px;
            }
            
            .chart-wrapper {
                height: 250px;
            }
        }
        
        @media (max-width: 480px) {
            .activity-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            
            .activity-status {
                margin-left: 0;
                align-self: flex-start;
            }
            
            .package-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 5px;
            }
            
            .package-count {
                align-self: flex-start;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h2>Admin Panel</h2>
        </div>
        
        <div class="sidebar-menu">
            <div class="menu-item active">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </div>
            <div class="menu-item">
                <i class="fas fa-plus-circle"></i>
                <span>Add Package</span>
            </div>
            <div class="menu-item">
                <i class="fas fa-box-open"></i>
                <span>Manage Packages</span>
            </div>
            <div class="menu-item">
                <i class="fas fa-calendar-check"></i>
                <span>Manage Bookings</span>
            </div>
            <div class="menu-item">
                <i class="fas fa-users"></i>
                <span>Manage Users</span>
            </div>
            <div class="menu-item logout">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </div>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="header">
            <div class="header-left">
                <h1>Dashboard Overview</h1>
            </div>
            <div class="header-right">
                <div class="admin-profile">
                    <div class="admin-avatar">A</div>
                    <span>Administrator</span>
                </div>
            </div>
        </div>
        
        <!-- Content -->
        <div class="content">
            <!-- Stats Cards Section -->
            <div class="stats-section">
                <div class="dashboard-cards">
                    <div class="card packages">
                        <div class="card-header">
                            <h3>Total Packages</h3>
                            <div class="card-icon">
                                <i class="fas fa-box"></i>
                            </div>
                        </div>
                        <div class="card-value"><?php echo $packages; ?></div>
                        <div class="card-footer">
                            <i class="fas fa-info-circle"></i>
                            All active travel packages
                        </div>
                    </div>
                    
                    <div class="card bookings">
                        <div class="card-header">
                            <h3>Total Bookings</h3>
                            <div class="card-icon">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                        </div>
                        <div class="card-value"><?php echo $bookings; ?></div>
                        <div class="card-footer">
                            <i class="fas fa-info-circle"></i>
                            All confirmed bookings
                        </div>
                    </div>
                    
                    <div class="card users">
                        <div class="card-header">
                            <h3>Total Users</h3>
                            <div class="card-icon">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                        <div class="card-value"><?php echo $users; ?></div>
                        <div class="card-footer">
                            <i class="fas fa-info-circle"></i>
                            All registered users
                        </div>
                    </div>
                    
                    <div class="card revenue">
                        <div class="card-header">
                            <h3>Revenue</h3>
                            <div class="card-icon">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                        </div>
                        <div class="card-value">₹<?php 
                            $revenue = $conn->query("
                                SELECT SUM(p.price) as total 
                                FROM bookings b 
                                JOIN packages p ON p.id = b.package_id 
                                WHERE b.status = 'Confirmed' OR b.status = 'Completed'
                            ")->fetch_assoc()['total'];
                            echo number_format($revenue ? $revenue : 0, 2);
                        ?></div>
                        <div class="card-footer">
                            <i class="fas fa-chart-line"></i>
                            Total confirmed bookings revenue
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Charts Section -->
            <div class="charts-section">
                <!-- Booking Trends Chart -->
                <div class="chart-container">
                    <div class="chart-header">
                        <h3>Booking Trends (Last 6 Months)</h3>
                    </div>
                    <div class="chart-wrapper">
                        <canvas id="bookingTrendsChart"></canvas>
                    </div>
                </div>
                
                <!-- Booking Status Chart -->
                <div class="chart-container">
                    <div class="chart-header">
                        <h3>Booking Status</h3>
                    </div>
                    <div class="chart-wrapper">
                        <canvas id="bookingStatusChart"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Analytics Section -->
            <div class="analytics-section">
                <!-- Recent Activity -->
                <div class="activity-container">
                    <div class="chart-header">
                        <h3>Recent Bookings</h3>
                    </div>
                    <div class="activity-list">
                        <?php if(count($recentBookings) > 0): ?>
                            <?php foreach($recentBookings as $booking): ?>
                            <div class="activity-item">
                                <div class="activity-icon booking">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                <div class="activity-details">
                                    <div class="activity-title"><?php echo htmlspecialchars($booking['user_name']); ?> booked <?php echo htmlspecialchars($booking['package_title']); ?></div>
                                    <div class="activity-time"><?php echo date('M j, Y', strtotime($booking['created_at'])); ?></div>
                                </div>
                                <div class="activity-status status-<?php echo strtolower($booking['status']); ?>">
                                    <?php echo $booking['status']; ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p style="text-align: center; color: #6c757d; padding: 20px;">No recent bookings</p>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Popular Packages -->
                <div class="popular-packages">
                    <div class="chart-header">
                        <h3>Popular Packages</h3>
                    </div>
                    <div class="activity-list">
                        <?php if(count($popularPackages) > 0): ?>
                            <?php foreach($popularPackages as $package): ?>
                            <div class="package-item">
                                <div class="package-name"><?php echo htmlspecialchars($package['title']); ?></div>
                                <div class="package-count"><?php echo $package['booking_count']; ?> bookings</div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p style="text-align: center; color: #6c757d; padding: 20px;">No package bookings yet</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p>&copy; <?php echo date('Y'); ?> Travel Booking System. All rights reserved.</p>
        </div>
    </div>

    <script>
        // Add interactivity to menu items
        document.addEventListener('DOMContentLoaded', function() {
            const menuItems = document.querySelectorAll('.menu-item');
            
            menuItems.forEach(item => {
                item.addEventListener('click', function() {
                    // Remove active class from all items
                    menuItems.forEach(i => i.classList.remove('active'));
                    
                    // Add active class to clicked item
                    this.classList.add('active');
                    
                    // Handle logout click
                    if (this.classList.contains('logout')) {
                        window.location.href = 'logout.php';
                    }
                    
                    // Handle navigation
                    const text = this.querySelector('span').textContent;
                    if (text === 'Add Package') {
                        window.location.href = 'add-package.php';
                    } else if (text === 'Manage Packages') {
                        window.location.href = 'manage-packages.php';
                    } else if (text === 'Manage Bookings') {
                        window.location.href = 'manage-bookings.php';
                    } else if (text === 'Manage Users') {
                        window.location.href = 'manage-users.php';
                    }
                });
            });

            // Initialize Charts
            // Booking Trends Chart
            const trendsCtx = document.getElementById('bookingTrendsChart').getContext('2d');
            const trendsChart = new Chart(trendsCtx, {
                type: 'line',
                data: {
                    labels: [
                        <?php 
                        $months = [];
                        foreach($monthlyBookings as $mb) {
                            $months[] = "'" . date('M Y', strtotime($mb['month'] . '-01')) . "'";
                        }
                        echo implode(', ', $months);
                        ?>
                    ],
                    datasets: [{
                        label: 'Bookings',
                        data: [
                            <?php 
                            $counts = [];
                            foreach($monthlyBookings as $mb) {
                                $counts[] = $mb['count'];
                            }
                            echo implode(', ', $counts);
                            ?>
                        ],
                        borderColor: '#4361ee',
                        backgroundColor: 'rgba(67, 97, 238, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                drawBorder: false
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            // Booking Status Chart
            const statusCtx = document.getElementById('bookingStatusChart').getContext('2d');
            const statusChart = new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: [
                        <?php 
                        $statusLabels = [];
                        foreach($bookingStats as $stat) {
                            $statusLabels[] = "'" . $stat['status'] . "'";
                        }
                        echo implode(', ', $statusLabels);
                        ?>
                    ],
                    datasets: [{
                        data: [
                            <?php 
                            $statusData = [];
                            foreach($bookingStats as $stat) {
                                $statusData[] = $stat['count'];
                            }
                            echo implode(', ', $statusData);
                            ?>
                        ],
                        backgroundColor: [
                            '#ffc107', // Pending
                            '#17a2b8', // Confirmed
                            '#28a745', // Completed
                            '#dc3545'  // Cancelled
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    },
                    cutout: '70%'
                }
            });
        });
    </script>
</body>
</html>