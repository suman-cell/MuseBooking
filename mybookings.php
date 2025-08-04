<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// DB Connection
$conn = new mysqli("localhost", "root", "", "museumbooking");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("SELECT * FROM bookings WHERE user_id = ? ORDER BY booking_date DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings | Museum Explorer</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #5e35b1;
            --primary-dark: #4527a0;
            --primary-light: #7e57c2;
            --secondary: #ff7043;
            --success: #43a047;
            --danger: #e53935;
            --warning: #fb8c00;
            --info: #039be5;
            --light: #f5f5f5;
            --dark: #263238;
            --gray: #757575;
            --light-gray: #e0e0e0;
            --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            --card-shadow-hover: 0 8px 30px rgba(0, 0, 0, 0.15);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --border-radius: 12px;
            --glass-effect: rgba(255, 255, 255, 0.15);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
            color: var(--dark);
            line-height: 1.6;
            min-height: 100vh;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 3rem;
            padding: 1rem 0;
            position: relative;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
            text-decoration: none;
            z-index: 10;
        }
        
        .logo i {
            font-size: 1.75rem;
            transition: var(--transition);
        }
        
        .logo:hover i {
            transform: rotate(-15deg);
        }
        
        .user-nav {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }
        
        .user-nav a {
            color: var(--gray);
            text-decoration: none;
            transition: var(--transition);
            font-weight: 500;
            position: relative;
            padding: 0.5rem 0;
        }
        
        .user-nav a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--primary);
            transition: var(--transition);
        }
        
        .user-nav a:hover {
            color: var(--primary);
        }
        
        .user-nav a:hover::after {
            width: 100%;
        }
        
        .avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .avatar:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        
        h1 {
            font-size: 2.25rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: var(--dark);
            position: relative;
            display: inline-block;
        }
        
        h1::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 60px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary) 0%, var(--secondary) 100%);
            border-radius: 2px;
        }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2.5rem;
        }
        
        .filter-controls {
            display: flex;
            gap: 0.75rem;
            background: var(--glass-effect);
            backdrop-filter: blur(10px);
            padding: 0.5rem;
            border-radius: 50px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        
        .filter-btn {
            padding: 0.5rem 1.25rem;
            border-radius: 50px;
            background: transparent;
            border: none;
            cursor: pointer;
            transition: var(--transition);
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--gray);
            position: relative;
            overflow: hidden;
        }
        
        .filter-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            opacity: 0;
            transition: var(--transition);
            z-index: -1;
            border-radius: 50px;
        }
        
        .filter-btn.active, .filter-btn:hover {
            color: white;
        }
        
        .filter-btn.active::before, .filter-btn:hover::before {
            opacity: 1;
        }
        
        .booking-list {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        
        .booking-card {
            background: white;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--card-shadow);
            transition: var(--transition);
            display: flex;
            position: relative;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .booking-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 8px;
            height: 100%;
            background: linear-gradient(to bottom, var(--primary) 0%, var(--secondary) 100%);
        }
        
        .booking-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--card-shadow-hover);
        }
        
        .card-image {
            width: 250px;
            min-height: 200px;
            background-size: cover;
            background-position: center;
            position: relative;
        }
        
        .card-content {
            flex: 1;
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }
        
        .museum-name {
            font-weight: 700;
            font-size: 1.25rem;
            color: var(--dark);
            position: relative;
            padding-bottom: 0.5rem;
        }
        
        .museum-name::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 40px;
            height: 3px;
            background: var(--primary);
            border-radius: 3px;
        }
        
        .booking-status {
            padding: 0.375rem 0.875rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .status-confirmed {
            background: rgba(67, 160, 71, 0.1);
            color: var(--success);
        }
        
        .status-pending {
            background: rgba(251, 140, 0, 0.1);
            color: var(--warning);
        }
        
        .status-cancelled {
            background: rgba(229, 57, 53, 0.1);
            color: var(--danger);
        }
        
        .booking-details {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }
    
        .card-image {
            width: 250px;
            min-height: 200px;
            background-size: cover;
            background-position: center;
            position: relative;
        }
        
        .card-content {
            flex: 1;
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }
        
        .museum-name {
            font-weight: 700;
            font-size: 1.25rem;
            color: var(--dark);
            position: relative;
            padding-bottom: 0.5rem;
        }
        
        .museum-name::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 40px;
            height: 3px;
            background: var(--primary);
            border-radius: 3px;
        }
        
        .booking-status {
            padding: 0.375rem 0.875rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .status-confirmed {
            background: rgba(67, 160, 71, 0.1);
            color: var(--success);
        }
        
        .status-pending {
            background: rgba(251, 140, 0, 0.1);
            color: var(--warning);
        }
        
        .status-cancelled {
            background: rgba(229, 57, 53, 0.1);
            color: var(--danger);
        }
        
        .booking-details {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }
    
        .detail-item {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }
        
        .detail-label {
            font-size: 0.75rem;
            color: var(--gray);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 500;
        }
        
        .detail-value {
            font-weight: 600;
            font-size: 0.9375rem;
            color: var(--dark);
        }
        
        .ticket-count {
            display: flex;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }
        
        .ticket-badge {
            background: rgba(94, 53, 177, 0.1);
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.8125rem;
            font-weight: 600;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .ticket-badge i {
            font-size: 0.9rem;
        }
        
        .card-footer {
            margin-top: auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 1rem;
            border-top: 1px solid var(--light-gray);
        }
        
        .booking-reference {
            font-size: 0.75rem;
            color: var(--gray);
            font-weight: 500;
        }
        
        .booking-actions {
            display: flex;
            gap: 0.75rem;
        }
        
        .action-btn {
            padding: 0.625rem 1.25rem;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            border: none;
        }
        
        .download-btn {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
            box-shadow: 0 4px 6px rgba(94, 53, 177, 0.2);
        }
        
        .download-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(94, 53, 177, 0.3);
        }
        
        .cancel-btn {
            background: white;
            color: var(--danger);
            border: 1px solid var(--light-gray);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        
        .cancel-btn:hover {
            background: var(--danger);
            color: white;
            border-color: var(--danger);
            box-shadow: 0 4px 8px rgba(229, 57, 53, 0.2);
        }
        
        .qr-section {
            position: absolute;
            right: 1.5rem;
            top: 50%;
            transform: translateY(-50%);
        }
        
        .qr-code {
            width: 100px;
            height: 100px;
            background: white;
            padding: 0.5rem;
            border: 1px solid var(--light-gray);
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: var(--transition);
        }
        
        .qr-code:hover {
            transform: translateY(-50%) scale(1.05);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        }
        
        .empty-state {
            text-align: center;
            padding: 5rem 2rem;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
        }
        
        .empty-icon {
            font-size: 4rem;
            color: var(--primary-light);
            margin-bottom: 1.5rem;
            display: inline-block;
            transition: var(--transition);
        }
        
        .empty-state:hover .empty-icon {
            transform: rotate(15deg) scale(1.1);
        }
        
        .empty-text {
            color: var(--gray);
            margin-bottom: 2rem;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .explore-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.875rem 1.75rem;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
            box-shadow: 0 4px 6px rgba(94, 53, 177, 0.2);
            border: none;
            cursor: pointer;
        }
        
        .explore-btn:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 8px 15px rgba(94, 53, 177, 0.3);
        }
        
        .alert {
            padding: 1.25rem;
            border-radius: var(--border-radius);
            margin-bottom: 2.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            background: white;
            box-shadow: var(--card-shadow);
            transform: translateY(0);
            animation: slideIn 0.5s ease-out forwards;
            border-left: 5px solid;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .alert-success {
            border-left-color: var(--success);
            background: rgba(67, 160, 71, 0.05);
            color: var(--success);
        }
        
        .alert-error {
            border-left-color: var(--danger);
            background: rgba(229, 57, 53, 0.05);
            color: var(--danger);
        }
        
        .alert-icon {
            font-size: 1.5rem;
        }
        
        .alert-content {
            flex: 1;
        }
        
        .alert-title {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }
        
        /* Floating background elements */
        .floating-circle {
            position: absolute;
            border-radius: 50%;
            background: radial-gradient(circle, var(--primary-light) 0%, rgba(126, 87, 194, 0) 70%);
            opacity: 0.15;
            z-index: -1;
        }
        
        .circle-1 {
            width: 300px;
            height: 300px;
            top: -100px;
            right: -100px;
        }
        
        .circle-2 {
            width: 200px;
            height: 200px;
            bottom: -50px;
            left: -50px;
        }
        
        /* General responsive adjustments */
        @media (max-width: 1200px) {
            .container {
                padding: 1.5rem;
            }
            .booking-details {
                grid-template-columns: repeat(2, 1fr);
            }
            .card-image {
                width: 100%;
                height: 200px;
            }
        }

        /* Tablet view adjustments */
        @media (max-width: 768px) {
            header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            .user-nav {
                flex-wrap: wrap;
                gap: 1rem;
            }
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            .filter-controls {
                flex-wrap: wrap;
                justify-content: flex-start;
            }
            .booking-details {
                grid-template-columns: 1fr;
            }
            .qr-section {
                position: static;
                margin: 1rem 0;
                display: flex;
                justify-content: center;
            }
            .qr-code {
                width: 80px;
                height: 80px;
            }
        }

        /* Mobile view adjustments */
        @media (max-width: 480px) {
            .logo {
                font-size: 1.25rem;
            }
            .card-content {
                padding: 1rem;
            }
            .museum-name {
                font-size: 1rem;
            }
            .booking-status {
                font-size: 0.75rem;
            }
            .detail-label {
                font-size: 0.7rem;
            }
            .detail-value {
                font-size: 0.85rem;
            }
            .ticket-badge {
                font-size: 0.75rem;
                padding: 0.4rem 0.8rem;
            }
            .action-btn {
                font-size: 0.75rem;
                padding: 0.5rem 1rem;
            }
            .qr-code {
                width: 60px;
                height: 60px;
            }
            .empty-state {
                padding: 3rem 1rem;
            }
            .empty-text {
                font-size: 0.85rem;
            }
            .explore-btn {
                font-size: 0.85rem;
                padding: 0.75rem 1.5rem;
            }
        }

        @media (max-width: 992px) {
            .booking-card {
                flex-direction: column;
            }
            
            .card-image {
                width: 100%;
                height: 200px;
            }
            
            .booking-details {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .qr-section {
                position: static;
                transform: none;
                margin: 1.5rem 0;
                display: flex;
                justify-content: center;
            }
            
            .qr-code {
                transform: none !important;
            }
            
            .qr-code:hover {
                transform: scale(1.05) !important;
            }
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 1.5rem;
            }
            
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1.5rem;
            }
            
            .filter-controls {
                width: 100%;
                overflow-x: auto;
                padding-bottom: 0.5rem;
                justify-content: flex-start;
            }
            
            header {
                flex-direction: column;
                gap: 1.5rem;
                align-items: flex-start;
            }
            
            .user-nav {
                width: 100%;
                justify-content: space-between;
            }
            
            .booking-details {
                grid-template-columns: 1fr;
            }
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="floating-circle circle-1"></div>
    <div class="floating-circle circle-2"></div>
    
    <div class="container">
        <header>
            <a href="index.php" class="logo">
                <i class="fas fa-landmark"></i>
                <span>Museum Explorer</span>
            </a>
            <div class="user-nav">
                <a href="index.php"><i class="fas fa-home"></i> Home</a>
            </div>
        </header>
        
        <div class="page-header">
            <h1><i class="fas fa-ticket-alt"></i> My Bookings</h1>
            <div class="filter-controls">
                <button class="filter-btn active">All</button>
                <button class="filter-btn">Confirmed</button>
                <button class="filter-btn">Pending</button>
                <button class="filter-btn">Cancelled</button>
            </div>
        </div>
        
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle alert-icon"></i>
                <div class="alert-content">
                    <div class="alert-title">Success!</div>
                    <div><?= $_SESSION['message']; unset($_SESSION['message']); ?></div>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle alert-icon"></i>
                <div class="alert-content">
                    <div class="alert-title">Error</div>
                    <div><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="booking-list">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): 
                    $status_class = '';
                    if ($row['booking_status'] == 'Confirmed') {
                        $status_class = 'status-confirmed';
                    } elseif ($row['booking_status'] == 'Pending') {
                        $status_class = 'status-pending';
                    } elseif ($row['booking_status'] == 'Cancelled') {
                        $status_class = 'status-cancelled';
                    }
                    
                    // For demo purposes - in a real app, you'd have museum images in your database
                    $museum_images = [
                        "Victoria Memorial" => "pokemon2.jpg",
                        "Chhatrapati Shivaji Maharaj Vastu Sangrahalaya" => "pokemon.jpg",
                        "Calico Museum of Textiles" => "pokemon1.jpg",
                        "Salar Jung Museum" => "popular5.jpg",
                        "National Museum" => "popular4.jpg",];
                    
                    $museum_image = $museum_images[$row['museum']] ?? "https://images.unsplash.com/photo-1580976606229-2423beaaf4a3?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80";
                ?>
                    <div class="booking-card">
                        <div class="card-image" style="background-image: url('<?= $museum_image ?>');"></div>
                        
                        <div class="card-content">
                            <div class="card-header">
                                <div class="museum-name"><?= htmlspecialchars($row['museum']) ?></div>
                                <div class="booking-status <?= $status_class ?>"><?= htmlspecialchars($row['booking_status']) ?></div>
                            </div>
                            
                            <div class="booking-details">
                                <div class="detail-item">
                                    <span class="detail-label">Visitor Name</span>
                                    <span class="detail-value"><?= htmlspecialchars($row['name']) ?></span>
                                </div>
                                
                                <div class="detail-item">
                                    <span class="detail-label">Visit Date</span>
                                    <span class="detail-value"><?= htmlspecialchars($row['visit_date']) ?></span>
                                </div>
                                
                                <div class="detail-item">
                                    <span class="detail-label">Booking Date</span>
                                    <span class="detail-value"><?= date('M j, Y', strtotime($row['booking_date'])) ?></span>
                                </div>
                                
                                <div class="detail-item">
                                    <span class="detail-label">Total Paid</span>
                                    <span class="detail-value">₹<?= number_format($row['total_price'], 2) ?></span>
                                </div>
                            </div>
                            
                            <div class="ticket-count">
                                <span class="ticket-badge"><i class="fas fa-user"></i> <?= $row['adult_tickets'] ?> Adult<?= $row['adult_tickets'] != 1 ? 's' : '' ?></span>
                                <span class="ticket-badge"><i class="fas fa-child"></i> <?= $row['child_tickets'] ?> Child<?= $row['child_tickets'] != 1 ? 'ren' : '' ?></span>
                            </div>
                            
                            <div class="card-footer">
                                <span class="booking-reference">Ref: <?= htmlspecialchars($row['booking_reference']) ?></span>
                                <div class="booking-actions">
                                    <a href="download-ticket.php?id=<?= $row['id'] ?>" class="action-btn download-btn">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                    <?php if ($row['booking_status'] == 'Pending' || $row['booking_status'] == 'Confirmed'): ?>
                                        <form action="cancel_booking.php" method="POST" style="margin:0;">
                                            <input type="hidden" name="booking_id" value="<?= $row['id'] ?>">
                                            <button type="submit" class="action-btn cancel-btn">
                                                <i class="fas fa-times"></i> Cancel
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="qr-section">
                            <img class="qr-code" src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=<?= urlencode($row['booking_reference']) ?>" alt="QR Code">
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="far fa-calendar-check"></i>
                    </div>
                    <h3>No Bookings Yet</h3>
                    <p class="empty-text">You haven't made any museum bookings yet. Start exploring our collections and book your next cultural adventure!</p>
                    <a href="explore.php" class="explore-btn">
                        <i class="fas fa-compass"></i> Explore Museums
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Enhanced filter functionality with animation
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelector('.filter-btn.active').classList.remove('active');
                this.classList.add('active');
                
                const filter = this.textContent;
                const cards = document.querySelectorAll('.booking-card');
                
                cards.forEach((card, index) => {
                    const status = card.querySelector('.booking-status').textContent;
                    
                    if (filter === 'All' || filter === status) {
                        // Animate card in
                        card.style.display = 'flex';
                        card.style.animation = `fadeIn 0.5s ease-out ${index * 0.05}s forwards`;
                    } else {
                        // Animate card out
                        card.style.animation = `fadeOut 0.3s ease-out forwards`;
                        setTimeout(() => {
                            card.style.display = 'none';
                        }, 300);
                    }
                });
            });
        });

        // Add animation styles dynamically
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(10px); }
                to { opacity: 1; transform: translateY(0); }
            }
            @keyframes fadeOut {
                from { opacity: 1; transform: translateY(0); }
                to { opacity: 0; transform: translateY(10px); }
            }
        `;
        document.head.appendChild(style);

        // Add subtle parallax effect to floating circles
        window.addEventListener('mousemove', (e) => {
            const x = e.clientX / window.innerWidth;
            const y = e.clientY / window.innerHeight;
            
            document.querySelector('.circle-1').style.transform = `translate(${x * 20}px, ${y * 20}px)`;
            document.querySelector('.circle-2').style.transform = `translate(${x * -15}px, ${y * -15}px)`;
        });
    </script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>