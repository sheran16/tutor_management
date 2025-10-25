<?php
session_start();
require('../config/db.php');

if (!isset($_SESSION['tutor_id'])) {
  header('Location: /tutor_management/tutor_login.php');
  exit();
}
$tutor_id = (int)$_SESSION['tutor_id'];

// fetch tutor (no delete logic since you removed delete)
$sql = "SELECT tutor_id, full_name, user_name, contact_no, dob, address, email, password
        FROM tutor
        WHERE tutor_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $tutor_id);
$stmt->execute();
$tutor = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$tutor) { die("Tutor not found."); }
?>
<html>
<head>
  <title>View Tutor Profile</title>
  <link rel="stylesheet" href="/tutor_management/assets/css/style1.css">
  <link rel="stylesheet" href="/tutor_management/assets/css/tutor_profile.css">
  <style>
    body {
      background: url("/tutor_management/tutorBackground.jpg") no-repeat center center fixed;
      background-size: cover;
      background-color: #f2f2f7; /* fallback */
    }
  </style>
</head>
<body>
<?php include '../includes/headert.php'; ?>

<main>
  <div class="wrap">
    <h2>My Profile</h2>

    <div class="row"><label>Tutor ID:</label>   <span class="value"><?= $tutor['tutor_id'] ?></span></div>
    <div class="row"><label>Full Name:</label>  <span class="value"><?= $tutor['full_name'] ?></span></div>
    <div class="row"><label>Username:</label>   <span class="value"><?= $tutor['user_name'] ?></span></div>
    <div class="row"><label>Contact No:</label> <span class="value"><?= $tutor['contact_no'] ?></span></div>
    <div class="row"><label>DOB:</label>        <span class="value"><?= $tutor['dob'] ?></span></div>
    <div class="row"><label>Address:</label>    <span class="value"><?= nl2br($tutor['address']) ?></span></div>
    <div class="row"><label>Email:</label>      <span class="value"><?= $tutor['email'] ?></span></div>
    <div class="row"><label>Password:</label>   <span class="value">••••••••</span></div>

    <div class="actions">
      <a class="btn primary" href="/tutor_management/includes/tutorupdate.php">Update</a>
      <a class="btn muted" href="/tutor_management/modules/enrollment/enrollment.php">Back</a>
    </div>
  </div>
</main>

<?php include '../includes/footer.php'; ?>
<script src="/tutor_management/assets/js/tutor_profile.js"></script>
<script src="/tutor_management/assets/js/script.js"></script>
</body>
</html>

