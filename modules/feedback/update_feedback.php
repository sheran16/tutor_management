<?php
require('../../config/db.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $feedback_id = isset($_POST['feedback_id']) ? (int)$_POST['feedback_id'] : 0;
    $action = $_POST['action'] ?? '';

    if ($action === 'reply') {
        $tutor_reply = trim($_POST['tutor_reply'] ?? '');
        
        // Simple validation
        if (empty($tutor_reply)) {
            echo "<script>alert('Reply cannot be empty!'); window.history.back();</script>";
            exit();
        }
        
        $sql = "UPDATE feedback SET tutor_reply = ?, status = 'resolved' WHERE feedback_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $tutor_reply, $feedback_id);
        $stmt->execute();
        $stmt->close();

    } elseif ($action === 'resolve') {
        $sql = "UPDATE feedback SET status = 'resolved' WHERE feedback_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $feedback_id);
        $stmt->execute();
        $stmt->close();

    } elseif ($action === 'delete') {
        $sql = "UPDATE feedback 
                SET tutor_deleted = 1, tutor_deleted_at = NOW()
                WHERE feedback_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $feedback_id);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: tutor_feedback.php");
    exit();
}