<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form values
    $_SESSION['name'] = $_POST['name'] ?? '';
    $_SESSION['email'] = $_POST['email'] ?? '';
    $_SESSION['phone'] = $_POST['phone'] ?? '';
    $_SESSION['visit_date'] = $_POST['visit_date'] ?? '';
    $_SESSION['museum'] = $_POST['museum'] ?? '';
    $_SESSION['adults'] = (int) ($_POST['adults'] ?? 0);
    $_SESSION['children'] = (int) ($_POST['children'] ?? 0);

    // Calculate total price (example logic: ₹100 per adult, ₹50 per child)
    $total_price = ($_SESSION['adults'] * 100) + ($_SESSION['children'] * 50);
    $_SESSION['total'] = $total_price;

    // Redirect to payment page
    header("Location: payment-page.php"); // change as needed
    exit();
}
?>
