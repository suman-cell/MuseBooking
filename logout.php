<?php
session_start();
session_destroy(); // Destroy all session data
setcookie("username", "", time() - 3600, "/"); // Expire the cookie
header("Location: login.php"); // Redirect to login page
exit();
?>
