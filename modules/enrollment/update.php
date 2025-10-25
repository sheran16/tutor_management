<?php
require(__DIR__ . '/../../config/db.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: /tutor_management/modules/enrollment/enrollment.php");
  exit;
}

$student_id = $_POST['student_id'] ?? '';
$full_name  = $_POST['full_name']  ?? '';
$address    = $_POST['address']    ?? '';
$dob        = $_POST['dob']        ?? '';
$gradeID    = isset($_POST['gradeID']) ? (int)$_POST['gradeID'] : 0;  // cast to int
$contact    = $_POST['contact']    ?? '';

if ($student_id === '' || $full_name === '' || $address === '' || $dob === '' || $gradeID === 0 || $contact === '') {
  header("Location: /tutor_management/modules/enrollment/enrollment.php?updated=0");
  exit;
}

if ($gradeID < 1 || $gradeID > 5) { 
  header("Location: /tutor_management/modules/enrollment/enrollment.php?updated=0&reason=grade_range");
  exit;
}

$stmt = $conn->prepare("UPDATE students SET full_name=?, address=?, dob=?, gradeID=?, contact=? WHERE student_id=?");
$stmt->bind_param("sssiss", $full_name, $address, $dob, $gradeID, $contact, $student_id);
$ok = $stmt->execute();

header("Location: /tutor_management/modules/enrollment/enrollment.php?updated=" . ($ok ? "1" : "0"));
exit;
