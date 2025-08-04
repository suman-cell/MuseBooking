<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $booking_ref = $_POST['booking_ref'];

    // Simulate payment success
    if (!empty($_POST['card_number']) && !empty($_POST['expiry']) && !empty($_POST['cvv'])) {
        // Update booking status to Confirmed
        $query = "UPDATE bookings SET booking_status = 'Confirmed' WHERE booking_reference = '$booking_ref'";
        if (mysqli_query($conn, $query)) {
            echo "Payment successful! Your booking is confirmed.";
            header("refresh:3; url=booking_summary.php"); // Redirect after 3 sec
            exit();
        } else {
            echo "Error updating booking status: " . mysqli_error($conn);
        }
    } else {
        echo "Invalid payment details. Please try again.";
    }
}
?>
