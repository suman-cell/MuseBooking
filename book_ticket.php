<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $visit_date = mysqli_real_escape_string($conn, $_POST['visit_date']);
    $event = mysqli_real_escape_string($conn, $_POST['event']);
    $adult_tickets = (int)$_POST['adults'];
    $child_tickets = (int)$_POST['children'];
    $total_tickets = $adult_tickets + $child_tickets;

    // Ticket Prices
    $adult_price = 120;
    $child_price = 60;
    $total_price = ($adult_tickets * $adult_price) + ($child_tickets * $child_price);

    // Check available tickets for the selected museum
    $query = "SELECT GREATEST(10 - COALESCE(SUM(adult_tickets + child_tickets), 0), 0) AS available_tickets 
              FROM bookings 
              WHERE museum = '$event' AND booking_status != 'Cancelled'";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    $row = mysqli_fetch_assoc($result);
    $available_tickets = (int)$row['available_tickets'];

    // Check if the requested tickets exceed the available tickets
    if ($available_tickets <= 0 || $total_tickets > $available_tickets) {
        echo "No tickets available for $event. Booking limit reached.";
        exit();
    }

    // Generate Booking Reference
    $booking_reference = strtoupper(substr(md5(time() . rand()), 0, 8));

    // Insert into Database
    $query = "INSERT INTO bookings (user_id, name, email, phone, visit_date, museum, adult_tickets, child_tickets, total_price, booking_reference, booking_status) 
              VALUES ('{$_SESSION['user_id']}', '$name', '$email', '$phone', '$visit_date', '$event', '$adult_tickets', '$child_tickets', '$total_price', '$booking_reference', 'Pending')";

    if (mysqli_query($conn, $query)) {
        echo "Booking successful! Your reference: $booking_reference";
        header("refresh:3; url=payment.php?booking_ref=$booking_reference");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
