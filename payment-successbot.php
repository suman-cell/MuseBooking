<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// Retrieve data from session or query string
$name = $_GET['name'] ?? $_SESSION['name'] ?? '';
$phone = $_GET['phone'] ?? $_SESSION['phone'] ?? '';
$visit_date = $_GET['visit_date'] ?? $_SESSION['visit_date'] ?? '';
$museum = $_GET['museum'] ?? $_SESSION['museum'] ?? '';
$adults = $_GET['adults'] ?? $_SESSION['adults'] ?? 0;
$children = $_GET['children'] ?? $_SESSION['children'] ?? 0;
$total = $_GET['total'] ?? $_SESSION['total'] ?? 0;

// Validate required fields
if (empty($name) || empty($phone) || empty($visit_date) || empty($museum)) {
    die("❌ Error: Missing required booking details.");
}

// Save booking to database
$conn = new mysqli("localhost", "root", "", "museumbooking");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$booking_reference = strtoupper(uniqid('REF'));
$booking_status = 'Confirmed';
$booking_date = date('Y-m-d H:i:s');

$stmt = $conn->prepare("INSERT INTO bookings 
    (user_id, name, email, phone, visit_date, museum, adult_tickets, child_tickets, total_price, booking_reference, booking_status, booking_date) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$stmt->bind_param("isssssiiisss", 
    $_SESSION['user_id'], $name, $_GET['email'], $phone, $visit_date, 
    $museum, $adults, $children, $total, 
    $booking_reference, $booking_status, $booking_date
);

if ($stmt->execute()) {
    $booking_id = $stmt->insert_id; // Get the booking ID for redirection
} else {
    echo "Error: " . $stmt->error;
    exit;
}

$stmt->close();
$conn->close();

// Send confirmation email
$email = $_GET['email'] ?? $_SESSION['email'] ?? '';
if (!empty($email)) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'musebook080@gmail.com'; // Replace with your Gmail address
        $mail->Password = 'gqhw ivij eekn flbz';   // Replace with your Gmail app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('musebook080@gmail.com', 'MuseBook');
        $mail->addAddress($email, $name); // Use the email from the form or session

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your Museum Ticket Booking Confirmation';
        $mail->Body = "
            <h3>Dear $name,</h3>
            <p>Thank you for booking tickets with us! Here are your booking details:</p>
            <ul>
                <li><strong>Museum:</strong> $museum</li>
                <li><strong>Visit Date:</strong> $visit_date</li>
                <li><strong>Adult Tickets:</strong> $adults</li>
                <li><strong>Child Tickets:</strong> $children</li>
                <li><strong>Total Amount:</strong> ₹$total</li>
            </ul>
            <p>You can download your ticket using the following link:</p>
            <a href='https://yourwebsite.com/download-ticket.php?id=$booking_id'>Download Ticket</a>
            <p>We look forward to your visit!</p>
            <p>Regards,<br>Muse-Bot Team</p>
        ";

        $mail->send();
        error_log("Confirmation email sent to $email");
    } catch (Exception $e) {
        error_log("Failed to send email: {$mail->ErrorInfo}");
        echo "❌ Error: Unable to send confirmation email. Please try again later.";
    }
} else {
    error_log("No email address provided for sending confirmation.");
    echo "❌ Error: Email address is missing.";
}

