<?php
include("../../config/db.php");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Tutor Dashboard - Assessments</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/style1.css">
</head>
<body class="tutor-dashboard">
    <?php require("../../includes/headert.php"); ?>

    <div class="main-content">
        <div class="dashboard-container">

            <h1 class="page-title">Tutor Dashboard - Assessments</h1>

            <!-- Grade Dropdown -->
            <div class="grade-select">
                <label for="grade">Select Grade:</label>
                <select id="grade" onchange="updateQuizGrade();">
                    <option value="">-- Choose Grade --</option>
                    <option value="1">Grade 1</option>
                    <option value="2">Grade 2</option>
                    <option value="3">Grade 3</option>
                    <option value="4">Grade 4</option>
                    <option value="5">Grade 5</option>
                </select>
            </div>

            <!-- New Quiz Form -->
<div class="quiz-card">
    <h3>Create New Quiz</h3>
    <form id="quiz-form" method="POST" action="create_quiz.php">
        <div class="form-row-container">
            <div class="form-row">
                <label for="quiz-number"><strong>Quiz No:</strong></label>
                <input type="number" id="quiz-number" name="quizNo" placeholder="Quiz No" min="1" required>
            </div>

            <div class="form-row">
                <label for="quiz-max-marks"><strong>Total Marks:</strong></label>
                <input type="number" id="quiz-max-marks" name="maxMarks" placeholder="Total" min="1" max="100" required>
            </div>
        </div>

        <!-- Hidden input for selected grade -->
        <input type="hidden" id="quiz-grade" name="quiz_grade" value="">

        <!-- Questions container -->
        <div id="questions-container"></div>

        <!-- Buttons row inside form -->
        <div class="form-buttons">
            <button type="button" class="btn-create" onclick="addQuestion()">Add Question</button>
            <button type="submit" class="btn-publish">Publish Quiz</button>
        </div>
    </form>
</div>

        <!-- Navigation buttons-->
        <div class="dashboard-nav-buttons">
            <button class="btn-all" onclick="window.location.href='all_quizzes.php'">All Quizzes</button>
            <button class="btn-submissions" onclick="window.location.href='submissions.php'">Submissions</button>
        </div>
        </div>
    </div>

    <?php require("../../includes/footer.php"); ?>
    <script src="../../assets/js/script.js"></script>
</body>
</html>
