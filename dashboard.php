<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit();
}

require 'db.php';

$user_id = $_SESSION['user_id'];

// Get user details
$stmt = $conn->prepare("SELECT full_name, balance FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$fullName = $user['full_name'];
$balance = $user['balance'];

// Get transactions
$stmt = $conn->prepare("
    SELECT t.type, t.amount, t.date
    FROM transactions t
    WHERE t.user_id = ?
    ORDER BY t.date DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$transactions = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Your Bank Dashboard</title>
  <link rel="stylesheet" href="dashboard.css">
</head>
<body>
  <div class="container">
    <h1>Welcome, <?php echo htmlspecialchars($fullName); ?>!</h1>
    <p class="balance">Current Balance: <span>$<?php echo number_format($balance, 2); ?></span></p>

    <div class="actions">
      <a href="deposit.html"><button type="button">Deposit</button></a>
      <a href="withdraw.html"><button type="button">Withdraw</button></a>
      <form action="logout.php" method="POST" style="display:inline;">
        <a href="logout.php" class="logout-btn">Logout</a>
      </form>
    </div>

    <h2>Recent Transactions</h2>
    <table>
  <thead>
    <tr>
      <th>Type</th>
      <th>Amount</th>
      <th>Date</th>
    </tr>
  </thead>
  <tbody>
    <?php while ($row = $transactions->fetch_assoc()): ?>
      <?php
        $type = strtolower($row['type']);
        $isWithdrawal = $type === 'withdrawal';
      ?>
      <tr class="<?php echo $isWithdrawal ? 'withdrawal' : ''; ?>">
        <td><?php echo htmlspecialchars(ucfirst($type)); ?></td>

        <!-- ✅ FIXED this part: only one <td> for Amount -->
        <td class="amount <?php echo $isWithdrawal ? 'withdrawal' : 'deposit'; ?>">
          <?php echo $isWithdrawal ? "-$" : "$"; ?>
          <?php echo number_format($row['amount'], 2); ?>
        </td>

        <td><?php echo htmlspecialchars($row['date']); ?></td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>

  </div>
</body>
</html>
