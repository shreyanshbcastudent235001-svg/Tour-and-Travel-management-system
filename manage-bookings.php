<?php
session_start();
include '../include/db.php';

// Admin login check
if(!isset($_SESSION['admin_id'])){
    header('Location: index.php');
    exit;
}

// ---- Update Status ----
if(isset($_POST['booking_id']) && isset($_POST['status'])){
    $id = (int)$_POST['booking_id'];
    $status = $conn->real_escape_string($_POST['status']);

    $conn->query("UPDATE bookings SET status='$status' WHERE id=$id");
    header("Location: manage-bookings.php");
    exit;
}

// ---- Fetch All Bookings ----
$res = $conn->query("
    SELECT b.*, u.name AS user_name, p.title AS package_title 
    FROM bookings b 
    JOIN users u ON u.id = b.user_id 
    JOIN packages p ON p.id = b.package_id 
    ORDER BY b.created_at DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings - Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
        }
        
        /* Table Styles */
        .table-container {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }
        
        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }
        
        .table-header h2 {
            color: var(--dark);
            font-size: 24px;
            font-weight: 600;
        }
        
        .stats-summary {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .stat-card {
            background: white;
            padding: 15px 20px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
            display: flex;
            align-items: center;
            gap: 10px;
            border-left: 4px solid var(--primary);
        }
        
        .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
        }
        
        .stat-info h3 {
            font-size: 14px;
            color: #6c757d;
            margin-bottom: 5px;
        }
        
        .stat-value {
            font-size: 22px;
            font-weight: 700;
            color: var(--dark);
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        th {
            background-color: #f8f9fa;
            padding: 15px 12px;
            text-align: left;
            font-weight: 600;
            color: #495057;
            border-bottom: 2px solid #e9ecef;
        }
        
        td {
            padding: 15px 12px;
            border-bottom: 1px solid #e9ecef;
            color: #495057;
        }
        
        tr:hover {
            background-color: #f8f9fa;
        }
        
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
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
        
        .action-form {
            display: flex;
            gap: 8px;
            align-items: center;
        }
        
        .status-select {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            background: white;
            min-width: 130px;
        }
        
        .update-btn {
            background: linear-gradient(to right, var(--primary), var(--secondary));
            color: white;
            border: none;
            border-radius: 6px;
            padding: 8px 15px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .update-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 3px 8px rgba(67, 97, 238, 0.3);
        }
        
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #6c757d;
        }
        
        .empty-state i {
            font-size: 50px;
            margin-bottom: 15px;
            color: #dee2e6;
        }
        
        /* Footer Styles */
        .footer {
            background-color: white;
            padding: 20px 30px;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
            border-top: 1px solid #eaeaea;
        }
        
        /* Responsive Design */
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
        }
        
        @media (max-width: 768px) {
            .table-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .stats-summary {
                flex-direction: column;
            }
            
            .table-container {
                padding: 15px;
                overflow-x: auto;
            }
            
            table {
                min-width: 800px;
            }
            
            .action-form {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .header {
                padding: 0 15px;
            }
            
            .content {
                padding: 20px 15px;
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
            <div class="menu-item">
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
            <div class="menu-item active">
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
                <h1>Manage Bookings</h1>
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
            <div class="table-container">
                <div class="table-header">
                    <h2>All Bookings</h2>
                </div>
                
                <?php if($res->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Package</th>
                            <th>Travel Date</th>
                            <th>Travelers</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($r = $res->fetch_assoc()): 
                            $statusClass = 'status-' . strtolower($r['status']);
                        ?>
                        <tr>
                            <td><?php echo $r['id']; ?></td>
                            <td><?php echo htmlspecialchars($r['user_name']); ?></td>
                            <td><?php echo htmlspecialchars($r['package_title']); ?></td>
                            <td><?php echo $r['travel_date']; ?></td>
                            <td><?php echo $r['travelers']; ?></td>
                            <td>
                                <span class="status-badge <?php echo $statusClass; ?>">
                                    <?php echo $r['status']; ?>
                                </span>
                            </td>
                            <td>
                                <form method="post" class="action-form">
                                    <input type="hidden" name="booking_id" value="<?php echo $r['id']; ?>">
                                    <select name="status" class="status-select" required>
                                        <option value="Pending"   <?php if($r['status']=="Pending") echo "selected"; ?>>Pending</option>
                                        <option value="Confirmed" <?php if($r['status']=="Confirmed") echo "selected"; ?>>Confirmed</option>
                                        <option value="Completed" <?php if($r['status']=="Completed") echo "selected"; ?>>Completed</option>
                                        <option value="Cancelled" <?php if($r['status']=="Cancelled") echo "selected"; ?>>Cancelled</option>
                                    </select>
                                    <button type="submit" class="update-btn">
                                        <i class="fas fa-sync-alt"></i>
                                        Update
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-calendar-times"></i>
                    <h3>No Bookings Found</h3>
                    <p>There are no bookings in the system yet.</p>
                </div>
                <?php endif; ?>
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
                    if (text === 'Dashboard') {
                        window.location.href = 'dashboard.php';
                    } else if (text === 'Add Package') {
                        window.location.href = 'add-package.php';
                    } else if (text === 'Manage Packages') {
                        window.location.href = 'manage-packages.php';
                    } else if (text === 'Manage Users') {
                        window.location.href = 'manage-users.php';
                    }
                });
            });
        });
    </script>
</body>
</html>