<?php
include("../../config/db.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $submissionId = $_POST['submission_id'];
    $answersJson = $_POST['answers'];
    $answers = json_decode($answersJson, true); // array of ['answer_id' => ..., 'marks' => ...]

    if ($submissionId && $answers) {
        $sql = $conn->prepare("UPDATE submission_answer SET awardedMarks=? WHERE id=?");

        foreach ($answers as $a) {
            $marks = (int)$a['marks'];
            $answer_id = (int)$a['answer_id'];
            $sql->bind_param("ii", $marks, $answer_id);
            $sql->execute();
        }
        $sql->close();

        echo "Marks updated successfully!";
        // Redirect to TD
        header("Location: tutor_dashboard.php");
        exit;
    } else {
        echo "Submission or answers not found!";
    }
}
?>
