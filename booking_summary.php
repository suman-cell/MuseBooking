<?php
session_start();
include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html"); 
    exit();
}

// Get the logged-in user's bookings
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM bookings WHERE user_id = '$user_id' ORDER BY booking_date DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Summary</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; }
        .container { max-width: 800px; margin: auto; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: center; }
        th { background-color: #f4f4f4; }
        .cancel-btn { background-color: red; color: white; padding: 5px 10px; border: none; cursor: pointer; }
        .cancel-btn:hover { background-color: darkred; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Your Bookings</h2>
        <table>
            <tr>
                <th>Booking Reference</th>
                <th>Visit Date</th>
                <th>Adult Tickets</th>
                <th>Child Tickets</th>
                <th>Total Price</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row['booking_reference']; ?></td>
                <td><?php echo $row['visit_date']; ?></td>
                <td><?php echo $row['adult_tickets']; ?></td>
                <td><?php echo $row['child_tickets']; ?></td>
                <td>₹<?php echo $row['total_price']; ?></td>
                <td><?php echo $row['booking_status']; ?></td>
                <td>
                    <?php if ($row['booking_status'] == 'Pending') { ?>
                        <form action="cancel_booking.php" method="POST">
                            <input type="hidden" name="booking_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" class="cancel-btn">Cancel</button>
                        </form>
                    <?php } else { echo "N/A"; } ?>
                </td>
            </tr>
            <?php }?>
        </table>
        <br>
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>
