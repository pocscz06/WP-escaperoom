<?php
session_start();

/**
 * Register a new user by saving credentials in a cookie.
 *
 * @param string $username The username.
 * @param string $password The password.
 * @return string|null Success message or error message.
 */
function register_user($username, $password) {
    // Load existing users from the cookie
    $users = isset($_COOKIE['users']) ? json_decode($_COOKIE['users'], true) : [];

    // Check if the username already exists
    if (isset($users[$username])) {
        return "Username already exists.";
    }

    // Save the new user (password is hashed for security)
    $users[$username] = password_hash($password, PASSWORD_DEFAULT);
    setcookie('users', json_encode($users), time() + (86400 * 30), "/"); // Save for 30 days

    return "Registration successful. You can now log in.";
}

/**
 * Log in a user by verifying credentials from the cookie.
 *
 * @param string $username The username.
 * @param string $password The password.
 * @return string|null Success message or error message.
 */
function login_user($username, $password) {
    // Load existing users from the cookie
    $users = isset($_COOKIE['users']) ? json_decode($_COOKIE['users'], true) : [];

    // Check if the username exists and verify the password
    if (isset($users[$username]) && password_verify($password, $users[$username])) {
        // Set session variables for the logged-in user
        $_SESSION['username'] = $username;
        return "Login successful.";
    }

    return "Invalid username or password.";
}

/**
 * Log out the user by clearing the session.
 */
function logout_user() {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}

// Handle form submissions
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['register'])) {
        $message = register_user($_POST['username'], $_POST['password']);
    } elseif (isset($_POST['login'])) {
        $message = login_user($_POST['username'], $_POST['password']);
        if ($message === "Login successful.") {
            header("Location: index.php"); // Redirect to the main page
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login/Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }
        .container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-bottom: 5px;
            font-weight: bold;
        }
        input {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .message {
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 4px;
            text-align: center;
        }
        .message.success {
            background-color: #d4edda;
            color: #155724;
        }
        .message.error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Login/Register</h1>
        <?php if ($message): ?>
            <div class="message <?php echo strpos($message, 'successful') !== false ? 'success' : 'error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <form method="post">
            <label for="username">Name:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit" name="login">Login</button>
            <button type="submit" name="register">Register</button>
        </form>
    </div>
</body>
</html>