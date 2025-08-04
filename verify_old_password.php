<?php
session_start();
require_once 'db.php';

$user = $_SESSION['reset_user'] ?? null;
$message = '';

if (!$user) {
    header("Location: forgot_password.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $old_password = $_POST['old_password'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    if (password_verify($old_password, $user['password'])) {
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $new_password, $user['id']);
        $stmt->execute();
        $message = "<span style='color: green;'>Password updated! <a href='login.php'>Login</a></span>";
    } else {
        $message = "<span style='color: red;'>Incorrect old password.</span>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="main">
        <h2>Reset with Old Password</h2>
        <form method="POST">
            <label for="old_password">Old Password:</label>
            <input type="password" name="old_password" id="old_password" placeholder="Old Password" required>

            <label for="new_password">New Password:</label>
            <input type="password" name="new_password" id="new_password" placeholder="New Password" required>

            <div class="wrap">
                <button type="submit">Reset Password</button>
            </div>
        </form>
        <p><?php echo $message; ?></p>
    </div>
</body>
</html>


