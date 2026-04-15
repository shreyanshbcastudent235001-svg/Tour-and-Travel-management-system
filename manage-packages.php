<?php
session_start();
include '../include/db.php';

// Admin login check
if(!isset($_SESSION['admin_id'])){
    header('Location: index.php');
    exit;
}

// ---- DELETE PACKAGE ----
if(isset($_GET['del']) && is_numeric($_GET['del'])){
    $id = (int)$_GET['del'];
    $conn->query("DELETE FROM packages WHERE id=$id");
    header("Location: manage-packages.php");
    exit;
}

// ---- FETCH ALL PACKAGES ----
$res = $conn->query("SELECT * FROM packages ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Packages - Admin Panel</title>
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
        
        .table-actions {
            display: flex;
            gap: 15px;
        }
        
        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
        }
        
        .btn-primary {
            background: linear-gradient(to right, var(--primary), var(--secondary));
            color: white;
            border: none;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.4);
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
        
        .action-buttons {
            display: flex;
            gap: 10px;
        }
        
        .btn-edit {
            background-color: #17a2b8;
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: all 0.3s;
        }
        
        .btn-edit:hover {
            background-color: #138496;
            transform: translateY(-2px);
        }
        
        .btn-delete {
            background-color: #dc3545;
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: all 0.3s;
        }
        
        .btn-delete:hover {
            background-color: #c82333;
            transform: translateY(-2px);
        }
        
        .price-tag {
            font-weight: 600;
            color: #28a745;
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
            
            .table-actions {
                width: 100%;
                justify-content: space-between;
            }
            
            .table-container {
                padding: 15px;
                overflow-x: auto;
            }
            
            table {
                min-width: 700px;
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
            <div class="menu-item active">
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
                <h1>Manage Packages</h1>
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
                    <h2>All Travel Packages</h2>
                    <div class="table-actions">
                        <a href="add-package.php" class="btn btn-primary">
                            <i class="fas fa-plus-circle"></i>
                            Add New Package
                        </a>
                    </div>
                </div>
                
                <?php if($res->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Price</th>
                            <th>Days</th>
                            <th>Nights</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($p = $res->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $p['id']; ?></td>
                            <td><?php echo htmlspecialchars($p['title']); ?></td>
                            <td class="price-tag">₹<?php echo number_format($p['price'], 2); ?></td>
                            <td><?php echo $p['days']; ?></td>
                            <td><?php echo $p['nights']; ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="edit-package.php?id=<?php echo $p['id']; ?>" class="btn-edit">
                                        <i class="fas fa-edit"></i>
                                        Edit
                                    </a>
                                    <a href="manage-packages.php?del=<?php echo $p['id']; ?>" 
                                       class="btn-delete"
                                       onclick="return confirm('Are you sure you want to delete this package?');">
                                        <i class="fas fa-trash-alt"></i>
                                        Delete
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-box-open"></i>
                    <h3>No Packages Found</h3>
                    <p>There are no travel packages in the system yet.</p>
                    <a href="add-package.php" class="btn btn-primary" style="margin-top: 15px;">
                        <i class="fas fa-plus-circle"></i>
                        Add Your First Package
                    </a>
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
                    } else if (text === 'Manage Bookings') {
                        window.location.href = 'manage-bookings.php';
                    } else if (text === 'Manage Users') {
                        window.location.href = 'manage-users.php';
                    }
                });
            });
        });
    </script>
</body>
</html>