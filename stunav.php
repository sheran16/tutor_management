<html>
<head>
  <title>Private Tutor Management System</title>
  <link rel="stylesheet" href="assets/css/style1.css">
  <link rel="stylesheet" href="assets/css/stunav.css">
  <style>
    body {
      background: url("student_Background.jpg") no-repeat center center fixed;
      background-size: cover;
      background-color: #f2f2f7; /* default */
    }
  </style>
</head>
<body>

<?php 
require 'includes/auth_student.php';
require 'includes/header.php'; 
require 'config/db.php';

// student information
$student_name = "Student"; // Default 
if (isset($_SESSION['student_id'])) {
    $student_id = (int)$_SESSION['student_id'];
    $query = "SELECT full_name FROM students WHERE student_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $student_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        $student_name = $row['full_name'];
    }
    mysqli_stmt_close($stmt);
}
?>
  <main class="main-content">
    <div class="welcome">
      <h4>Welcome Back, <?php echo htmlspecialchars($student_name); ?>!</h4>
      <p>Each student will shine the best in future.</p>
    </div>

    <div class="dashboard">
      <a href="modules/classSlots/student_dashboardCS.php" class="card-btn red">Class Slot</a>
      <a href="modules/assessments/student_dashboard.php" class="card-btn green">Assessment</a>
      <a href="modules/payment/seestudent_payments.php" class="card-btn blue">Payment</a>
    </div>
  </main>
<a href = "/tutor_management/modules/feedback/feedback.php" class="feedback-btn"> Feedback</a>
<?php require 'includes/footer.php'; ?>

<script src="assets/js/script.js"></script>
</body>
</html>
