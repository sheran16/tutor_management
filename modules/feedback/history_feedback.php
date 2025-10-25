<?php
session_start();
require('../../config/db.php');

// student login
if (!isset($_SESSION['student_id'])) {
    header("Location: ../../student_login.php");
    exit();
}

$student_id = (int)$_SESSION['student_id'];

// student's feedbacks
$sql = "SELECT f.feedback_id, f.feedback_type, f.feedback_text, 
               f.tutor_reply, f.status, f.created_at, g.grade_name
        FROM feedback f
        JOIN grade g ON f.gradeID = g.gradeID
        WHERE f.student_id = ?
        ORDER BY f.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<html>
<head>
  <title>My Feedback History</title>
  <link rel="stylesheet" href="../../assets/css/style1.css">
  <link rel="stylesheet" href="../../assets/css/history_feedback.css">
  <style>
    body {
      background: url("/tutor_management/student_Background.jpg") no-repeat center center fixed;
      background-size: cover;
      background-color: #f2f2f7;
    }
  </style>
</head>
<body>
<?php include '../../includes/header.php'; ?>

<main>
  <div class="feedback-list">
    <h2>My Feedback History</h2>

    <?php if ($result->num_rows > 0): ?>
      <?php while($row = $result->fetch_assoc()):
        $date   = date("Y-m-d", strtotime($row['created_at']));
        $grade  = $row['grade_name'];
        $type   = ucfirst($row['feedback_type']);
        $status = strtolower((string)$row['status']);
        $status_safe = preg_replace('/[^a-z0-9_-]/i', '-', $status);
        $status_text = ucfirst($status);
        $student_fb  = $row['feedback_text'];
        $tutor_rep   = $row['tutor_reply'] ? $row['tutor_reply'] : "No reply yet";
      ?>
        <article class="feedback-item"> <!-- container -->
          <div class="feedback-meta">
            <span><strong>Date:</strong> <?= $date ?></span> |
            <span><strong>Grade:</strong> <?= $grade ?></span> |
            <span><strong>Type:</strong> <?= $type ?></span> |
            <span class="status <?= $status_safe ?>"><?= $status_text ?></span>
          </div>

          <p><strong>Your Feedback:</strong> <?= $student_fb ?></p>
          <p><strong>Tutor Reply:</strong> <?= $tutor_rep ?></p>
        </article>
      <?php endwhile; ?>
    <?php else: ?>
      <article class="feedback-item">
        <p>No feedback found yet.</p>
      </article>
    <?php endif; ?>
  </div>
</main>

<?php include '../../includes/footer.php'; ?>
<script src="../../assets/js/student_feedback.js"></script>
<script src="../../assets/js/script.js"></script>
</body>
</html>
<?php
$stmt->close();
// $conn->close();  
?>
