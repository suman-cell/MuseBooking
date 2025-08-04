<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $booking_id = $_POST['booking_id'];
    $action = $_POST['action'];

    if ($action == "approve") {
        $query = "UPDATE bookings SET booking_status = 'Confirmed' WHERE id = '$booking_id'";
    } elseif ($action == "cancel") {
        $query = "UPDATE bookings SET booking_status = 'Cancelled' WHERE id = '$booking_id'";
    }

    if (mysqli_query($conn, $query)) {
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
