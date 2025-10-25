<?php
include("../../config/db.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $quizNo   = $_POST['quizNo'] ?? null;
    $maxMarks = $_POST['maxMarks'] ?? null;
    $gradeID  = $_POST['quiz_grade'] ?? null;
    $questions = $_POST['questions'] ?? [];

    if (!$quizNo || !$maxMarks || !$gradeID) {
        die("Error: Please fill all required fields and select a grade.");
    }

    if (!isset($_SESSION['tutor_id'])) {
        die("Error: Tutor not logged in.");
    }
    $tutor_id = $_SESSION['tutor_id'];

    // Check if quizNo already exists for the same grade (even soft-deleted)
        $checkStmt = $conn->prepare("SELECT assessment_id FROM assessment WHERE quizNo = ? AND gradeID = ?");
        $checkStmt->bind_param("ii", $quizNo, $gradeID);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            echo "<script>
                alert('Quiz number already exists for this grade. Please use a different quiz number.');
                window.history.back();
            </script>";
            exit();
        }
        $checkStmt->close();

    //insert assessments
    $stmt = $conn->prepare("INSERT INTO assessment (quizNo, maxMarks, tutor_id, gradeID) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiii", $quizNo, $maxMarks, $tutor_id, $gradeID);

    if (!$stmt->execute()) {
        die("Error inserting quiz: " . $stmt->error);
    }

    $assessment_id = $stmt->insert_id;
    $stmt->close();

    //insert questions
    if (!empty($questions)) {
        foreach ($questions as $q) {
            $text  = trim($q['text']);
            $marks = intval($q['marks']);

            if ($text && $marks > 0) {
                $stmtQ = $conn->prepare("INSERT INTO question (text, marks, assessment_id) VALUES (?, ?, ?)");
                $stmtQ->bind_param("sii", $text, $marks, $assessment_id);
                $stmtQ->execute();
                $stmtQ->close();
            }
        }
    }

    echo "<script>
        alert ('Successfully Published!');
        window.location.href='tutor_dashboard.php';
        </script>";
    exit();
}
?>
