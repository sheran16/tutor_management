<?php
require('config/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST['full_name'] ?? '');
    $address   = trim($_POST['address'] ?? '');
    $dob       = trim($_POST['dob'] ?? '');
    $gradeID   = trim($_POST['gradeID'] ?? '');
    $contact   = trim($_POST['contact'] ?? '');
    $User_name = trim($_POST['User_name'] ?? '');
    $password_plain = $_POST['password'] ?? '';

    // required checks
    if ($full_name === '' || $address === '' || $dob === '' || $gradeID === '' || $contact === '' || $User_name === '' || $password_plain === '') {
        echo "<script>alert('Please fill all required fields.'); window.history.back();</script>";
        exit();
    }

    //Server-side DOB & grade validation
    $gradeAgeMap = [
        "1" => ['min' => 5, 'max' => 7],
        "2" => ['min' => 6, 'max' => 8],
        "3" => ['min' => 7, 'max' => 9],
        "4" => ['min' => 8, 'max' => 10],
        "5" => ['min' => 9, 'max' => 11],
    ];

    // validate dob format
    $dobObj = DateTime::createFromFormat('Y-m-d', $dob);
    if (!$dobObj) {
        echo "<script>alert('Invalid DOB format. Use YYYY-MM-DD.'); window.history.back();</script>";
        exit();
    }

    $now = new DateTime('now');
    if ($dobObj > $now) {
        echo "<script>alert('Date of birth cannot be in the future.'); window.history.back();</script>";
        exit();
    }

    // compute age
    $age = $now->diff($dobObj)->y;

    if (!isset($gradeAgeMap[$gradeID])) {
        echo "<script>alert('Invalid grade selected.'); window.history.back();</script>";
        exit();
    }

    $range = $gradeAgeMap[$gradeID];
    if ($age < $range['min'] || $age > $range['max']) {
        echo "<script>alert('Selected grade does not match DOB (calculated age: {$age}). Expected age for Grade {$gradeID} is {$range['min']} - {$range['max']}.'); window.history.back();</script>";
        exit();
    }

    // === hash password ===
    $hashed_password = password_hash($password_plain, PASSWORD_DEFAULT);

    $tutor_id = 1;  
   
$sql = "INSERT INTO students (full_name, address, dob, gradeID, contact,User_name, password, tutor_id)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if($stmt === false)
{
    die("SQL Error: ".$conn ->error);
}

   $stmt->bind_param("sssisssi", $full_name, $address, $dob, $gradeID, $contact, $User_name, $hashed_password, $tutor_id);

    if ($stmt->execute()) {
       // echo " Student registered successfully!";
       header("Location: student_login.php?signup=success");
        exit();
    } else {
        echo "<script>alert('Database error: " . addslashes($stmt->error) . "'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

