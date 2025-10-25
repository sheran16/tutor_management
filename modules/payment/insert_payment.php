<?php 
require (dirname(__DIR__, 2) . '/config/db.php');

// Collect form data
$student_id   = $_POST["student_id"];
$name         = $_POST["student_name"];
$date         = $_POST["payment_date"];
$amount       = $_POST["amount"];
$grade        = $_POST["grade"];
$paid_amount  = $_POST["paid_amount"];
$month        = $_POST["payment_month"]; // from form dropdown

// Initialize messages
$success_msg = "";
$error_msg   = "";

// Determine payment status automatically
if ($paid_amount >= $amount) {
    $status = "Paid";
} elseif ($paid_amount > 0 && $paid_amount < $amount) {
    $status = "Pending";
} else {
    $status = "Incomplete";
}

// ✅ Check if this student already has a payment for the same month
$check_sql = "SELECT * FROM student_payments_new 
              WHERE student_id = '$student_id' 
              AND month = '$month'";
$check_result = $conn->query($check_sql);

if ($check_result && $check_result->num_rows > 0) {
    // ❌ Payment already recorded for that month
    $error_msg = "<div class='error-msg'>❌ Error: Payment already exists for <b>$month</b> (Student ID: $student_id)</div>";
} else {
    // ✅ Insert new payment record
    $sql = "INSERT INTO student_payments_new 
                (student_id, student_name, grade, month, payment_date, amount, paid_amount, status) 
            VALUES 
                ('$student_id', '$name', '$grade', '$month', '$date', '$amount', '$paid_amount', '$status')";

    if ($conn->query($sql)) {
        $success_msg = "<div class='success-msg'>✅ Payment added successfully for Student ID: <b>$student_id</b> for <b>$month</b>.</div>";
    } else {
        $error_msg = "<div class='error-msg'>❌ Database Error: " . $conn->error . "</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Processing</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/tutor_management/assets/css/payment.css">
    <style>
    body {
      background: url("/tutor_management/tutorBackground.jpg") no-repeat center center fixed;
      background-size: cover;
      background-color: #f2f2f7; /* fallback */
    }
  </style>
</head>
<body>
   <?php require("../../includes/headert.php"); ?>

    <div class="container" style="min-height: 70vh;">
        <div class="form-header">
            <h1><i class="fas fa-credit-card"></i> Payment Processing</h1>
        </div>

        <div class="form-container">
            <?php 
            if(!empty($success_msg)) {
                echo $success_msg;
            } else if(!empty($error_msg)) {
                echo $error_msg;
            }
            ?>
            <div class="action-buttons" style="display: flex; gap: 10px; margin-top: 20px;">
                <a href="PaymentInsert.php" class="insert-btn" style="flex: 1; text-align: center;">
                    <i class="fas fa-plus-circle"></i> Add Another Payment
                </a>
                <a href="admin_dashboard.php" class="view-btn" style="flex: 1; text-align: center;">
                    <i class="fas fa-list"></i> View All Payments
                </a>
            </div>
        </div>
    </div>

    <?php require '../../includes/footer.php'; ?>
    <script src="/tutor_management/assets/js/scriptPayment.js"></script>
    <script src="../../assets/js/script.js"></script>
</body>
</html>
