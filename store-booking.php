<?php
include 'db.php';

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $input['name'];
    $email = $input['email'];
    $phone = $input['phone'];
    $visit_date = $input['date'];
    $time = $input['time'];
    $visitors = $input['visitors'];
    $museum = $input['museum'];
    $payment_id = $input['payment_id'];

    // Calculate total price
    $total_price = $visitors * 120; // Example: ₹120 per visitor

    // Generate booking reference
    $booking_reference = 'REF' . strtoupper(uniqid());

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO bookings (name, email, phone, visit_date, adult_tickets, total_price, booking_reference, booking_status, museum, payment_id) VALUES (?, ?, ?, ?, ?, ?, ?, 'Confirmed', ?, ?)");
    $stmt->bind_param("ssssissss", $name, $email, $phone, $visit_date, $visitors, $total_price, $booking_reference, $museum, $payment_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'booking_id' => $conn->insert_id]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Database error']);
    }
}
?>