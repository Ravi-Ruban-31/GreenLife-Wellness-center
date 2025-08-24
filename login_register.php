<?php
session_start();
require_once 'connection.php';

// Function to sanitize input
function sanitizeInput($data) {
    return htmlspecialchars(trim($data));
}

// Function to validate email
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Function to validate password strength
function validatePassword($password) {
    return strlen($password) >= 6;
}

// Registration Handler
if (isset($_POST['register'])) {
    $name = sanitizeInput($_POST['name']);
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password']; // Don't sanitize password as it may contain special chars
    $role = sanitizeInput($_POST['role']);

    // Validation
    $errors = [];
    
    if (empty($name)) {
        $errors[] = "Name is required";
    }
    
    if (!validateEmail($email)) {
        $errors[] = "Please enter a valid email address";
    }
    
    if (!validatePassword($password)) {
        $errors[] = "Password must be at least 6 characters long";
    }
    
    if (empty($role) || !in_array($role, ['User', 'Admin'])) {
        $errors[] = "Please select a valid role";
    }

    // If no validation errors, proceed with registration
    if (empty($errors)) {
        // Use prepared statements to prevent SQL injection
        $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $_SESSION['register_error'] = 'Email is already registered!';
            $_SESSION['active_form'] = 'register';
        } else {
            // Hash the password for security
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert new user
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $hashedPassword, $role);
            
            if ($stmt->execute()) {
                $_SESSION['success_message'] = 'Registration successful! Please login.';
                $_SESSION['active_form'] = 'login';
            } else {
                $_SESSION['register_error'] = 'Registration failed. Please try again.';
                $_SESSION['active_form'] = 'register';
            }
        }
        $stmt->close();
    } else {
        $_SESSION['register_error'] = implode('. ', $errors);
        $_SESSION['active_form'] = 'register';
    }

    header("Location: index.php");
    exit();
}

// Login Handler
if (isset($_POST['login'])) {
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];

    // Validation
    if (empty($email) || empty($password)) {
        $_SESSION['login_error'] = 'Please fill in all fields';
        $_SESSION['active_form'] = 'login';
        header("Location: index.php");
        exit();
    }

    if (!validateEmail($email)) {
        $_SESSION['login_error'] = 'Please enter a valid email address';
        $_SESSION['active_form'] = 'login';
        header("Location: index.php");
        exit();
    }

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT id, name, email, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Verify password using password_verify for hashed passwords
        if (password_verify($password, $user['password'])) {
            // Regenerate session ID for security
            session_regenerate_id(true);
            
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['logged_in'] = true;

            // Redirect based on role
            if (strtolower($user['role']) === 'admin') {
                header("Location: admin_page.php");
            } else {
                header("Location: user_page.php");
            }
            exit();
        } else {
            $_SESSION['login_error'] = 'Incorrect email or password';
        }
    } else {
        $_SESSION['login_error'] = 'Incorrect email or password';
    }
    
    $stmt->close();
    $_SESSION['active_form'] = 'login';
    header("Location: index.php");
    exit();
}

// Logout Handler (bonus feature)
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}
?>