// Confirmation page
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Booking Confirmation | MuseBook</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary: #4361ee;
      --primary-light: #4cc9f0;
      --success: #4ade80;
      --dark: #1e293b;
      --light: #f8fafc;
      --gray: #94a3b8;
    }
    
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f5f7ff;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      margin: 0;
      padding: 20px;
      color: var(--dark);
    }
    
    .confirmation-card {
      max-width: 600px;
      width: 100%;
      background: white;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
      transform: translateY(20px);
      opacity: 0;
      animation: fadeInUp 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
    }
    
    .confirmation-header {
      background: linear-gradient(135deg, var(--primary), var(--primary-light));
      color: white;
      padding: 30px;
      text-align: center;
      position: relative;
      overflow: hidden;
    }
    
    .confirmation-header::after,
    .confirmation-header::before {
      content: '';
      position: absolute;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.1);
    }
    
    .confirmation-header::after {
      top: -50px;
      right: -50px;
      width: 150px;
      height: 150px;
    }
    
    .confirmation-header::before {
      bottom: -80px;
      left: -30px;
      width: 200px;
      height: 200px;
    }
    
    .checkmark-circle {
      width: 80px;
      height: 80px;
      background: white;
      border-radius: 50%;
      display: flex;
      justify-content: center;
      align-items: center;
      margin: 0 auto 20px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
      position: relative;
      z-index: 2;
    }
    
    .checkmark {
      color: var(--success);
      font-size: 40px;
      animation: scaleCheckmark 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
    }
    
    .confirmation-body {
      padding: 30px;
    }
    
    .booking-details {
      background: #f8fafc;
      border-radius: 12px;
      padding: 20px;
      margin: 20px 0;
    }
    
    .detail-row {
      display: flex;
      justify-content: space-between;
      padding: 10px 0;
      border-bottom: 1px solid #e2e8f0;
    }
    
    .detail-row:last-child {
      border-bottom: none;
    }
    
    .detail-label {
      color: var(--gray);
      font-weight: 500;
    }
    
    .detail-value {
      font-weight: 600;
    }
    
    .reference-number {
      background: var(--dark);
      color: white;
      padding: 10px 15px;
      border-radius: 8px;
      font-family: monospace;
      letter-spacing: 1px;
      display: inline-block;
      margin-top: 10px;
    }
    
    .btn-home {
      background: var(--primary);
      color: white;
      border: none;
      padding: 12px 30px;
      border-radius: 8px;
      font-weight: 600;
      margin-top: 20px;
      transition: all 0.3s ease;
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }
    
    .btn-home:hover {
      background: #3a56e8;
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
    }
    
    .confirmation-footer {
      text-align: center;
      padding: 20px;
      color: var(--gray);
      font-size: 14px;
      border-top: 1px solid #e2e8f0;
    }
    
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    @keyframes scaleCheckmark {
      0% {
        transform: scale(0);
      }
      80% {
        transform: scale(1.2);
      }
      100% {
        transform: scale(1);
      }
    }
    
    .countdown {
      margin-top: 20px;
      font-size: 14px;
      color: var(--gray);
    }
  </style>
</head>
<body>
  <div class="confirmation-card">
    <div class="confirmation-header">
      <div class="checkmark-circle">
        <i class="fas fa-check checkmark"></i>
      </div>
      <h2>Booking Confirmed!</h2>
      <p>Your museum visit has been successfully booked</p>
    </div>
    
    <div class="confirmation-body">
      <h4>Hello, <?php echo htmlspecialchars($name); ?>!</h4>
      <p>Thank you for booking with MuseBook. Here are your booking details:</p>
      
      <div class="booking-details">
        <div class="detail-row">
          <span class="detail-label">Museum:</span>
          <span class="detail-value"><?php echo htmlspecialchars($museum); ?></span>
        </div>
        <div class="detail-row">
          <span class="detail-label">Visit Date:</span>
          <span class="detail-value"><?php echo htmlspecialchars($visit_date); ?></span>
        </div>
        <div class="detail-row">
          <span class="detail-label">Adults:</span>
          <span class="detail-value"><?php echo $adults; ?></span>
        </div>
        <div class="detail-row">
          <span class="detail-label">Children:</span>
          <span class="detail-value"><?php echo $children; ?></span>
        </div>
        <div class="detail-row">
          <span class="detail-label">Total Paid:</span>
          <span class="detail-value">₹<?php echo number_format($total, 2); ?></span>
        </div>
      </div>
      
      <p>Your booking reference:</p>
      <div class="reference-number"><?php echo $booking_reference; ?></div>
      
      <p class="mt-3">A confirmation has been sent to your email address.</p>
      
      <center>
        <a href="index.php?chatbot=1" class="btn btn-home">
          <i class="fas fa-home"></i> Return to Chatbot
        </a>
        <div class="countdown">Redirecting to chatbot in <span id="countdown">5</span> seconds...</div>
      </center>
    </div>
    
    <div class="confirmation-footer">
      <p>Need help? Contact us at support@musebook.com</p>
      <p>© <?php echo date('Y'); ?> MuseBook. All rights reserved.</p>
    </div>
  </div>

  <script>
    // Countdown timer
    let seconds = 5;
    const countdownElement = document.getElementById('countdown');
    
    const countdown = setInterval(() => {
      seconds--;
      countdownElement.textContent = seconds;
      
      if (seconds <= 0) {
        clearInterval(countdown);
        // Redirect to chatbot with booking ID
        window.location.href = 'index.php?chatbot=1&booking_id=<?php echo $booking_id; ?>';
      }
    }, 1000);
  </script>
</body>
</html>
