<?php
session_start();
include 'db.php';

// Check if the admin is logged in (Modify this based on your authentication system)
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $event_id = $_POST['event_id'];
    $new_tickets = (int)$_POST['new_tickets']; // Ensure it's an integer

    // Validate input
    if ($new_tickets < 0) {
        die("Invalid ticket number.");
    }

    // Prepare SQL statement to prevent SQL injection
    $query = "UPDATE events SET available_tickets = ? WHERE id = ?";
    if ($stmt = mysqli_prepare($conn, $query)) {
        mysqli_stmt_bind_param($stmt, "ii", $new_tickets, $event_id);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: admin_tickets.php?success=1");
            exit();
        } else {
            echo "Error updating ticket availability: " . mysqli_stmt_error($stmt);
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Error preparing statement: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>
