<?php
session_start();
require('../../config/db.php');

// show in url
$selected_grade = isset($_GET['grade']) ? (int)$_GET['grade'] : '';

// feedback with student + grade details
$sql = "SELECT f.feedback_id, f.feedback_type, f.feedback_text, 
               f.tutor_reply, f.status, s.full_name, g.grade_name, g.gradeID, f.created_at
        FROM feedback f
        JOIN students s ON f.student_id = s.student_id
        JOIN grade g ON f.gradeID = g.gradeID
        WHERE f.tutor_deleted = 0";
        
//grade filter
if (!empty($selected_grade)) {
    $sql .= " AND g.gradeID = " . (int)$selected_grade;
}
$sql .= " ORDER BY f.created_at DESC";
$result = $conn->query($sql);
// get all grades
$grades_sql = "SELECT gradeID, grade_name FROM grade ORDER BY grade_name";
$grades_result = $conn->query($grades_sql);
?>
<html>
<head>
  <title>Tutor Feedback Management</title>
  <link rel="stylesheet" href="../../assets/css/style1.css">
  <link rel="stylesheet" href="../../assets/css/tutor_feedback.css">
  <style>
    body {
      background: url("/tutor_management/tutorBackground.jpg") no-repeat center center fixed;
      background-size: cover;
      background-attachment: fixed;
      min-height: 100vh;
      background-color: #f2f2f7; /* Default */
    }
  </style>
</head>
<body class="page">
  <?php include '../../includes/headert.php'; ?>

  <main class="content">
    <div class="table-wrap">
      <h2 class="page-heading">Student Feedbacks</h2>
      <div class="dropdown-container">
        <form method="GET" class="grade-filter-form">
          <select name="grade" id="gradeFilter" class="grade-filter-select" onchange="this.form.submit()">
            <option value="">All Grades</option>
            <?php while($grade_row = $grades_result->fetch_assoc()): ?>
              <option value="<?= $grade_row['gradeID'] ?>" <?= $selected_grade == $grade_row['gradeID'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($grade_row['grade_name']) ?>
              </option>
            <?php endwhile; ?>
          </select>
        </form>
      </div>

      <table class="feedback-table">
        <thead>
          <tr>
            <th>Student</th>
            <th>Grade</th>
            <th>Type</th>
            <th>Feedback</th>
            <th>Reply</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $row['full_name'] ?></td>
            <td><?= $row['grade_name'] ?></td>
            <td><?= ucfirst($row['feedback_type']) ?></td>
            <td><?= $row['feedback_text'] ?></td>
            <td><?= $row['tutor_reply'] ? htmlspecialchars($row['tutor_reply']) : "No reply yet"; ?></td>
            <td><?= ucfirst($row['status']) ?></td>
            <td class="actions-cell">
              <!-- Reply button opens modal -->
              <button 
                type="button" 
                class="btn reply-btn" 
                data-feedback-id="<?= (int)$row['feedback_id'] ?>"
                data-current-reply="<?= htmlspecialchars($row['tutor_reply'] ?? '', ENT_QUOTES) ?>"
              >
                Reply
              </button>
              <!-- resolve / delete forms  -->
              <form action="update_feedback.php" method="POST" style="display:inline;">
                <input type="hidden" name="feedback_id" value="<?= (int)$row['feedback_id'] ?>">
                <button class="btn resolve" type="submit" name="action" value="resolve">Mark Read</button>
              </form>

              <form action="update_feedback.php" method="POST" style="display:inline;">
                <input type="hidden" name="feedback_id" value="<?= (int)$row['feedback_id'] ?>">
                <button class="btn delete" type="submit" name="action" value="delete" onclick="return confirm('Delete this feedback?');">Delete</button>
              </form>
            </td>
          </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </main>

  <?php include '../../includes/footer.php'; ?>

  <!-- Reply Modal -->
  <div id="replyModal" class="modal" aria-hidden="true">
    <div class="modal-backdrop" data-close-modal></div>
    <div class="modal-dialog" role="dialog" aria-modal="true" aria-labelledby="replyModalTitle">
      <div class="modal-header">
        <h3 id="replyModalTitle">Reply to Feedback</h3>
        <button type="button" class="modal-close" title="Close" data-close-modal>&times;</button>
      </div>
      <div class="modal-body">
        <form id="replyForm" action="update_feedback.php" method="POST">
          <input type="hidden" name="feedback_id" id="feedback_id">
          <input type="hidden" name="action" value="reply">
          <label for="tutor_reply" class="sr-only">Reply</label>
          <textarea name="tutor_reply" id="tutor_reply" placeholder="Type your reply..." required></textarea>
          <div class="modal-actions">
            <button type="button" class="btn flat" data-close-modal>Cancel</button>
            <button type="submit" class="btn resolve">Send Reply</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="../../assets/js/tutor_feedback.js"></script>
  <script src="../../assets/js/script.js"></script>
</body>
</html>