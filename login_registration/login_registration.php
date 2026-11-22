<?php
// Start the session
session_start();

// Include database connection
include '../connection/connection.php';

// Initialize error and success messages
$login_error = '';
$register_error = '';
$register_success = '';

// Function to sanitize user input
function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}


// Login Form Processing
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    
    // Get and sanitize input
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password']; // Will be verified later

    // Validate input
    if (empty($email) || empty($password)) {
        $login_error = "Please enter both email and password.";
    } else {
        // Prepare SQL statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT user_id, name, email, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();

            // Verify hashed password
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];

                // Redirect to home page or dashboard
                header("Location: ../home/HomePage.html");
                exit();
            } else {
                $login_error = "Invalid email or password.";
            }
        } else {
            $login_error = "Invalid email or password.";
        }
        $stmt->close();
    }
}

// Registration Form Processing
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    // Get and sanitize input
    $name = sanitize_input($_POST['name']);
    $email = sanitize_input($_POST['email']);
    $phone = sanitize_input($_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate input
    if (empty($name) || empty($email) || empty($phone) || empty($password) || empty($confirm_password)) {
        $register_error = "Please fill in all fields.";
    } elseif ($password !== $confirm_password) {
        $register_error = "Passwords do not match.";
    } elseif (strlen($password) < 6) {
        $register_error = "Password must be at least 6 characters long.";
    } else {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $register_error = "Email already registered. Please use a different email.";
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert new user
            $stmt = $conn->prepare("INSERT INTO users (name, email, phone, password) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $phone, $hashed_password);

            if ($stmt->execute()) {
                $register_success = "Registration successful! You can now log in.";
            } else {
                $register_error = "Registration failed. Please try again.";
            }
        }
        $stmt->close();
    }
}

// Close the database connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Registration</title>
    <link rel="icon" type="image/x-icon" href="MainLogo.jpg">
    <link rel="stylesheet" href="bootstrap.min.css">
    <script src="bootstrap.bundle.min.js"></script>
    <link rel="stylesheet"href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"/>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="navbar.css">
 
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f5f5f5;
        }

        .container {
            background-color: white;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .tabs {
            display: flex;
            margin-bottom: 2rem;
        }

        .tab {
            flex: 1;
            padding: 1rem;
            text-align: center;
            cursor: pointer;
            border: none;
            background: none;
            font-size: 1.1rem;
            color: #333;
        }

        .tab.active {
            color: #ff5722;
            font-weight: bold;
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-group input {
            width: 100%;
            padding: 1rem;
            padding-left: 2.5rem;
            border: 1px solid #ddd;
            border-radius: 0.5rem;
            font-size: 1rem;
            outline: none;
        }

        .form-group input::placeholder {
            color: #999;
        }

        .form-group i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }

        .forgot-password {
            text-align: right;
            margin-bottom: 1.5rem;
        }

        .forgot-password a {
            color: #ff5722;
            text-decoration: none;
        }

        .login-btn {
            width: 100%;
            padding: 1rem;
            background-color: #ff5722;
            color: white;
            border: none;
            border-radius: 0.5rem;
            font-size: 1.1rem;
            cursor: pointer;
            margin-bottom: 1.5rem;
        }

        .register-link {
            text-align: center;
        }

        .register-link a {
            color: #ff5722;
            text-decoration: none;
            font-weight: bold;
        }

        .show-password {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #999;
        }

        .hidden {
            display: none;
        }
    </style>
</head>
<body>


    
    <div class="container">
        <div class="tabs">
            <button class="tab active" onclick="showLogin()">Login</button>
            <button class="tab" onclick="showRegister()">Register</button>
        </div>

        <!-- Display error/success messages -->
        <?php if(isset($login_error) && !empty($login_error)): ?>
            <div class="alert alert-danger"><?php echo $login_error; ?></div>
        <?php endif; ?>
        
        <?php if(isset($register_error) && !empty($register_error)): ?>
            <div class="alert alert-danger"><?php echo $register_error; ?></div>
        <?php endif; ?>
        
        <?php if(isset($register_success) && !empty($register_success)): ?>
            <div class="alert alert-success"><?php echo $register_success; ?></div>
        <?php endif; ?>

        <form id="loginForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" <?php echo isset($_POST['register']) ? 'class="hidden"' : ''; ?>>
            <div class="form-group">
                <i>👤</i>
                <input type="text" name="email" placeholder="Enter your email" required>
            </div>

            <div class="form-group">
                <i>🔒</i>
                <input type="password" name="password" placeholder="Password" required>
                <span class="show-password" onclick="togglePassword(this)">👁️</span>
            </div>

            <div class="forgot-password">
                <a href="#">Forgot Password?</a>
            </div>

            <button type="submit" name="login" class="login-btn">Log In</button>

            <div class="register-link">
                <span>Don't have an account? </span>
                <a href="#" onclick="showRegister()">Register Here</a>
            </div>
        </form>

        <form id="registerForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" <?php echo !isset($_POST['register']) ? 'class="hidden"' : ''; ?>>
            <div class="form-group">
                <i>📧</i>
                <input type="email" name="email" placeholder="Email" required>
            </div>
            <div class="form-group">
                <i>👤</i>
                <input type="text" name="name" placeholder="Name" required>
            </div>
            <div class="form-group">
                <i>📞</i>
                <input type="tel" name="phone" placeholder="Phone" required>
            </div>
            <div class="form-group">
                <i>🔒</i>
                <input type="password" name="password" placeholder="Password" required>
                <span class="show-password" onclick="togglePassword(this)">👁️</span>
            </div>
            <div class="form-group">
                <i>🔒</i>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                <span class="show-password" onclick="togglePassword(this)">👁️</span>
            </div>

            <button type="submit" name="register" class="login-btn">Register</button>

            <div class="register-link">
                <span>Already have an account? </span>
                <a href="#" onclick="showLogin()">Login Here</a>
            </div>
        </form>
    </div>

    <script>

        function showLogin() {
            document.getElementById('loginForm').classList.remove('hidden');
            document.getElementById('registerForm').classList.add('hidden');
            document.querySelector('.tab.active').classList.remove('active');
            document.querySelector('.tab:nth-child(1)').classList.add('active');
        }        
        function showRegister() {
            document.getElementById('loginForm').classList.add('hidden');
            document.getElementById('registerForm').classList.remove('hidden');
            document.querySelector('.tab.active').classList.remove('active');
            document.querySelector('.tab:nth-child(2)').classList.add('active');
        }
        function togglePassword(element) {
            const passwordField = element.previousElementSibling;
            if (passwordField.type === "password") {
                passwordField.type = "text";
                element.textContent = "🔒";
            } else {
                passwordField.type = "password";
                element.textContent = "👁️";
            }
        }
    </script>

   
</body>
</html>
