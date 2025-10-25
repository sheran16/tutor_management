<?php 
require ('../config/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $student_id   = $_POST["student_id"];
    $name         = $_POST["student_name"];
    $grade        = $_POST["grade"];
    $month        = $_POST["payment_month"];
    $date         = $_POST["payment_date"];
    $amount       = $_POST["amount"];
    $paid_amount  = $_POST["paid_amount"];
    $status       = $_POST["payment_status"];

    if(empty($student_id) || empty($name) || empty($grade) || empty($month) || empty($date) || empty($amount) || empty($paid_amount) || empty($status)) {
        echo json_encode(["success" => false, "message" => "All fields are required!"]);
        exit;
    }

    // Check if payment for same student and month already exists
    $check_sql = "SELECT * FROM student_payments_new WHERE student_id='$student_id' AND month='$month'";
    $check_result = $conn->query($check_sql);

    if($check_result && $check_result->num_rows > 0) {
        echo json_encode(["success" => false, "message" => "Payment for Student ID $student_id for $month already exists!"]);
        exit;
    } 

    // Insert payment
    $sql = "INSERT INTO student_payments_new 
            (student_id, student_name, payment_date, amount, paid_amount, grade, month, status) 
            VALUES 
            ('$student_id', '$name', '$date', '$amount', '$paid_amount', '$grade', '$month', '$status')";

    if ($conn->query($sql)) {
        echo json_encode(["success" => true, "message" => "Payment added successfully for Student ID: $student_id"]);
    } else {
        echo json_encode(["success" => false, "message" => "Database error: ".$conn->error]);
    }
}
?>
