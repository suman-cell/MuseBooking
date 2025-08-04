<?php
session_start();

// Define your fixed admin credentials
$admin_username = "admin";
$admin_password = "admin123"; // Change to a secure password you want to use

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validate against hardcoded credentials
    if ($username === $admin_username && $password === $admin_password) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $admin_username;
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $error = "Invalid admin username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Admin Login</title>
    <link rel="stylesheet" href="login.css" />
</head>
<body>
    <div class="main">
        <h1><img src="favicon.svg" alt="Logo" /></h1>
        <h3>Admin Login</h3>

        <?php if (!empty($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>

        <form action="admin.php" method="POST">
            <label for="admin-login-username">Admin Username:</label>
            <input type="text" id="admin-login-username" name="username" placeholder="Enter Admin Username" required>

            <label for="admin-login-password">Password:</label>
            <input type="password" id="admin-login-password" name="password" placeholder="Enter Admin Password" required>

            <div class="wrap">
                <button type="submit">Login as Admin</button>
            </div>
        </form>

        <p>Back to <a href="login.php" style="color: blue;">User Login</a></p>
    </div>
</body>
</html>
