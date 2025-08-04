<?php
include 'db.php';

$selected_date = isset($_GET['visit_date']) ? $_GET['visit_date'] : date('Y-m-d');

// Fetch ticket availability for each museum on the selected date
$query = "SELECT m.museum_name AS museum, 
                 GREATEST(10 - COALESCE(SUM(b.adult_tickets + b.child_tickets), 0), 0) AS available_tickets
          FROM museums m
          LEFT JOIN bookings b 
          ON m.museum_name = b.museum 
          AND b.booking_status != 'Cancelled' 
          AND b.visit_date = '$selected_date'
          GROUP BY m.museum_name";
$result = mysqli_query($conn, $query);

$availability = [];
while ($row = mysqli_fetch_assoc($result)) {
    $availability[$row['museum']] = (int)$row['available_tickets'];
}

// Return the availability as JSON
header('Content-Type: application/json');
echo json_encode($availability);
?>