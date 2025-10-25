<?php
session_start();
require('../../config/db.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $feedback_type = trim($_POST['feedback_type'] ?? '');
    $feedback_text = trim($_POST['feedback_text'] ?? '');
    $student_id    = $_POST['student_id'] ?? '';
    $gradeID       = $_POST['gradeID'] ?? '';

    if ($feedback_type === '' || $feedback_text === '' || $student_id === '' || $gradeID === '') {
        header("Location: feedback.php?error=missing");
        exit();
    }

    $sql = "INSERT INTO feedback (feedback_type, feedback_text, student_id, gradeID)
            VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("SQL Error: " . $conn->error);
    }

    $stmt->bind_param("sssi", $feedback_type, $feedback_text, $student_id, $gradeID);

    if ($stmt->execute()) {
        //  success popup
        echo "<script>
                alert('Feedback submitted successfully!');
                window.location.href = '../../stunav.php';
              </script>";
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
