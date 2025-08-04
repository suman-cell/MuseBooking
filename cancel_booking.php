<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $booking_id = $_POST['booking_id'];
    $user_id = $_SESSION['user_id'];

    // Update booking status to "Cancelled"
    $query = "UPDATE bookings SET booking_status = 'Cancelled' WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $booking_id, $user_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Booking cancelled successfully.";
        header("Location: mybookings.php");
        exit();
    } else {
        $_SESSION['error'] = "Failed to cancel the booking. Please try again.";
        header("Location: mybookings.php");
        exit();
    }
}
?>

<div class="ticket-card">
    <div class="booking-ref">Ref: <?= htmlspecialchars($row['booking_reference']) ?></div>
    <h2><?= htmlspecialchars($row['museum']) ?></h2>
    <div class="ticket-info">
        <p><strong>Name:</strong> <?= htmlspecialchars($row['name']) ?></p>
        <p><strong>Date of Visit:</strong> <?= htmlspecialchars($row['visit_date']) ?></p>
        <p><strong>Tickets:</strong> <?= $row['adult_tickets'] ?> Adult(s), <?= $row['child_tickets'] ?> Child(ren)</p>
        <p><strong>Total Paid:</strong> ₹<?= $row['total_price'] ?></p>
        <p><strong>Status:</strong> <?= $row['booking_status'] ?></p>
    </div>
    <div class="qr-code">
        <img src="https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl=<?= urlencode($row['booking_reference']) ?>&choe=UTF-8" alt="QR Code">
    </div>
    <div style="margin-top: 10px; text-align:center;">
        <a href="download-ticket.php?id=<?= $row['id'] ?>" 
           style="display: inline-block; background: #4CAF50; color: white; padding: 8px 12px; border-radius: 5px; text-decoration: none;">
            📄 Download Ticket (PDF)
        </a>
    </div>
    <?php if ($row['booking_status'] == 'Pending'): ?>
        <form action="cancel_booking.php" method="POST" style="margin-top: 10px; text-align:center;">
            <input type="hidden" name="booking_id" value="<?= $row['id'] ?>">
            <button type="submit" style="background: #e53e3e; color: white; padding: 8px 12px; border: none; border-radius: 5px; cursor: pointer;">
                ❌ Cancel Ticket
            </button>
        </form>
    <?php endif; ?>
</div>
