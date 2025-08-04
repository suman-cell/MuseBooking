<?php
session_start();
require_once 'db.php'; // Ensure this file contains your database connection setup

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $rememberMe = isset($_POST['remember']);

    if (!empty($username) && !empty($password)) {
        $query = "SELECT id, password FROM users WHERE username = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) { // Assuming passwords are hashed
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $user_email; // After login


                // Set cookie if "Remember Me" is checked
                if ($rememberMe) {
                    setcookie("username", $username, time() + (86400 * 30), "/"); // 30 days
                }

                // Redirect to index.php
                header("Location: index.php");
                exit();
            } else {
                echo "<script>alert('Invalid username or password!');</script>";
            }
        } else {
            echo "<script>alert('Invalid username or password!');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Both fields are required!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
    <script src="JS/script1.js" defer></script>
</head>
<body>
    <div class="main">
        <h1><img src="favicon.svg" alt="Logo"></h1>
        <h3>Enter your login credentials</h3>

        <form method="POST" action="">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" placeholder="Enter your Username" required 
                value="<?php echo isset($_COOKIE['username']) ? $_COOKIE['username'] : ''; ?>">

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Enter your Password" required>

            <div class="wrap">
                <button type="submit">Login</button>
            </div>
        </form>
        <p><a href="forgot_password.php" style="color: blue;">Forgot Password?</a></p>
        <p>Not registered? <a href="register.php">Create an account</a></p>
        <p>Admin? <a href="admin.php" style="color: red; font-weight: bold;">Login Here</a></p>
    </div>
</body>
</html>
