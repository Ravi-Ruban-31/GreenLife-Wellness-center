<?php
session_start();

$errors = [
    'login' => $_SESSION['login_error'] ?? '',
    'register' => $_SESSION['register_error'] ?? ''
];
$activeForm = $_SESSION['active_form'] ?? 'login';

// Clear session errors after displaying them
unset($_SESSION['login_error']);
unset($_SESSION['register_error']);
unset($_SESSION['active_form']);

function showError($error) {
    return !empty($error) ? "<p class='error-message'>" . htmlspecialchars($error) . "</p>" : '';
}

function isActiveForm($formName, $activeForm) {
    return $formName === $activeForm ? 'active' : '';
}
?>

<!-- <!DOCTYPE html>
  -->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Register</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(to top, rgba(0,0,0,0.5) 50%, rgba(0,0,0,0.5) 50%), url(bgn.png);
            background-size: cover;
            background-position: center;
            color: #333;
        }

        .container {
            background: transparent;
            margin: 0 15px;
            width: 100%;
            max-width: 450px;
        }

        .form-box {
            width: 100%;
            padding: 30px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            display: none;
        }

        .form-box.active {
            display: block;
        }

        h2 {
            font-size: 34px;
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        input, select {
            width: 100%;
            padding: 12px;
            background: #eee;
            border-radius: 6px;
            border: none;
            outline: none;
            font-size: 16px;
            color: #333;
            margin-bottom: 20px;
            transition: background-color 0.3s;
        }

        input:focus, select:focus {
            background: #ddd;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #7494ec;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            color: #fff;
            font-weight: 500;
            margin-bottom: 20px;
            transition: background-color 0.3s;
        }

        button:hover {
            background: #5b7ce8;
        }

        button:active {
            transform: translateY(1px);
        }

        p {
            font-size: 14.5px;
            text-align: center;
            margin-bottom: 10px;
        }

        p a {
            color: #7494ec;
            text-decoration: none;
            cursor: pointer;
        }

        p a:hover {
            text-decoration: underline;
        }

        .error-message {
            padding: 12px;
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 6px;
            font-size: 16px;
            color: #721c24;
            text-align: center;
            margin-bottom: 20px;
        }

        /* Responsive design */
        @media (max-width: 480px) {
            .container {
                margin: 0 10px;
            }
            
            .form-box {
                padding: 20px;
            }
            
            h2 {
                font-size: 28px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Login Form -->
        <div class="form-box <?= isActiveForm('login', $activeForm); ?>" id="login-form">
            <form action="login_register.php" method="post">
                <h2>Login Here</h2>
                <?= showError($errors['login']); ?>
                <input type="email" name="email" placeholder="Enter Email Here" required>
                <input type="password" name="password" placeholder="Enter Password Here" required>
                <button type="submit" name="login">Login</button>
                <p>Don't have an account? <a href="#" onclick="showForm('register-form')">Register</a></p>
            </form>
        </div>

        <!-- Register Form -->
        <div class="form-box <?= isActiveForm('register', $activeForm); ?>" id="register-form">
            <form action="login_register.php" method="post">
                <h2>Register Here</h2>
                <?= showError($errors['register']); ?>
                <input type="text" name="name" placeholder="Enter Your Name Here" required>
                <input type="email" name="email" placeholder="Enter Email Here" required>
                <input type="password" name="password" placeholder="Enter Password Here" minlength="6" required>
                <select name="role" required>
                    <option value="">--Select Role--</option>
                    <option value="User">User</option>
                    <option value="Admin">Admin</option>
                </select>
                <button type="submit" name="register">Register</button>
                <p>Already have an account? <a href="#" onclick="showForm('login-form')">Login</a></p>
            </form>
        </div>
    </div>

    <script>
        function showForm(formId) {
            // Hide all forms
            const forms = document.querySelectorAll('.form-box');
            forms.forEach(form => form.classList.remove('active'));
            
            // Show selected form
            document.getElementById(formId).classList.add('active');
        }

        // Initialize the active form on page load
        document.addEventListener('DOMContentLoaded', function() {
            const activeForm = '<?= $activeForm ?>';
            if (activeForm === 'register') {
                showForm('register-form');
            } else {
                showForm('login-form');
            }
        });
    </script>
</body>
</html>