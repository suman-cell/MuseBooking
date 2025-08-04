<?php
include 'db.php';

$museum_name = isset($_GET['museum_name']) ? mysqli_real_escape_string($conn, trim($_GET['museum_name'])) : '';
$ticket_count = isset($_GET['people_count']) ? (int)$_GET['people_count'] : 0; // Renamed to ticket_count

// Validate input
if (empty($museum_name) || $ticket_count <= 0) {
    echo json_encode(['error' => 'Invalid input. Please provide a valid museum name and number of tickets.']);
    exit;
}

// Fetch ticket availability for the selected museum
$query = "SELECT dates.date AS visit_date, 
                 GREATEST(10 - COALESCE(SUM(b.adult_tickets + b.child_tickets), 0), 0) AS available_tickets
          FROM (
              SELECT CURDATE() + INTERVAL n DAY AS date
              FROM (SELECT 0 AS n UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4) AS numbers
          ) AS dates
          LEFT JOIN bookings b 
          ON DATE(dates.date) = DATE(b.visit_date) 
          AND b.museum = '$museum_name'
          AND b.booking_status != 'Cancelled'
          GROUP BY dates.date";

$result = mysqli_query($conn, $query);

if (!$result) {
    echo json_encode(['error' => 'Database query failed.']);
    exit;
}

$availability = [];
while ($row = mysqli_fetch_assoc($result)) {
    $availability[] = [
        'date' => $row['visit_date'], // Use the valid date from the query
        'available_tickets' => (int)$row['available_tickets'],
        'can_book' => $row['available_tickets'] >= $ticket_count // Check if tickets are sufficient
    ];
}

// Return the availability as JSON
header('Content-Type: application/json');
echo json_encode($availability);
?>