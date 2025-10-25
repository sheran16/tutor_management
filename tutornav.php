<html>
<head>
  <title>Private Tutor Management System</title>
  <link rel="stylesheet" href="assets/css/style1.css">
  <link rel="stylesheet" href="assets/css/tutornav.css">
  <style>
    body {
      background: url("tutorBackground.jpg") no-repeat center center fixed;
      background-size: cover;
      background-color: #f2f2f7; /* default */
    }
  </style>
</head>
<body>

<?php 
require 'includes/auth_tutor.php'; 
require 'includes/headert.php'; 
require 'config/db.php';

$tutor_name = "Tutor"; 
if (isset($_SESSION['tutor_id'])) {
    $tutor_id = (int)$_SESSION['tutor_id'];
    $query = "SELECT full_name FROM tutor WHERE tutor_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $tutor_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        $tutor_name = $row['full_name'];
    }
    mysqli_stmt_close($stmt);
}
?>
  <main class="main-content">
    <div class="welcome">
      <h4>Welcome Back, Ms. <?php echo htmlspecialchars($tutor_name); ?>!</h4>
      <p>Shaping bright futures, one student at a time.</p>
    </div>

    <div class="dashboard">
      <a href="modules/enrollment/enrollment.php" class="card-btn green">Enrollment</a>
      <a href="modules/classSlots/tutor_dashboardCS.php" class="card-btn blue">Class Slots</a>
      <a href="modules/assessments/tutor_dashboard.php" class="card-btn red">Assessments</a>
      <a href="modules/feedback/tutor_feedback.php" class="card-btn purple">Reviews</a>
      <a href="modules/payment/admin_dashboard.php" class="card-btn black">Fee & Payment</a>
    </div>
  </main>

<?php require 'includes/footer.php'; ?>
<script src="assets/js/tutornav.js"></script>
</body>
</html>
