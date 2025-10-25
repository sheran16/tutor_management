<?php 
require (dirname(__DIR__, 2) . '/config/db.php');

// For current month & year
$currentMonth = date('m');
$currentYear = date('Y');

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>All Students Payments</title>
<link rel="stylesheet" href="/tutor_managemnt/assets/css/payment.css">
<link rel="stylesheet" href="/tutor_managemnt/assets/css/all_students.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</head>
<body>
<div class="container">
    <h1><i class="fas fa-users"></i> All Students</h1>

    <table>
        <thead>
            <tr>
                <th>Student ID</th>
                <th>Student Name & Payments</th>
                <th>Grade</th>
                <th>Contact</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $students_sql = "SELECT * FROM students ORDER BY gradeID, student_id";
            $students_result = $con->query($students_sql);

            if ($students_result && $students_result->num_rows > 0) {
                while ($student = $students_result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$student['student_id']}</td>";
                    echo "<td>
                            {$student['full_name']}";

                    // Payments
                    $payments_sql = "SELECT * FROM student_payments_new 
                                     WHERE student_id='{$student['student_id']}' 
                                     ORDER BY payment_date DESC";
                    $payments_result = $con->query($payments_sql);

                    if ($payments_result && $payments_result->num_rows > 0) {
                        echo "<ul class='payment-list'>";
                        while ($payment = $payments_result->fetch_assoc()) {
                            $date = date('d.m.Y', strtotime($payment['payment_date']));
                            echo "<li>{$date} - LKR " . number_format($payment['amount'], 2) . " ({$payment['status']})</li>";
                        }
                        echo "</ul>";
                    } else {
                        echo "<div class='payment-list'><i>No payments yet</i></div>";
                    }

                    echo "</td>";
                    echo "<td>{$student['gradeID']}</td>";
                    echo "<td>{$student['contact']}</td>";

                    // Check if current month payment exists
                    $checkPayment = "SELECT * FROM student_payments_new 
                                     WHERE student_id='{$student['student_id']}' 
                                     AND MONTH(payment_date)='$currentMonth' 
                                     AND YEAR(payment_date)='$currentYear'";
                    $checkResult = $con->query($checkPayment);
                    $disabled = ($checkResult && $checkResult->num_rows > 0) ? "disabled" : "";

                    echo "<td>
                            <button class='notify-btn' data-id='{$student['student_id']}' $disabled>
                                <i class='fas fa-bell'></i> Notify
                            </button>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No students found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script>
// Notify button click
document.querySelectorAll('.notify-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const studentId = this.dataset.id;
        fetch('notify_user.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ student_id: studentId })
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message);
            if(data.success) this.disabled = true;
        })
        .catch(err => console.error(err));
    });
});
</script>
</body>
</html>
