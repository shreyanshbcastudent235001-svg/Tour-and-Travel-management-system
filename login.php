<?php
session_start();
include 'include/db.php';
$msg = '';
if($_SERVER['REQUEST_METHOD']=='POST'){
    $email = $conn->real_escape_string(trim($_POST['email']));
    $password = $_POST['password'];
    $res = $conn->query("SELECT * FROM users WHERE email='{$email}'");
    if($res->num_rows==1){
        $u = $res->fetch_assoc();
        if(password_verify($password, $u['password'])){
            $_SESSION['user_id'] = $u['id'];
            $_SESSION['user_name'] = $u['name'];
            header('Location: index.php'); exit;
        } else { $msg = 'Invalid credentials'; }
    } else { $msg = 'Invalid credentials'; }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset='utf-8'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Tour & Travel</title>
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
        .main-content {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 20px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
        }

        .login-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            width: 100%;
            max-width: 450px;
            position: relative;
        }

        .login-header {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 2.5rem 2rem;
            text-align: center;
        }

        .login-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 1rem;
        }

        .login-logo i {
            font-size: 2.5rem;
        }

        .login-logo h2 {
            font-size: 1.8rem;
            font-weight: 700;
        }

        .login-header p {
            opacity: 0.9;
            font-size: 1.1rem;
        }

        .login-form {
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
            padding: 1rem;
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
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            border-left: 4px solid;
        }

        .alert-success {
            background-color: #d4edda;
            border-color: var(--success);
            color: #155724;
        }

        .alert-error {
            background-color: #f8d7da;
            border-color: var(--accent);
            color: #721c24;
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

        /* Footer */
        footer {
            background: var(--dark);
            color: white;
            text-align: center;
            padding: 2rem 0;
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

            .login-container {
                margin: 10px;
            }

            .login-header {
                padding: 2rem 1.5rem;
            }

            .login-form {
                padding: 2rem 1.5rem;
            }

            .login-logo {
                flex-direction: column;
                gap: 8px;
            }

            .login-logo h2 {
                font-size: 1.6rem;
            }

            .footer-links {
                flex-direction: column;
                gap: 1rem;
            }
        }

        .forgot-password {
            text-align: right;
            margin-top: 0.5rem;
        }

        .forgot-password a {
            color: var(--secondary);
            text-decoration: none;
            font-size: 0.9rem;
            transition: var(--transition);
        }

        .forgot-password a:hover {
            color: var(--primary);
            text-decoration: underline;
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
            <a href="about.php">About</a>
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
<div class="main-content">
    <div class="login-container">
        <div class="login-header">
            <div class="login-logo">
                <i class="fas fa-sign-in-alt"></i>
                <h2>Welcome Back</h2>
            </div>
            <p>Login to your account to continue</p>
        </div>

        <div class="login-form">
            <!-- Success Message -->
            <?php if(isset($_GET['registered'])): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> Account created successfully. Please login.
                </div>
            <?php endif; ?>

            <!-- Error Message -->
            <?php if(isset($msg) && $msg): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($msg); ?>
                </div>
            <?php endif; ?>

            <form method='post'>
                <div class="form-group">
                    <label for="email">
                        <i class="fas fa-envelope"></i> Email Address
                    </label>
                    <input type='email' name='email' id='email' class="form-control" placeholder="Enter your email" required>
                    <i class="fas fa-envelope input-icon"></i>
                </div>

                <div class="form-group">
                    <label for="password">
                        <i class="fas fa-lock"></i> Password
                    </label>
                    <input type='password' name='password' id='password' class="form-control" placeholder="Enter your password" required>
                    <i class="fas fa-lock input-icon"></i>
                    <button type="button" class="password-toggle" onclick="togglePassword()">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>

                <div class="forgot-password">
                    <a href="forgot-password.php">Forgot your password?</a>
                </div>

                <button type='submit' class="btn-form btn-primary-form">
                    <i class="fas fa-sign-in-alt"></i> Login to Account
                </button>
            </form>

            <div class="form-footer">
                <p>Don't have an account? <a href='signup.php'>Create new account</a></p>
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