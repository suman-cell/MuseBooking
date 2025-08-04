<?php
session_start();

require_once __DIR__ . '/vendor/autoload.php';
require_once 'db.php';
require_once __DIR__ . '/tcpdf/tcpdf.php';

// Validate login
if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access");
}

$booking_id = $_GET['id'] ?? 0;
$user_id = $_SESSION['user_id'];

// Fetch booking
$stmt = $conn->prepare("SELECT * FROM bookings WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $booking_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("Booking not found");
}

$booking = $result->fetch_assoc();

// Generate QR code URL
$qr_url = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($booking['booking_reference']) . "&choe=UTF-8";

// PDF setup with custom page size (slightly increased height for better layout)
$pdf = new TCPDF('P', 'mm', array(85, 170), true, 'UTF-8', false); // Height increased from 160mm to 170mm
$pdf->SetCreator('Museum Booking System');
$pdf->SetAuthor($booking['museum']);
$pdf->SetTitle('Ticket - ' . $booking['booking_reference']);
$pdf->SetSubject('Museum');

// Remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// Set margins
$pdf->SetMargins(8, 8, 8);
$pdf->SetAutoPageBreak(true, 8);

// Add a page
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', '', 9);

// Add decorative border
$pdf->SetLineWidth(0.5);
$pdf->SetDrawColor(150, 150, 150);
$pdf->RoundedRect(5, 5, 75, 160, 2, '1111', 'D'); // Adjusted to fit the new height

// Museum header with elegant styling
$pdf->SetFillColor(70, 40, 20); // Earthy brown color
$pdf->SetTextColor(255, 255, 255);
$pdf->SetFont('times', 'B', 14);

// Replace long museum name with a shorter version
$display_museum_name = $booking['museum'] === "Chhatrapati Shivaji Maharaj Vastu Sangrahalaya" 
    ? "CSMVS Museum" 
    : $booking['museum'];

// Use MultiCell for long museum names
$pdf->MultiCell(0, 8, strtoupper($display_museum_name), 0, 'C', true);
$pdf->Ln(3);

// Ticket reference with decorative line
$pdf->SetTextColor(70, 40, 20);
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(0, 6, 'ADMISSION TICKET', 0, 1, 'C');
$pdf->SetFont('courier', 'B', 11);
$pdf->Cell(0, 6, $booking['booking_reference'], 0, 1, 'C');
$pdf->SetDrawColor(200, 180, 150);
$pdf->Line(15, $pdf->GetY(), 70, $pdf->GetY());
$pdf->Ln(5);

// Visitor information section with subtle background
$pdf->SetFillColor(245, 240, 230); // Light parchment color
$pdf->SetTextColor(50, 50, 50);
$pdf->SetFont('helvetica', 'B', 9);
$pdf->Cell(0, 7, ' VISITOR DETAILS', 0, 1, 'L', true);
$pdf->SetFillColor(255, 255, 255);

// Visitor details with elegant formatting
$pdf->SetFont('helvetica', '', 8);
$pdf->Cell(25, 6, 'Name:', 0, 0);
$pdf->SetFont('helvetica', 'B', 9);

// Use MultiCell to wrap long names
$pdf->MultiCell(0, 6, $booking['name'], 0, 'L');
$pdf->SetFont('helvetica', '', 8);

$details = [
    'Date' => date('F j, Y', strtotime($booking['visit_date'])),
    'Time' => '10:00 AM - 5:00 PM', // Assuming standard hours
    'Adults' => $booking['adult_tickets'],
    'Children' => $booking['child_tickets'],
    'Total' => '₹' . number_format($booking['total_price'], 2),
    'Status' => ucfirst($booking['booking_status']) // Add booking status
];

foreach ($details as $label => $value) {
    $pdf->Cell(25, 6, $label . ':', 0, 0);
    $pdf->Cell(0, 6, $value, 0, 1);
}

$pdf->Ln(3);

// QR code section with museum-style presentation
$pdf->SetFillColor(245, 240, 230);
$pdf->SetFont('helvetica', 'B', 9);
$pdf->Cell(0, 7, ' ENTRY PASS', 0, 1, 'L', true);
$pdf->SetFillColor(255, 255, 255);

$pdf->SetFont('helvetica', '', 7);
$pdf->MultiCell(40, 4, "Present this code at the entrance for scanning. Valid only on the specified date.", 0, 'L');

// Add QR code with decorative border
$currentY = $pdf->GetY(); // Get the current Y position
$pdf->Image($qr_url, 50, $currentY + 5, 20, 20, 'PNG', '', 'T', false, 300, '', false, false, 1, false, false, false); // Adjusted position
$pdf->SetDrawColor(200, 180, 150);
$pdf->RoundedRect(48, $currentY + 3, 24, 24, 1.5, '1001', 'D'); // Adjusted position to match QR code

$pdf->Ln(30); // Adjusted spacing to ensure footer starts below the QR code

// Museum information footer
$pdf->SetTextColor(100, 100, 100);
$pdf->SetFont('helvetica', 'I', 6);
$pdf->MultiCell(0, 3, "This ticket grants admission to " . $booking['museum'] . ". Please keep this ticket with you at all times during your visit. No re-entry allowed.", 0, 'C');

$pdf->Ln(4); // Slightly increased spacing between footer and contact info

// Contact information
$pdf->SetFont('helvetica', '', 6);
$pdf->Cell(0, 3, 'For assistance: info@museum.example.com | +91 1234567890', 0, 1, 'C');

// Output
$pdf->Output("Museum-Ticket-{$booking['booking_reference']}.pdf", 'D');