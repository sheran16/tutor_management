<?php
include("../../config/db.php");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Student Submissions - Tutor Dashboard</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/style1.css">
    <link rel="stylesheet" href="../../assets/css/submissions.css">
</head>
<body class="tutor-dashboard">
    <?php require("../../includes/headert.php"); ?>

    <div class="main-content">
        <div class="submissions-container">
            <a href="tutor_dashboard.php" class="back-button">← Back to Dashboard</a>
            
            <div class="submissions-header">
                <h1 class="page-title">Student Submissions</h1>
                <p>View and grade student quiz submissions</p>
            </div>

            <!-- Grade Dropdown -->
            <form method="GET" style="margin-bottom: 15px;">
            <label for="gradeFilter"><strong>Select Grade:</strong></label>
            <select name="grade" id="gradeFilter" onchange="this.form.submit()">
                <option value="0">All Grades</option>
                <option value="1" <?= (isset($_GET['grade']) && $_GET['grade'] == 1) ? 'selected' : '' ?>>Grade 1</option>
                <option value="2" <?= (isset($_GET['grade']) && $_GET['grade'] == 2) ? 'selected' : '' ?>>Grade 2</option>
                <option value="3" <?= (isset($_GET['grade']) && $_GET['grade'] == 3) ? 'selected' : '' ?>>Grade 3</option>
                <option value="4" <?= (isset($_GET['grade']) && $_GET['grade'] == 4) ? 'selected' : '' ?>>Grade 4</option>
                <option value="5" <?= (isset($_GET['grade']) && $_GET['grade'] == 5) ? 'selected' : '' ?>>Grade 5</option>
            </select>
            </form>

            <?php
            // Get all student submissions
            $filterGrade = isset($_GET['grade']) ? intval($_GET['grade']) : 0;

            $query = "SELECT s.submissionId, s.student_id, a.quizNo, a.gradeID, s.submitted_at
                    FROM assessment_submission s 
                    JOIN assessment a ON s.assessment_id = a.assessment_id";
            if ($filterGrade > 0) {
                $query .= " WHERE a.gradeID = $filterGrade";
            }
            $query .= " ORDER BY a.gradeID, s.submitted_at DESC";
            $subs = $conn->query($query);

            if($subs->num_rows > 0) {
                echo '<table class="submission-table">';
                echo '<thead>';
                echo '<tr>';
                echo '<th>Student ID</th>';
                echo '<th>Quiz No</th>';
                echo '<th>Grade</th>';
                echo '<th>Submitted At</th>';
                echo '<th>Action</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';

                while($sub = $subs->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$sub['student_id']}</td>";
                    echo "<td>Quiz {$sub['quizNo']}</td>";
                    echo "<td>Grade {$sub['gradeID']}</td>";
                    echo "<td>" . date('M j, Y g:i A', strtotime($sub['submitted_at'])) . "</td>";
                    echo "<td><button 
                            data-submissionid='{$sub['submissionId']}' 
                            data-studentid='{$sub['student_id']}' 
                            data-quizno='{$sub['quizNo']}' 
                            data-gradeid='{$sub['gradeID']}'
                            onclick=\"viewSubmission(this)\">View</button></td>";
                    echo "</tr>";
                }

                echo '</tbody>';
                echo '</table>';
            } else {
                echo '<div class="no-submissions">No student submissions found.</div>';
            }
            ?>

            <!-- Answer Sheet Modal -->
            <div id="answer-sheet" class="answer-sheet"></div>

            <!-- Hidden divs to store answers -->
            <?php
            $answersQuery = $conn->query("SELECT sa.submissionId, sa.id AS answer_id, q.text AS question_text, q.marks AS allocated_marks, sa.answer, sa.awardedMarks 
                                          FROM submission_answer sa
                                          JOIN question q ON sa.questionId = q.questionId");
            $answersBySubmission = [];
            while($a = $answersQuery->fetch_assoc()){
                $subId = $a['submissionId'];
                if(!isset($answersBySubmission[$subId])){
                    $answersBySubmission[$subId] = [];
                }
                $answersBySubmission[$subId][] = $a;
            }
            ?>

            <?php foreach($answersBySubmission as $subId => $answers): ?>
                <div id="submission-answers-<?= $subId ?>" style="display:none;">
                    <?php foreach($answers as $ans): ?>
                        <div class="answer-item">
                            <p><strong>Question:</strong> <?= htmlspecialchars($ans['question_text']) ?></p>
                            <p><strong>Marks:</strong> <?= $ans['allocated_marks'] ?></p>
                            <p><strong>Student Answer:</strong> <?= htmlspecialchars($ans['answer']) ?></p>
                            <label>Award Marks: 
                                <input type="number" class="question-marks" min="0" max="<?= $ans['allocated_marks'] ?>" 
                                       data-answerid="<?= $ans['answer_id'] ?>" value="<?= $ans['awardedMarks'] ?>">
                            </label>
                        </div>
                    <?php endforeach; ?>
                    <div class="total-marks-section">
                        <p><strong>Total Marks:</strong> <span class="total-marks">0</span></p>
                        <button class="btn-submit" onclick="submitMarks()">Submit Marks</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <?php require("../../includes/footer.php"); ?>
    <script src="../../assets/js/submissions.js"></script>
    <script src="../../assets/js/script.js"></script>
</body>
</html>
