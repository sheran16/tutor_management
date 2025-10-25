<?php
session_start();
require('../config/db.php');

if (!isset($_SESSION['student_id'])) {
  header('Location: /tutor_management/student_login.php');
  exit();
}
$student_id = $_SESSION['student_id']; 

// Soft delete
$sql = "UPDATE students SET deleted = 1, deleted_at = NOW() WHERE student_id = ? AND deleted = 0";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $student_id);
$ok = $stmt->execute();
$stmt->close();

// logout after delete
session_destroy();
header("Location: /tutor_management/home.php?account_deleted=" . ($ok ? "1" : "0"));
exit();

