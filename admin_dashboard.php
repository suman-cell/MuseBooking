<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin.php");
    exit();
}
include 'db.php';

// Filters
$search = $_GET['search'] ?? '';
$status = $_GET['status'] ?? '';
$conditions = [];

if ($search) {
    $search = mysqli_real_escape_string($conn, $search);
    $conditions[] = "(name LIKE '%$search%' OR email LIKE '%$search%' OR booking_reference LIKE '%$search%')";
}
if ($status) {
    $status = mysqli_real_escape_string($conn, $status);
    $conditions[] = "booking_status = '$status'";
}
$whereClause = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';

// Data
$query = "SELECT * FROM bookings $whereClause ORDER BY booking_date DESC";
$result = mysqli_query($conn, $query);

$summary = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) as total, 
     SUM(CASE WHEN booking_status = 'Confirmed' THEN 1 ELSE 0 END) as confirmed,
     SUM(total_price) as revenue FROM bookings"));
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --accent: #4895ef;
            --dark: #1b263b;
            --light: #f8f9fa;
            --success: #4cc9f0;
            --danger: #f72585;
            --warning: #f8961e;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        
        .container {
            max-width: 95%;
            margin: 0 auto;
            padding: 20px;
        }
        
        .logout {
            float: right;
            margin-bottom: 20px;
        }
        
        .logout a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            background: var(--danger);
            padding: 10px 20px;
            border-radius: 30px;
            box-shadow: 0 4px 15px rgba(247, 37, 133, 0.3);
            transition: all 0.3s ease;
            display: inline-block;
        }
        
        .logout a:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(247, 37, 133, 0.4);
        }
        
        h2 {
            color: var(--dark);
            font-weight: 600;
            margin-bottom: 30px;
            font-size: 28px;
            position: relative;
            display: inline-block;
        }
        
        h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 60px;
            height: 4px;
            background: var(--primary);
            border-radius: 2px;
        }
        
        .summary {
            display: flex;
            gap: 25px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        
        .summary div {
            background: white;
            color: var(--dark);
            padding: 25px;
            flex: 1;
            min-width: 200px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            transform-style: preserve-3d;
            position: relative;
        }
        
        .summary div::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, var(--primary), var(--accent));
            border-radius: 15px;
            z-index: -1;
            transform: translateZ(-1px);
            opacity: 0.8;
        }
        
        .summary div:hover {
            transform: translateY(-10px) rotateX(5deg);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }
        
        .summary div span {
            display: block;
            font-size: 36px;
            font-weight: 600;
            margin-top: 10px;
            color: var(--primary);
        }
        
        .filters {
            background: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .filters form {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: center;
        }
        
        input, select {
            padding: 12px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 30px;
            font-family: 'Poppins', sans-serif;
            outline: none;
            transition: all 0.3s ease;
            flex: 1;
            min-width: 200px;
        }
        
        input:focus, select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
        }
        
        button {
            padding: 12px 25px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(67, 97, 238, 0.3);
        }
        
        button:hover {
            background: var(--secondary);
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(67, 97, 238, 0.4);
        }
        
        .action-buttons {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        
        .action-btn {
            padding: 12px 25px;
            border-radius: 30px;
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .action-btn:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }
        
        .export-btn {
            background: var(--success);
        }
        
        .charts-btn {
            background: var(--accent);
        }
        
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        th, td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #e0e0e0;
        }
        
        th {
            background: linear-gradient(45deg, var(--primary), var(--secondary));
            color: white;
            font-weight: 500;
            position: sticky;
            top: 0;
        }
        
        tr:last-child td {
            border-bottom: none;
        }
        
        tr:hover td {
            background: rgba(67, 97, 238, 0.05);
            transform: scale(1.01);
            transition: all 0.2s ease;
        }
        
        td {
            transition: all 0.2s ease;
        }
        
        .status {
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 500;
            font-size: 12px;
        }
        
        .Confirmed {
            background: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }
        
        .Pending {
            background: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }
        
        .Cancelled {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }
        
        .action-links a {
            color: var(--primary);
            text-decoration: none;
            margin: 0 5px;
            padding: 5px 10px;
            border-radius: 5px;
            transition: all 0.2s ease;
        }
        
        .action-links a:hover {
            background: rgba(67, 97, 238, 0.1);
            text-decoration: underline;
        }
        
        .action-links a:last-child {
            color: var(--danger);
        }
        
        .no-data {
            text-align: center;
            padding: 50px;
            color: #6c757d;
        }
        
        @media (max-width: 768px) {
            .summary {
                flex-direction: column;
            }
            
            .filters form {
                flex-direction: column;
                align-items: stretch;
            }
            
            table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logout">
            <a href="logout.php">Logout</a>
        </div>

        <h2>Admin Dashboard</h2>

        <div class="summary">
            <div>
                <span>📊</span>
                Total Bookings
                <span><?= $summary['total'] ?></span>
            </div>
            <div>
                <span>✅</span>
                Confirmed Bookings
                <span><?= $summary['confirmed'] ?></span>
            </div>
            <div>
                <span>💰</span>
                Total Revenue
                <span>₹<?= number_format($summary['revenue']) ?></span>
            </div>
        </div>

        <div class="filters">
            <form method="GET">
                <input type="text" name="search" placeholder="Search name/email/ref" value="<?= htmlspecialchars($search) ?>">
                <select name="status">
                    <option value="">All Statuses</option>
                    <option value="Confirmed" <?= $status == 'Confirmed' ? 'selected' : '' ?>>Confirmed</option>
                    <option value="Pending" <?= $status == 'Pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="Cancelled" <?= $status == 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                </select>
                <button type="submit">Filter</button>
            </form>
        </div>

        <div class="action-buttons">
            <a href="export-bookings.php" class="action-btn export-btn">
                📥 Export Bookings (CSV)
            </a>
            <a href="admin-charts.php" class="action-btn charts-btn">
                📊 View Charts
            </a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Visit</th>
                    <th>Adults</th><th>Children</th><th>Total</th><th>Ref</th><th>Status</th>
                    <th>Date</th><th>Museum</th><th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= $row['phone'] ?></td>
                            <td><?= $row['visit_date'] ?></td>
                            <td><?= $row['adult_tickets'] ?></td>
                            <td><?= $row['child_tickets'] ?></td>
                            <td>₹<?= $row['total_price'] ?></td>
                            <td><?= $row['booking_reference'] ?></td>
                            <td><span class="status <?= $row['booking_status'] ?>"><?= $row['booking_status'] ?></span></td>
                            <td><?= $row['booking_date'] ?></td>
                            <td><?= htmlspecialchars($row['museum']) ?></td>
                            <td class="action-links">
                                <a href="edit-booking.php?id=<?= $row['id'] ?>">Edit</a>
                                <a href="delete-booking.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete this booking?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="13" class="no-data">No bookings found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>