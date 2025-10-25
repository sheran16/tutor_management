<?php
session_start(); // start the session
include("../../config/db.php");
include("fetch_quizzes.php"); // all quizzes grouped by grade

//logged-in student from session
$studentId = $_SESSION['student_id']; 
$studentGrade = $_SESSION['gradeID']; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="../../assets/css/student_dashboard.css">
    <link rel="stylesheet" href="../../assets/css/style1.css">
  <style>
    body {
      background: url("/tutor_management/student_Background.jpg") no-repeat center center fixed;
      background-size: cover;
      background-color: #f2f2f7;
      min-height: 100vh !important;
      display: flex !important;
      flex-direction: column !important;
      margin: 0 !important;
      padding: 0 !important;
    }
    .main-content {
      flex: 1 !important;
      min-height: 0 !important;
    }
    .footer {
      position: fixed !important;
      bottom: 0 !important;
      left: 0 !important;
      right: 0 !important;
      width: 100% !important;
      z-index: 1000 !important;
    }
    .main-content {
      padding-bottom: 80px !important;
    }
  </style>
</head>
<body>
    <?php require("../../includes/header.php"); ?>

    <div class="main-content">
        <div class="container">

            <!-- Left side: grades + quizzes -->
            <div class="left-side">
                <div class="grade-buttons">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                <button class="grade-btn" data-grade="<?= $i ?>" 
                <?= $i != $studentGrade ? 'disabled style="opacity:0.5;cursor:not-allowed;"' : '' ?>>
                Grade <?= $i ?>
                </button>
                <?php endfor; ?>
                </div>
                <div class="quiz-container">
                    <?php foreach($quizzes_by_grade as $grade => $quizzes): ?>
                        <div id="grade-<?= $grade ?>" class="grade-quizzes" style="display:none;">
                            <?php foreach($quizzes as $quizId => $quiz): ?>
                                <?php
                            // check if student already submitted quiz
                            $check = $conn->query("
                                SELECT * FROM assessment_submission 
                                WHERE student_id = '$studentId' AND assessment_id = '$quizId'
                            ");
                            $alreadySubmitted = $check->num_rows > 0;
                            ?>

                            <form class="quiz-form" action="submit_quiz.php" method="POST">
                                <input type="hidden" name="assessment_id" value="<?=$quizId ?>">
                                <h3>Grade <?= $grade ?> - Quiz <?= $quiz['quizNo'] ?></h3>
                                <input type="hidden" name="student_id" value="<?= $studentId ?>">

                                <?php $qnum = 1; foreach($quiz['questions'] as $q): ?>
                                    <div class="question">
                                        <label><?= $qnum ?>. <?= htmlspecialchars($q['text']) ?></label>
                                        <input type="text" name="answers[<?= $q['questionId'] ?>]"
                                            placeholder="Answer" <?= $alreadySubmitted ? 'disabled' : '' ?>>
                                        <span class="marks">Marks: <?= $q['marks'] ?></span>
                                    </div>
                                <?php $qnum++; endforeach; ?>

                            <div class="submit-wrapper">
                                <?php if($alreadySubmitted): ?>
                                    <span class="already-attempted-msg">Already attempted</span>
                                    <button type="button" class="submit-btn" disabled>Submit</button>
                                <?php else: ?>
                                    <button type="submit" class="submit-btn">Submit</button>
                                <?php endif; ?>
                            </div>
                            </form>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Right side: Progress Panel -->
            <div class="progress-panel">
                <h3>Progress</h3>
                <div id="progress-content">
                    <div class="progress-panel">
    
    <div id="progress-content">
        <?php
        $studentId = $_SESSION['student_id']; // from login session
        $subs = $conn->query("
            SELECT a.quizNo, SUM(sa.awardedMarks) AS totalMarks, SUM(q.marks) AS maxMarks
            FROM assessment_submission s
            JOIN submission_answer sa ON s.submissionId = sa.submissionId
            JOIN assessment a ON s.assessment_id = a.assessment_id
            JOIN question q ON sa.questionId = q.questionId
            WHERE s.student_id='$studentId'
            GROUP BY a.quizNo
            ORDER BY a.quizNo
        ");

        $totalOverall = 0;
        $count = 0;

        if ($subs->num_rows > 0) {
            while ($row = $subs->fetch_assoc()) {
                $quizNo = $row['quizNo'];
                $marks = $row['totalMarks'];
                $maxMarks = $row['maxMarks'];
                $percentage = $maxMarks > 0 ? round(($marks / $maxMarks) * 100) : 0;
                
                // Color coding for percentage
                $scoreClass = '';
                if ($percentage >= 80) $scoreClass = 'score-excellent';
                elseif ($percentage >= 60) $scoreClass = 'score-good';
                elseif ($percentage >= 40) $scoreClass = 'score-average';
                else $scoreClass = 'score-low';
                
                echo "<div class='quiz-result-card $scoreClass'>";
                echo "<div class='quiz-header'>";
                echo "<span class='quiz-title'> Quiz $quizNo</span>";
                echo "<span class='quiz-score'>$marks / $maxMarks</span>";
                echo "</div>";
                echo "<div class='progress-bar'>";
                echo "<div class='progress-fill' style='width: {$percentage}%'></div>";
                echo "</div>";
                echo "<div class='percentage'>$percentage%</div>";
                echo "</div>";
                
                $totalOverall += $marks;
                $count++;
            }
            $overallAverage = $count ? round($totalOverall / $count, 2) : 0;
            
            echo "<div class='summary-section'>";
            echo "<div class='summary-item'>";
            echo "<span class='summary-label'>Total Score</span>";
            echo "<span class='summary-value'>$totalOverall</span>";
            echo "</div>";
            echo "<div class='summary-item'>";
            echo "<span class='summary-label'>Average</span>";
            echo "<span class='summary-value'>$overallAverage</span>";
            echo "</div>";
            echo "</div>";
        } else {
            echo "<div class='no-results'>";
            echo "<div class='no-results-icon'</div>";
            echo "<p>No submissions yet.</p>";
            echo "<p class='no-results-sub'>Complete quizzes to see your progress!</p>";
            echo "</div>";
        }
        ?>
    </div>
</div>
                </div>
            </div>

        </div>
    </div>

    <?php require("../../includes/footer.php"); ?>
    <script src="../../assets/js/student_dashboard.js"></script>
    <script src="../../assets/js/script.js"></script>
</body>
</html>
