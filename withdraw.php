<?php
session_start();
require 'db.php';

// ✅ Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to withdraw.");
}

$user_id = $_SESSION['user_id'];

// ✅ Check if amount is set
if (!isset($_POST['amount'])) {
    die("Withdraw amount not provided.");
}

$amount = $_POST['amount'];

// ✅ Validate amount
if (!is_numeric($amount) || $amount <= 0) {
    die("Invalid withdraw amount.");
}

$amount = round(floatval($amount), 2);

$conn->begin_transaction();

try {
    // 1. Check current balance
    $check = $conn->prepare("SELECT balance FROM users WHERE id = ?");
    $check->bind_param("i", $user_id);
    $check->execute();
    $result = $check->get_result();
    $row = $result->fetch_assoc();

    if (!$row || $row['balance'] < $amount) {
        throw new Exception("Insufficient balance.");
    }

    // 2. Deduct from balance
    $update = $conn->prepare("UPDATE users SET balance = balance - ? WHERE id = ?");
    $update->bind_param("di", $amount, $user_id);
    $update->execute();

    // 3. Insert transaction
    $type = 'withdrawal'; // ✅ Consistent with your dashboard logic
    $insert = $conn->prepare("INSERT INTO transactions (user_id, type, amount, date) VALUES (?, ?, ?, NOW())");
    $insert->bind_param("isd", $user_id, $type, $amount);
    $insert->execute();

    // ✅ Commit changes
    $conn->commit();

    // ✅ Redirect with alert
    echo "<script>
        alert('Withdrawal of $amount was successful!');
        window.location.href = 'dashboard.php';
    </script>";

} catch (Exception $e) {
    $conn->rollback();
    echo "Error: " . $e->getMessage();
}

$conn->close();
?>
