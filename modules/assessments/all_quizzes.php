<?php
include("../../config/db.php");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>All Quizzes - Tutor Dashboard</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/style1.css">
    <link rel="stylesheet" href="../../assets/css/all_quizzes.css">
</head>
<body class="tutor-dashboard">
    <?php require("../../includes/headert.php"); ?>

    <div class="main-content">
        <div class="all-quizzes-container">
            <a href="tutor_dashboard.php" class="back-button">← Back to Dashboard</a>
            
            <h1 class="page-title">All Published Quizzes</h1>

            <?php
            // active quizzes grouped by grade
            $quizzes = $conn->query("SELECT * FROM assessment WHERE active = 1 ORDER BY gradeID, quizNo");
            
            if($quizzes->num_rows > 0) {
                $quizzesByGrade = [];
                
                // Group quizzes by grade
                while($quiz = $quizzes->fetch_assoc()) {
                    $gradeId = $quiz['gradeID'];
                    if(!isset($quizzesByGrade[$gradeId])) {
                        $quizzesByGrade[$gradeId] = [];
                    }
                    $quizzesByGrade[$gradeId][] = $quiz;
                }
                
                // Display quizzes
                for($grade = 1; $grade <= 5; $grade++) {
                    echo '<div class="grade-section">';
                    echo '<h2 class="grade-heading">Grade ' . $grade . '</h2>';
                    
                    if(isset($quizzesByGrade[$grade]) && count($quizzesByGrade[$grade]) > 0) {
                        echo '<div class="quiz-grid">';
                        
                        foreach($quizzesByGrade[$grade] as $quiz) {
                            echo '<div class="quiz-card">';
                            echo '<div class="quiz-header">';
                            echo '<div class="quiz-title">Quiz No: ' . $quiz['quizNo'] . '</div>';
                            echo '<div class="quiz-marks">Max Marks: ' . $quiz['maxMarks'] . '</div>';
                            echo '</div>';
                            
                            // questions for quiz
                            $questions = $conn->query("SELECT * FROM question WHERE assessment_id = " . $quiz['assessment_id']);
                            
                            if($questions->num_rows > 0) {
                                echo '<div class="questions-list">';
                                echo '<strong>Questions:</strong>';
                                echo '<ol>';
                                while($q = $questions->fetch_assoc()) {
                                    echo '<li>' . htmlspecialchars($q['text']) . ' <strong>(Marks: ' . $q['marks'] . ')</strong></li>';
                                }
                                echo '</ol>';
                                echo '</div>';
                            }
                            
                            echo '<div class="quiz-actions">';
                            echo '<button class="btn-update" onclick="openUpdateQuiz(' . $quiz['assessment_id'] . ')">Update</button>';
                            echo '<button class="btn-delete" onclick="confirmDelete(' . $quiz['assessment_id'] . ')">Delete</button>';
                            echo '</div>';
                            
                            echo '</div>'; // quiz-card
                        }
                        
                        echo '</div>'; // quiz-grid
                    } else {
                        echo '<div class="empty-grade">No quizzes available for Grade ' . $grade . '</div>';
                    }
                    
                    echo '</div>'; // grade-section
                }
            } else {
                echo '<div class="no-quizzes">No quizzes have been published yet.</div>';
            }
            ?>
        </div>
    </div>

    <!--Update Quiz Modal-->
    <div id="update-quiz-modal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeUpdateModal()">&times;</span>
            <h3>Update Quiz</h3>
            <form id="update-quiz-form" method="POST" action="update_quiz.php">
                <input type="hidden" name="assessment_id" id="update-assessment-id">

                <div class="form-row">
                    <label>Quiz No:</label>
                    <input type="number" name="quizNo" id="update-quiz-no" min="1" required>
                </div>

                <div class="form-row">
                    <label>Total Marks:</label>
                    <input type="number" name="maxMarks" id="update-max-marks" min="1" max="100" required>
                </div>

                <div id="update-questions-container">
                    <!--Existing questions-->
                </div>

                <div class="form-buttons">
                    <button type="button" onclick="addUpdateQuestion()">Add Question</button>
                    <button type="submit">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <?php require("../../includes/footer.php"); ?>
    <script src="../../assets/js/all_quizzes.js"></script>
    <script src="../../assets/js/script.js"></script>
</body>
</html>
