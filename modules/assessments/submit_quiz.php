<?php
include("../../config/db.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'];
    $assessment_id = $_POST['assessment_id'];
    $answers = $_POST['answers'] ?? [];

    // Validate all answers are filled
    $allFilled = true;
    foreach ($answers as $ans) {
        if (trim($ans) === '') {
            $allFilled = false;
            break;
        }
    }

// Check all answers are filled
    foreach ($answers as $ans) {
        if (trim($ans) === '') {
            // Stop if any empty
            die("Please answer all questions before submitting!");
        }
    }

    $check = $conn->query("SELECT * FROM students WHERE student_id = '$student_id'");
    if($check->num_rows === 0){
    die("Error: student_id '$student_id' does not exist in students table!");
    }

    // Check if already submitted
    $check = $conn->query("SELECT * FROM assessment_submission 
        WHERE student_id='$student_id' AND assessment_id='$assessment_id'");
    if($check->num_rows > 0){
        die("You have already attempted this quiz!");
    }

    // Insert into assessment_submission
    $stmt = $conn->prepare("INSERT INTO assessment_submission (student_id, assessment_id) VALUES (?, ?)");
    $stmt->bind_param("si", $student_id, $assessment_id);
    $stmt->execute();
    $submissionId = $stmt->insert_id;
    $stmt->close();

    // Insert each answer
    $stmt = $conn->prepare("INSERT INTO submission_answer (submissionId, questionId, answer, awardedMarks) VALUES (?, ?, ?, 0)");
    foreach ($answers as $questionId => $answer) {
        $stmt->bind_param("iis", $submissionId, $questionId, $answer);
        $stmt->execute();
    }
    $stmt->close();

    // Redirect
    header("Location: student_dashboard.php?success=1");
    exit();
}

