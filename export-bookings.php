<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin.php");
    exit();
}

include 'db.php';

// Set CSV headers
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="bookings_export.csv"');

$output = fopen('php://output', 'w');

// Add column headers
fputcsv($output, [
    'ID', 'User ID', 'Name', 'Email', 'Phone',
    'Visit Date', 'Adult Tickets', 'Child Tickets',
    'Total Price', 'Booking Ref', 'Status', 'Booking Date', 'Museum'
]);

// Fetch and write data
$result = mysqli_query($conn, "SELECT * FROM bookings ORDER BY booking_date DESC");
while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, [
        $row['id'],
        $row['user_id'],
        $row['name'],
        $row['email'],
        $row['phone'],
        $row['visit_date'],
        $row['adult_tickets'],
        $row['child_tickets'],
        $row['total_price'],
        $row['booking_reference'],
        $row['booking_status'],
        $row['booking_date'],
        $row['museum']
    ]);
}
fclose($output);
exit();
