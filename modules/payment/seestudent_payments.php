<?php
session_start();
require (dirname(__DIR__, 2) . '/config/db.php'); // Adjust path depending on folder structure

// Only allow logged-in students
if (!isset($_SESSION['student_id'])) {
    header("Location: ../../Login/login.php");
    exit;
}

$student_id   = $_SESSION['student_id'];
$student_name = $_SESSION['user_name'];

// Fetch completed payments (Paid)
$sql_paid = "SELECT payment_date, amount, grade, status 
             FROM student_payments_new 
             WHERE student_id = ? AND status = 'Paid' 
             ORDER BY payment_date DESC";

$stmt_paid = $conn->prepare($sql_paid);
$stmt_paid->bind_param("s", $student_id);
$stmt_paid->execute();
$result_paid = $stmt_paid->get_result();

// Fetch pending payments (Remainders)
$sql_pending = "SELECT payment_date, amount, grade, status 
                FROM student_payments_new  
                WHERE student_id = ? AND status = 'Pending' 
                ORDER BY payment_date ASC";

$stmt_pending = $conn->prepare($sql_pending);
$stmt_pending->bind_param("s", $student_id);
$stmt_pending->execute();
$result_pending = $stmt_pending->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Payments | Smart-Kids</title>
  <link rel="stylesheet" href="../../assets/css/style1.css">
  <style>
    html, body {
      height: 100%;
      margin: 0;
      display: flex;
      flex-direction: column;
      font-family: Arial, sans-serif;
      background: url("../../images/bgstudent.jpg") no-repeat center center fixed;
      background-size: cover;
    }
    .container { 
      flex: 1; /* pushes footer down */
      max-width: 900px; 
      margin: 50px auto; 
      background: #f9f9f9; 
      padding: 20px; 
      border-radius: 10px; 
    }
    h2, h3 { text-align: center; margin-bottom: 20px; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
    table th, table td { border: 1px solid #ccc; padding: 10px; text-align: center; }
    table th { background: #333; color: #fff; }
    .remainder { background: #ffecec; border: 1px solid #ff6b6b; padding: 15px; border-radius: 8px; }
    .success { color: green; }
    .pending { color: red; }
    footer {
      background: #333;
      color: #fff;
      text-align: center;
      padding: 10px;
      margin-top: auto; /* keeps footer at bottom */
    }
  </style>
  <style>
    body {
      background: url("/tutor_management/student_Background.jpg") no-repeat center center fixed;
      background-size: cover;
      background-color: #f2f2f7;
    }
  </style>
</head>
<body>
<?php require '../../includes/header.php'; ?>

<div class="container">
  <h2>Welcome, <?php echo htmlspecialchars($student_name); ?> 👋</h2>

  <h3>Completed Payments</h3>
  <table>
    <tr>
      <th>Payment Date</th>
      <th>Amount</th>
      <th>Grade</th>
      <th>Status</th>
    </tr>
    <?php if ($result_paid->num_rows > 0): ?>
      <?php while ($row = $result_paid->fetch_assoc()): ?>
        <tr>
          <td><?php echo $row['payment_date']; ?></td>
          <td><?php echo $row['amount']; ?></td>
          <td><?php echo $row['grade']; ?></td>
          <td class="success"><?php echo $row['status']; ?></td>
        </tr>
      <?php endwhile; ?>
    <?php else: ?>
      <tr><td colspan="4">No completed payments found.</td></tr>
    <?php endif; ?>
  </table>

  <div class="remainder">
    <h3>Remainders (Pending Payments)</h3>
    <?php if ($result_pending->num_rows > 0): ?>
      <table>
        <tr>
          <th>Payment Date</th>
          <th>Amount</th>
          <th>Grade</th>
          <th>Status</th>
        </tr>
        <?php while ($row = $result_pending->fetch_assoc()): ?>
          <tr>
            <td><?php echo $row['payment_date']; ?></td>
            <td><?php echo $row['amount']; ?></td>
            <td><?php echo $row['grade']; ?></td>
            <td class="pending"><?php echo $row['status']; ?></td>
          </tr>
        <?php endwhile; ?>
      </table>
    <?php else: ?>
      <p>🎉 No pending payments! You’re up-to-date.</p>
    <?php endif; ?>
  </div>
</div>

<?php require '../../includes/footer.php'; ?>
<script src="../../assets/js/script.js"></script>
</body>
</html>

<?php
// Close statements and connection
if (isset($stmt_paid) && $stmt_paid) $stmt_paid->close();
if (isset($stmt_pending) && $stmt_pending) $stmt_pending->close();
if (isset($conn) && $conn) $conn->close();
?>
