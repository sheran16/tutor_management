<?php
require 'config.php';
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

if(!isset($input['student_id'])) {
    echo json_encode(['success'=>false, 'message'=>'Student ID missing']);
    exit;
}

$student_id = $input['student_id'];
$currentMonth = date('m');
$currentYear = date('Y');
$deadline = date('Y-m-25');

// Check if current month payment exists
$check_sql = "SELECT * FROM student_payments_new 
              WHERE student_id='$student_id' 
              AND MONTH(payment_date)='$currentMonth' 
              AND YEAR(payment_date)='$currentYear'";
$check_result = $con->query($check_sql);

if($check_result && $check_result->num_rows > 0){
    echo json_encode(['success'=>false, 'message'=>'Payment already exists for this month.']);
    exit;
}

// Insert notification
$message = "Payment for current month is pending. Deadline: $deadline";
$insert_sql = "INSERT INTO notifications (student_id, message, created_at) 
               VALUES ('$student_id', '$message', NOW())";

if($con->query($insert_sql)){
    echo json_encode(['success'=>true, 'message'=>'Notification sent successfully']);
}else{
    echo json_encode(['success'=>false, 'message'=>'Error: '.$con->error]);
}
?>
