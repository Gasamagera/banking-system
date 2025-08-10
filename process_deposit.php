<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit();
}

require 'db.php';

$user_id = $_SESSION['user_id'];
$amount = $_POST['amount'];

if ($amount <= 0) {
    die("Invalid deposit amount.");
}

// 1. Update balance
$stmt = $conn->prepare("UPDATE users SET balance = balance + ? WHERE id = ?");
$stmt->bind_param("di", $amount, $user_id);
$stmt->execute();

// 2. Add transaction
$stmt = $conn->prepare("INSERT INTO transactions (user_id, type, amount, date) VALUES (?, 'deposit', ?, NOW())");
$stmt->bind_param("id", $user_id, $amount);
$stmt->execute();

// Redirect back to dashboard
header("Location: dashboard.php");
exit();
?>
