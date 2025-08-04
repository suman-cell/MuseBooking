<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html"); 
    exit();
}

// Check if a booking reference was passed
if (!isset($_GET['booking_ref'])) {
    echo "Invalid request!";
    exit();
}

$booking_ref = $_GET['booking_ref'];
$user_id = $_SESSION['user_id'];

// Fetch booking details
$query = "SELECT * FROM bookings WHERE booking_reference = '$booking_ref' AND user_id = '$user_id'";
$result = mysqli_query($conn, $query);
$booking = mysqli_fetch_assoc($result);

if (!$booking) {
    echo "Booking not found!";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; margin: 50px; }
        .container { max-width: 400px; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px; }
        .input-field { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; }
        .btn { padding: 10px 20px; background-color: green; color: white; border: none; border-radius: 5px; cursor: pointer; }
        .btn:hover { background-color: darkgreen; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Complete Your Payment</h2>
        <p>Booking Reference: <strong><?php echo $booking['booking_reference']; ?></strong></p>
        <p>Total Amount: <strong>₹<?php echo $booking['total_price']; ?></strong></p>

        <form id="paymentForm" action="process_payment.php" method="POST">
            <input type="hidden" id="booking_ref" name="booking_ref" value="<?php echo $booking['booking_reference']; ?>">
            <input type="text" class="input-field" id="card_number" name="card_number" placeholder="Card Number" required>
            <input type="text" class="input-field" id="expiry" name="expiry" placeholder="Expiry (MM/YY)" required>
            <input type="text" class="input-field" id="cvv" name="cvv" placeholder="CVV" required>
            <button type="submit" class="btn">Pay Now</button>
        </form>
    </div>
</body>
</html>
