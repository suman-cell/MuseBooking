<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin.php");
    exit();
}

$id = $_GET['id'] ?? null;
if (!$id) {
    die("Booking ID missing.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $status = $_POST['booking_status'];
    $visit_date = $_POST['visit_date'];
    $adult = (int)$_POST['adult_tickets'];
    $child = (int)$_POST['child_tickets'];

    $update = "UPDATE bookings SET visit_date=?, adult_tickets=?, child_tickets=?, booking_status=? WHERE id=?";
    $stmt = $conn->prepare($update);
    $stmt->bind_param("siisi", $visit_date, $adult, $child, $status, $id);
    $stmt->execute();

    header("Location: admin-dashboard.php");
    exit();
}

$result = mysqli_query($conn, "SELECT * FROM bookings WHERE id = $id");
$booking = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Edit Booking</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary: #4361ee;
      --secondary: #3f37c9;
      --light: #f8f9fa;
      --dark: #212529;
      --success: #4cc9f0;
      --danger: #f72585;
      --warning: #f8961e;
    }
    
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }
    
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f0f2f5;
      padding: 40px 20px;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    
    .container {
      background: white;
      border-radius: 16px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      padding: 40px;
      width: 100%;
      max-width: 600px;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .container:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
    }
    
    h2 {
      color: var(--dark);
      margin-bottom: 30px;
      text-align: center;
      font-weight: 600;
    }
    
    .booking-id {
      color: var(--primary);
      font-weight: 500;
    }
    
    form {
      display: flex;
      flex-direction: column;
      gap: 20px;
    }
    
    .form-group {
      display: flex;
      flex-direction: column;
      gap: 8px;
    }
    
    label {
      font-size: 14px;
      font-weight: 500;
      color: var(--dark);
    }
    
    input, select {
      padding: 12px 16px;
      border: 1px solid #ddd;
      border-radius: 8px;
      font-family: 'Poppins', sans-serif;
      font-size: 15px;
      transition: all 0.3s ease;
      background-color: var(--light);
    }
    
    input:focus, select:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
      transform: translateY(-2px);
    }
    
    button {
      background-color: var(--primary);
      color: white;
      border: none;
      padding: 14px;
      border-radius: 8px;
      font-family: 'Poppins', sans-serif;
      font-size: 16px;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.3s ease;
      margin-top: 10px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    button:hover {
      background-color: var(--secondary);
      transform: translateY(-3px);
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }
    
    button:active {
      transform: translateY(0);
    }
    
    select option {
      padding: 10px;
    }
    
    .status-pending {
      color: var(--warning);
    }
    
    .status-confirmed {
      color: var(--success);
    }
    
    .status-cancelled {
      color: var(--danger);
    }
  </style>
</head>
<body>

<div class="container">
  <h2>Edit Booking <span class="booking-id">#<?= $id ?></span></h2>

  <form method="POST">
    <div class="form-group">
      <label>Visit Date</label>
      <input type="date" name="visit_date" value="<?= $booking['visit_date'] ?>" required>
    </div>

    <div class="form-group">
      <label>Adult Tickets</label>
      <input type="number" name="adult_tickets" value="<?= $booking['adult_tickets'] ?>" required min="0">
    </div>

    <div class="form-group">
      <label>Child Tickets</label>
      <input type="number" name="child_tickets" value="<?= $booking['child_tickets'] ?>" required min="0">
    </div>

    <div class="form-group">
      <label>Booking Status</label>
      <select name="booking_status" class="status-<?= strtolower($booking['booking_status']) ?>">
        <option value="Pending" <?= $booking['booking_status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
        <option value="Confirmed" <?= $booking['booking_status'] === 'Confirmed' ? 'selected' : '' ?>>Confirmed</option>
        <option value="Cancelled" <?= $booking['booking_status'] === 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
      </select>
    </div>

    <button type="submit">Update Booking</button>
  </form>
</div>

</body>
</html>