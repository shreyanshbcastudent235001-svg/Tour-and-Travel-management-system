<?php
session_start();
include '../include/db.php';

if(!isset($_SESSION['admin_id'])){
    header("Location: index.php");
    exit;
}

if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
    header("Location: manage-packages.php");
    exit;
}

$id = (int)$_GET['id'];
$res = $conn->query("SELECT * FROM packages WHERE id=$id");
if($res->num_rows == 0){
    echo "Package not found!";
    exit;
}

$pkg = $res->fetch_assoc();
$msg = "";

// UPDATE
if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $title  = $conn->real_escape_string($_POST['title']);
    $price  = (float)$_POST['price'];
    $days   = (int)$_POST['days'];
    $nights = (int)$_POST['nights'];
    $desc   = $conn->real_escape_string($_POST['description']);
    $includes = $conn->real_escape_string($_POST['includes']);
    $excludes = $conn->real_escape_string($_POST['excludes']);
    $itinerary = $conn->real_escape_string($_POST['itinerary']);

    // Image Upload
    $image_name = $pkg['image'];

    if(!empty($_FILES['image']['name'])){
        $image_name = time() . "_" . basename($_FILES['image']['name']);
        $target_path = dirname(__DIR__) . "/uploads/packages/" . $image_name;

        if(move_uploaded_file($_FILES['image']['tmp_name'], $target_path)){
            if($pkg['image'] != "" && file_exists(dirname(__DIR__) . "/uploads/packages/" . $pkg['image'])){
                unlink(dirname(__DIR__) . "/uploads/packages/" . $pkg['image']);
            }
        }
    }

    $conn->query("
        UPDATE packages SET
        title='$title',
        price=$price,
        days=$days,
        nights=$nights,
        description='$desc',
        includes='$includes',
        excludes='$excludes',
        itinerary='$itinerary',
        image='$image_name'
        WHERE id=$id
    ");

    $msg = "Package Updated Successfully!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Package - Admin Panel</title>
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
        
        /* Form Styles */
        .form-container {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            max-width: 900px;
        }
        
        .form-header {
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eaeaea;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .form-header h2 {
            color: var(--dark);
            font-size: 24px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .back-btn:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }
        
        .success-message {
            background-color: #e8f5e9;
            color: #2e7d32;
            padding: 12px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
            border-left: 4px solid #4caf50;
        }
        
        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .form-group {
            flex: 1;
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #444;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
        }
        
        textarea.form-control {
            min-height: 120px;
            resize: vertical;
            line-height: 1.5;
        }
        
        .image-preview {
            margin-top: 10px;
            border-radius: 8px;
            overflow: hidden;
            border: 2px dashed #ddd;
            padding: 15px;
            text-align: center;
        }
        
        .image-preview img {
            max-width: 100%;
            height: auto;
            border-radius: 6px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }
        
        .no-image {
            color: #6c757d;
            font-style: italic;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 6px;
        }
        
        .file-upload {
            position: relative;
            display: inline-block;
            width: 100%;
        }
        
        .file-upload-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 25px;
            border: 2px dashed #ddd;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            text-align: center;
            background: #fafafa;
        }
        
        .file-upload-label:hover {
            border-color: var(--primary);
            background-color: rgba(67, 97, 238, 0.05);
        }
        
        .file-upload-label i {
            font-size: 35px;
            color: #aaa;
            margin-bottom: 10px;
        }
        
        .file-upload-label span {
            color: #666;
            font-weight: 500;
        }
        
        .file-input {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }
        
        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eaeaea;
        }
        
        .submit-btn {
            background: linear-gradient(to right, var(--primary), var(--secondary));
            color: white;
            border: none;
            border-radius: 8px;
            padding: 15px 30px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.4);
        }
        
        .cancel-btn {
            background: #6c757d;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 15px 30px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }
        
        .cancel-btn:hover {
            background: #5a6268;
            transform: translateY(-2px);
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
            .form-row {
                flex-direction: column;
                gap: 0;
            }
            
            .form-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .form-actions {
                flex-direction: column;
            }
            
            .header {
                padding: 0 15px;
            }
            
            .content {
                padding: 20px 15px;
            }
            
            .form-container {
                padding: 20px;
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
                <h1>Edit Package</h1>
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
            <div class="form-container">
                <div class="form-header">
                    <h2>
                        <i class="fas fa-edit"></i>
                        Edit Package: <?php echo htmlspecialchars($pkg['title']); ?>
                    </h2>
                    <a href="manage-packages.php" class="back-btn">
                        <i class="fas fa-arrow-left"></i>
                        Back to Packages
                    </a>
                </div>
                
                <?php if($msg): ?>
                    <div class="success-message">
                        <i class="fas fa-check-circle"></i>
                        <?php echo $msg; ?>
                    </div>
                <?php endif; ?>
                
                <form method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="title">Package Title</label>
                        <input type="text" id="title" name="title" class="form-control" 
                               value="<?php echo htmlspecialchars($pkg['title']); ?>" 
                               placeholder="Enter package title" required>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="price">Price (₹)</label>
                            <input type="number" id="price" name="price" class="form-control" 
                                   value="<?php echo $pkg['price']; ?>" 
                                   placeholder="0.00" step="0.01" min="0" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="days">Days</label>
                            <input type="number" id="days" name="days" class="form-control" 
                                   value="<?php echo $pkg['days']; ?>" 
                                   placeholder="Number of days" min="1" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="nights">Nights</label>
                            <input type="number" id="nights" name="nights" class="form-control" 
                                   value="<?php echo $pkg['nights']; ?>" 
                                   placeholder="Number of nights" min="1" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" class="form-control" 
                                  placeholder="Describe the package..."><?php echo htmlspecialchars($pkg['description']); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="includes">What's Included</label>
                        <textarea id="includes" name="includes" class="form-control" 
                                  placeholder="List what is included in the package..."><?php echo htmlspecialchars($pkg['includes']); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="excludes">What's Not Included</label>
                        <textarea id="excludes" name="excludes" class="form-control" 
                                  placeholder="List what is not included in the package..."><?php echo htmlspecialchars($pkg['excludes']); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="itinerary">Full Itinerary</label>
                        <textarea id="itinerary" name="itinerary" class="form-control" 
                                  placeholder="Write full day-wise itinerary..."><?php echo htmlspecialchars($pkg['itinerary']); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Current Image</label>
                        <div class="image-preview">
                            <?php if($pkg['image'] != ""): ?>
                                <img src="../uploads/packages/<?php echo $pkg['image']; ?>" 
                                     alt="<?php echo htmlspecialchars($pkg['title']); ?>">
                            <?php else: ?>
                                <div class="no-image">
                                    <i class="fas fa-image" style="font-size: 2rem; margin-bottom: 10px;"></i>
                                    <p>No image uploaded for this package</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Upload New Image (Optional)</label>
                        <div class="file-upload">
                            <label class="file-upload-label">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <span>Click to upload new package image</span>
                                <small style="color: #999; margin-top: 5px;">Leave empty to keep current image</small>
                            </label>
                            <input type="file" name="image" class="file-input" accept="image/*">
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="submit-btn">
                            <i class="fas fa-save"></i>
                            Update Package
                        </button>
                        <a href="manage-packages.php" class="cancel-btn">
                            <i class="fas fa-times"></i>
                            Cancel
                        </a>
                    </div>
                </form>
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
            
            // File upload preview
            const fileInput = document.querySelector('.file-input');
            const fileUploadLabel = document.querySelector('.file-upload-label');
            
            fileInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const fileName = this.files[0].name;
                    fileUploadLabel.innerHTML = `
                        <i class="fas fa-file-image"></i>
                        <span>${fileName}</span>
                        <small style="color: #28a745; margin-top: 5px;">New image selected</small>
                    `;
                }
            });
        });
    </script>
</body>
</html>