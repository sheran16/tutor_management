<?php
session_start();
require('../../config/db.php');

// student logged
if (!isset($_SESSION['student_id'])) {
    header("Location: ../../student_login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

$sql = "SELECT s.full_name, g.grade_name, s.student_id, g.gradeID
        FROM students s
        JOIN grade g ON s.gradeID = g.gradeID
        WHERE s.student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();
?>
<html>
<head>
  <title>Feedback Form</title>
  <link rel="stylesheet" href="../../assets/css/feedback.css">
  <link rel="stylesheet" href="../../assets/css/style1.css">
  <style>
    body {
      background: url("/tutor_management/student_Background.jpg") no-repeat center center fixed;
      background-size: cover;
      background-color: #f2f2f7;
    }
  </style>
</head>
<body>

<?php require '../../includes/header.php'; ?>
  <div class="container">
    <h2>Feedback Form</h2>
    <p>I would like to hear your thoughts, suggestions, concerns or problems.</p>

    <form action="feedback_submit.php" method="POST" id="feedbackForm" novalidate>
      <!-- Feedback type -->
      <div class="form-group">
        <label>Feedback Type <span class="required">*</span></label>
        <div class="radio-group">
          <label><input type="radio" name="feedback_type" value="comments" required> Comments</label>
          <label><input type="radio" name="feedback_type" value="suggestions" required> Suggestions</label>
          <label><input type="radio" name="feedback_type" value="questions" required> Questions</label>
        </div>
        <div class="error-message" id="typeError"></div>
      </div>

      <!-- Feedback text -->
      <div class="form-group">
        <label for="feedback-text">Describe Your Feedback: <span class="required">*</span></label>
        <textarea id="feedback-text" name="feedback_text" placeholder="Please provide detailed feedback..." required minlength="10" maxlength="500"></textarea>
        <div class="char-counter">
          <span id="charCount">0</span>/500 characters
        </div>
        <div class="error-message" id="textError"></div>
      </div>

      <!-- Student full name -->
      <div class="form-group">
        <label for="full_name">Full Name</label>
        <input type="text" id="full_name" value="<?= $student['full_name']; ?>" readonly>
      </div>

      <!-- Grade -->
      <div class="form-group">
        <label for="grade">Grade</label>
        <input type="text" id="grade" value="<?= $student['grade_name']; ?>" readonly>
      </div>

      <!-- Hidden fields to pass IDs -->
      <input type="hidden" name="student_id" value="<?= $student['student_id']; ?>">
      <input type="hidden" name="gradeID" value="<?= $student['gradeID']; ?>">

      <button type="submit" class="submit-btn">Submit</button>
    </form>
  </div>

  <a href="history_feedback.php" class="feedback-btn"> My Feedback History </a>

<?php include '../../includes/footer.php'; ?>
<script src="../../assets/js/script.js"></script>
<script src="../../assets/js/student_feedback.js"></script>
</body>
</html>
