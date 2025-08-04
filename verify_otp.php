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
    if ($_POST['otp'] == $_SESSION['otp']) {
        $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $new_password, $user['id']);
        $stmt->execute();
        $message = "<span style='color: green;'>Password changed successfully! <a href='login.php'>Login</a></span>";
    } else {
        $message = "<span style='color: red;'>Invalid OTP!</span>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Verify OTP</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="main">
        <h2>Verify OTP</h2>
        <form method="POST">
            <label for="otp">Enter OTP:</label>
            <input type="text" name="otp" id="otp" placeholder="Enter OTP" required>

            <label for="new_password">New Password:</label>
            <input type="password" name="new_password" id="new_password" placeholder="New Password" required>

            <div class="wrap">
                <button type="submit">Submit</button>
            </div>
        </form>
        <p><?php echo $message; ?></p>
    </div>
</body>
</html>
