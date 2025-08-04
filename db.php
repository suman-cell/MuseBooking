<?php
$host = "localhost"; // Server name
$user = "root";      // Default XAMPP username
$pass = "";          // Default XAMPP password (empty)
$dbname = "museumbooking"; // Database name

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
