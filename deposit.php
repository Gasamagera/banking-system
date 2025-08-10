<?php
session_start();
require 'db.php';

$user_id = $_SESSION['user_id'];

// Check if amount is set in POST
if (!isset($_POST['amount'])) {
    die("Deposit amount not provided.");
}

$amount = $_POST['amount'];

// Validate
if (!is_numeric($amount) || $amount <= 0) {
    die("Invalid deposit amount.");
}

$amount = round(floatval($amount), 2);

$conn->begin_transaction();

try {
    // 1. Update user balance
    $updateBalance = $conn->prepare("UPDATE users SET balance = balance + ? WHERE id = ?");
    $updateBalance->bind_param("di", $amount, $user_id);
    $updateBalance->execute();

    if ($updateBalance->affected_rows === 0) {
        throw new Exception("Failed to update balance.");
    }

    // 2. Insert transaction record with current date
    $insertTxn = $conn->prepare("INSERT INTO transactions (user_id, type, amount, date) VALUES (?, 'deposit', ?, NOW())");
    $insertTxn->bind_param("id", $user_id, $amount);
    $insertTxn->execute();

    $conn->commit();

    // ✅ Show success alert and redirect
    echo "<script>
            alert('Deposit of $amount was successful!');
            window.location.href = 'dashboard.php';
          </script>";

} catch (Exception $e) {
    $conn->rollback();
    echo "Error: " . $e->getMessage();
}

$conn->close();
?>
