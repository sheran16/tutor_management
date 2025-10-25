<?php
require (dirname(__DIR__, 2) . '/config/db.php');

// Check if ID is passed
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Payment ID is required.");
}

$id = $_GET['id'];

// Fetch payment record
$sql = "SELECT * FROM student_payments_new WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Payment record not found.");
}

$payment = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $paid_amount = $_POST['paid_amount'];
    $status = $_POST['status'];

    $update_sql = "UPDATE student_payments_new SET paid_amount = ?, status = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("dsi", $paid_amount, $status, $id);

    if ($update_stmt->execute()) {
        $success_msg = "Payment updated successfully!";
        $stmt->execute();
        $payment = $stmt->get_result()->fetch_assoc();
    } else {
        $error_msg = "Error updating payment: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Payment</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="/tutor_management/assets/css/payment.css">
<link rel="stylesheet" href="css/admin.css">
 <style>
    body {
      background: url("/tutor_management/tutorBackground.jpg") no-repeat center center fixed;
      background-size: cover;
      background-color: #f2f2f7; /* default */
    }
  </style>
</head>
<body>

<?php require("../../includes/headert.php"); ?>

<!-- 🔹 MAIN CONTENT -->
<div class="admin-container">
    <div class="admin-header">
        <h1><i class="fas fa-edit"></i> Edit Payment</h1>

    </div>

    <?php if (isset($success_msg)) echo "<div class='success-msg'>✅ $success_msg</div>"; ?>
    <?php if (isset($error_msg)) echo "<div class='error-msg'>❌ $error_msg</div>"; ?>

    <div class="form-container">
        <form action="" method="POST" class="payment-form">

            <div class="form-group">
                <label>Student ID:</label>
                <input type="text" value="<?php echo htmlspecialchars($payment['student_id']); ?>" class="form-control" readonly>
            </div>

            <div class="form-group">
                <label>Student Name:</label>
                <input type="text" value="<?php echo htmlspecialchars($payment['student_name']); ?>" class="form-control" readonly>
            </div>

            <div class="form-group">
                <label>Grade:</label>
                <input type="text" value="<?php echo htmlspecialchars($payment['grade']); ?>" class="form-control" readonly>
            </div>

            <div class="form-group">
                <label>Payment Month:</label>
                <input type="text" value="<?php echo htmlspecialchars($payment['month']); ?>" class="form-control" readonly>
            </div>

            <div class="form-group">
                <label>Payment Date:</label>
                <input type="date" value="<?php echo htmlspecialchars($payment['payment_date']); ?>" class="form-control" readonly>
            </div>

            <div class="form-group">
                <label>Fixed Amount (LKR):</label>
                <input type="number" value="<?php echo htmlspecialchars($payment['amount']); ?>" class="form-control" readonly>
            </div>

            <div class="form-group">
                <label>Paid Amount (LKR):</label>
                <input id="paid_amount" type="number" name="paid_amount" value="<?php echo htmlspecialchars($payment['paid_amount']); ?>" class="form-control" required step="0.01" min="0">
            </div>

            <div class="form-group">
                <label>Amount To Be Paid (LKR):</label>
                <input id="due_amount" type="number" value="<?php echo htmlspecialchars(number_format((float)$payment['amount'] - (float)$payment['paid_amount'], 2, '.', '')); ?>" class="form-control" readonly>
            </div>

            <div class="form-group">
                <label>Status:</label>
                <select name="status" class="form-control" required>
                    <option value="Pending" <?php if($payment['status']=='Pending') echo 'selected'; ?>>Pending</option>
                    <option value="Paid" <?php if($payment['status']=='Paid') echo 'selected'; ?>>Paid</option>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="insert-btn"><i class="fas fa-save"></i> Update Payment</button>
                <a href="/tutor_management/modules/payment/admin_dashboard.php" class="insert-btn" style="background:#555;">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </form>
    </div>
</div>

<!-- 🔹 SAME FOOTER -->
<footer class="footer">
  <div class="footer-item">
    <img src="/tutor_management/images/phone.png" alt="Phone" class="footer-icon" />
    <span>+94 77 123 4567</span>
  </div>
  <div class="footer-item">
    <img src="/tutor_management/images/email.png" alt="Email" class="footer-icon" />
    <span>info@SmartKids.lk</span>
  </div>
  <div class="footer-item">
    <img src="/tutor_management/images/letter.png" alt="Letter" class="footer-icon" />
    <span>2025 Smart Kids</span>
  </div>
</footer>
<script src="../../assets/js/script.js"></script>
<script>
// Recalculate due amount when paid amount changes
document.addEventListener('DOMContentLoaded', function () {
    var fixedInput = document.querySelector('input[readonly][value]');
    // Better to get fixed amount from the fixed amount input explicitly by selecting the correct element
    var fixedAmountInput = document.querySelectorAll('input[readonly]')[1];
    var paidInput = document.getElementById('paid_amount');
    var dueInput = document.getElementById('due_amount');

    function parseNumber(v) {
        var n = parseFloat(String(v).replace(/[^0-9.-]+/g, ''));
        return isNaN(n) ? 0 : n;
    }

    function updateDue() {
        var fixed = parseNumber(fixedAmountInput.value);
        var paid = parseNumber(paidInput.value);
        var due = fixed - paid;
        if (due < 0) due = 0;
        dueInput.value = due.toFixed(2);
    }

    if (paidInput) {
        paidInput.addEventListener('input', updateDue);
    }
});
</script>
<script src="../../assets/js/script.js"></script>
</body>
</html>
