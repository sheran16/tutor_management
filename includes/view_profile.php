<?php
session_start();
require('../config/db.php');

if (!isset($_SESSION['student_id'])) {
  header('Location: /tutor_management/student_login.php');
  exit();
}
$student_id = $_SESSION['student_id']; 

// students details
$sql = "SELECT student_id, full_name, address, dob, gradeID, contact, User_name, password, tutor_id
        FROM students
        WHERE student_id = ? AND deleted = 0";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $student_id);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$student) { die("Student not found or account deleted."); }
?>
<html>
<head>
  <title>View Profile</title>
  <link rel="stylesheet" href="/tutor_management/assets/css/style1.css">
  <link rel="stylesheet" href="/tutor_management/assets/css/profile.css">
  <style>
    body {
      background: url("/tutor_management/student_Background.jpg") no-repeat center center fixed;
      background-size: cover;
      background-color: #f2f2f7;
    }
  </style>
</head>
<body>
<?php include '../includes/header.php'; ?>  

<main>
  <div class="wrap">
    <h2>My Profile</h2>

    <div class="row"><label>Student ID:</label> <span class="value"><?= $student['student_id'] ?></span></div>
    <div class="row"><label>Full Name:</label>  <span class="value"><?= $student['full_name'] ?></span></div>
    <div class="row"><label>Address:</label>    <span class="value"><?= nl2br($student['address']) ?></span></div>
    <div class="row"><label>DOB:</label>        <span class="value"><?= $student['dob'] ?></span></div>
    <div class="row"><label>Grade ID:</label>   <span class="value"><?= $student['gradeID'] ?></span></div>
    <div class="row"><label>Contact:</label>    <span class="value"><?= $student['contact'] ?></span></div>
    <div class="row"><label>Username:</label>   <span class="value"><?= $student['User_name'] ?></span></div>
    <div class="row"><label>Password:</label>   <span class="value">••••••••</span></div>
   <!-- <div class="row"><label>Tutor ID:</label>   <span class="value"><?= $student['tutor_id'] ?? '-' ?></span></div> -->

    <div class="actions">
      <a class="btn primary" href="/tutor_management/includes/update_profile.php">Update</a>
        <form action="/tutor_management/includes/delete_profile.php" method="POST" class="inline js-confirm" data-confirm="Delete your account?">
          <button type="submit" class="btn danger">Delete</button>
        </form>
      <a class="btn muted" href="/tutor_management/stunav.php">Back</a>
    </div>
  </div>
</main>

<?php include '../includes/footer.php'; ?> 
<script src="/tutor_management/assets/js/profile.js"></script>
<script src="/tutor_management/assets/js/script.js"></script>
</body>
</html>

