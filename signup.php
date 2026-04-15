<?php
session_start();
include 'include/db.php';
$msg = '';
if($_SERVER['REQUEST_METHOD']=='POST'){
    $name = $conn->real_escape_string(trim($_POST['name']));
    $email = $conn->real_escape_string(trim($_POST['email']));
    $phone = $conn->real_escape_string(trim($_POST['phone']));
    $password = $_POST['password'];
    if($name==''||$email==''||$password==''){ $msg='Please fill required fields'; }
    else{
        $exists = $conn->query("SELECT id FROM users WHERE email='{$email}'");
        if($exists->num_rows>0){ $msg='Email already registered'; }
        else{
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $conn->query("INSERT INTO users (name,email,phone,password) VALUES ('{$name}','{$email}','{$phone}','{$hash}')");
            header('Location: login.php?registered=1'); exit;
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset='utf-8'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - Tour & Travel</title>
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

        /* Main Content */
        .main-content {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 20px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
        }

        .signup-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            width: 100%;
            max-width: 500px;
            position: relative;
        }

        .signup-header {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 2.5rem 2rem;
            text-align: center;
        }

        .signup-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 1rem;
        }

        .signup-logo i {
            font-size: 2.5rem;
        }

        .signup-logo h2 {
            font-size: 1.8rem;
            font-weight: 700;
        }

        .signup-header p {
            opacity: 0.9;
            font-size: 1.1rem;
        }

        .signup-form {
            padding: 2.5rem 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
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
            padding: 1rem 1rem 1rem 2.5rem;
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

        .input-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            transition: var(--transition);
        }

        .form-control:focus + .input-icon {
            color: var(--secondary);
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

        .form-footer {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e1e5e9;
        }

        .form-footer p {
            color: #666;
        }

        .form-footer a {
            color: var(--secondary);
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
        }

        .form-footer a:hover {
            color: var(--primary);
            text-decoration: underline;
        }

        .alert {
            padding: 1rem 1.5rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            border-left: 4px solid;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alert-error {
            background-color: #f8d7da;
            border-color: var(--accent);
            color: #721c24;
        }

        .alert-error i {
            color: var(--accent);
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #6c757d;
            cursor: pointer;
            transition: var(--transition);
        }

        .password-toggle:hover {
            color: var(--secondary);
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

            .auth-buttons {
                justify-content: center;
                width: 100%;
            }

            .signup-container {
                margin: 10px;
            }

            .signup-header {
                padding: 2rem 1.5rem;
            }

            .signup-form {
                padding: 2rem 1.5rem;
            }

            .signup-logo {
                flex-direction: column;
                gap: 8px;
            }

            .signup-logo h2 {
                font-size: 1.6rem;
            }

            .footer-links {
                flex-direction: column;
                gap: 1rem;
            }
        }

        @media (max-width: 480px) {
            .signup-header {
                padding: 1.5rem 1rem;
            }

            .signup-form {
                padding: 1.5rem 1rem;
            }

            .form-control {
                padding: 0.8rem 0.8rem 0.8rem 2.2rem;
            }
        }

        .user-welcome {
            color: var(--light);
            font-size: 0.9rem;
            margin-right: 1rem;
        }

        .required::after {
            content: " *";
            color: var(--accent);
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

<!-- Main Content -->
<div class="main-content">
    <div class="signup-container">
        <div class="signup-header">
            <div class="signup-logo">
                <i class="fas fa-user-plus"></i>
                <h2>Create Account</h2>
            </div>
            <p>Join us and start your travel journey today</p>
        </div>

        <div class="signup-form">
            <!-- Error Message -->
            <?php if($msg): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <div><?php echo htmlspecialchars($msg); ?></div>
                </div>
            <?php endif; ?>

            <form method='post'>
                <div class="form-group">
                    <label for="name" class="required">
                        <i class="fas fa-user"></i> Full Name
                    </label>
                    <input type="text" name='name' id='name' class="form-control" 
                           placeholder="Enter your full name" 
                           value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" 
                           required>
                    <i class="fas fa-user input-icon"></i>
                </div>

                <div class="form-group">
                    <label for="email" class="required">
                        <i class="fas fa-envelope"></i> Email Address
                    </label>
                    <input type='email' name='email' id='email' class="form-control" 
                           placeholder="Enter your email address" 
                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
                           required>
                    <i class="fas fa-envelope input-icon"></i>
                </div>

                <div class="form-group">
                    <label for="phone">
                        <i class="fas fa-phone"></i> Phone Number
                    </label>
                    <input type='tel' name='phone' id='phone' class="form-control" 
                           placeholder="Enter your phone number" 
                           value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                    <i class="fas fa-phone input-icon"></i>
                </div>

                <div class="form-group">
                    <label for="password" class="required">
                        <i class="fas fa-lock"></i> Password
                    </label>
                    <input type='password' name='password' id='password' class="form-control" 
                           placeholder="Create a strong password" required>
                    <i class="fas fa-lock input-icon"></i>
                    <button type="button" class="password-toggle" onclick="togglePassword()">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>

                <button type='submit' class="btn-form btn-primary-form">
                    <i class="fas fa-user-plus"></i> Create Account
                </button>
            </form>

            <div class="form-note">
                <i class="fas fa-info-circle"></i>
                Your password must be at least 8 characters long and include a mix of letters, numbers, and symbols.
            </div>

            <div class="form-footer">
                <p>Already have an account? <a href='login.php'>Login here</a></p>
            </div>
        </div>
    </div>
</div>

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
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.querySelector('.password-toggle i');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.className = 'fas fa-eye-slash';
        } else {
            passwordInput.type = 'password';
            toggleIcon.className = 'fas fa-eye';
        }
    }

    // Add some interactive effects
    document.addEventListener('DOMContentLoaded', function() {
        const inputs = document.querySelectorAll('.form-control');
        
        inputs.forEach(input => {
            // Add focus effect
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });
            
            input.addEventListener('blur', function() {
                if (!this.value) {
                    this.parentElement.classList.remove('focused');
                }
            });
        });
    });
</script>

</body>
</html>