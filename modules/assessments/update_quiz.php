<?php
include("../../config/db.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $assessment_id = $_POST['assessment_id'];
    $quizNo = $_POST['quizNo'];
    $maxMarks = $_POST['maxMarks'];
    $questions = $_POST['questions'] ?? [];

    // 1️ Check if any submission exists for this quiz
    $checkSubs = $conn->query("SELECT COUNT(*) AS cnt FROM assessment_submission WHERE assessment_id = $assessment_id");
    $row = $checkSubs->fetch_assoc();

    if($row['cnt'] > 0){
        // 2️ If submitted, show alert and stop
        echo "<script>alert('Cannot update: Students have already submitted this quiz!'); window.history.back();</script>";
        exit();
    }

    // 3️ If no submissions, safe to update
    // Update assessment table
    $stmt = $conn->prepare("UPDATE assessment SET quizNo=?, maxMarks=? WHERE assessment_id=?");
    $stmt->bind_param("iii", $quizNo, $maxMarks, $assessment_id);
    $stmt->execute();
    $stmt->close();

    // Delete old questions
    $conn->query("DELETE FROM question WHERE assessment_id=$assessment_id");

    // Insert updated questions
    foreach($questions as $q){
        $text = trim($q['text']);
        $marks = intval($q['marks']);
        if($text && $marks > 0){
            $stmtQ = $conn->prepare("INSERT INTO question (text, marks, assessment_id) VALUES (?, ?, ?)");
            $stmtQ->bind_param("sii", $text, $marks, $assessment_id);
            $stmtQ->execute();
            $stmtQ->close();
        }
    }

    // Redirect dashboard
    header("Location: tutor_dashboard.php?success=1");
    exit();
}
?>
