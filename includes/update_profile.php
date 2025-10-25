<?php
session_start();
require('../config/db.php');

if (!isset($_SESSION['student_id'])) {
  header('Location: /tutor_management/student_login.php');
  exit();
}
$student_id = $_SESSION['student_id']; 
// $msg = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'update') {
  $full_name = trim($_POST['full_name'] ?? '');
  $address   = trim($_POST['address'] ?? '');
  $dob       = $_POST['dob'] ?? '';
  $gradeID   = (int)($_POST['gradeID'] ?? 0);
  $contact   = trim($_POST['contact'] ?? '');
  $User_name = trim($_POST['User_name'] ?? '');
  $new_pass  = $_POST['password'] ?? ''; // if empty, don't change

  if ($new_pass === '') {
    $sql = "UPDATE students
            SET full_name=?, address=?, dob=?, gradeID=?, contact=?, User_name=?
            WHERE student_id=? AND deleted=0";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssisss", $full_name, $address, $dob, $gradeID, $contact, $User_name, $student_id);
  } else {
    $hashed_password = password_hash($new_pass, PASSWORD_DEFAULT);
    $sql = "UPDATE students
            SET full_name=?, address=?, dob=?, gradeID=?, contact=?, User_name=?, password=?
            WHERE student_id=? AND deleted=0";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssissss", $full_name, $address, $dob, $gradeID, $contact, $User_name, $hashed_password, $student_id);
  }

  $ok = $stmt->execute();
  $stmt->close();
  // $msg = $ok ? "Profile updated successfully." : "Update failed.";
  header('Location: view_profile.php?saved=1'); 
  exit();
}

/* students current details */
$sql = "SELECT student_id, full_name, address, dob, gradeID, contact, User_name, tutor_id
        FROM students WHERE student_id=? AND deleted=0";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $student_id);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$student) { die("Student not found or account deleted."); }

/* Grades */
$grades = $conn->query("SELECT gradeID, grade_name FROM grade ORDER BY gradeID");
?>
<html>
<head>
  <title>Update Profile</title>
  <link rel="stylesheet" href="/tutor_management/assets/css/style1.css">
  <link rel="stylesheet" href="/tutor_management/assets/css/profile.css">
  <style>
    body {
      background: url("/tutor_management/student_Background.jpg") no-repeat center center fixed;
      background-size: cover;
      background-color: #f2f2f7;
    }
  </style>
</head>
<body>
<?php include '../includes/header.php'; ?>

<main>
  <div class="wrap">
    <h2>Update Profile</h2>
   <!-- <?php if ($msg): ?><div class="flash"><?= $msg ?></div><?php endif; ?> -->

    <form action="update_profile.php" method="POST" id="updateForm" class="js-confirm" data-confirm="Do you want to update?">
      <input type="hidden" name="action" value="update">

      <div class="row">
        <label for="student_id">Student ID</label>
        <input type="text" id="student_id" value="<?= $student['student_id'] ?>" disabled>
      </div>

      <div class="row">
        <label for="full_name">Full Name</label>
        <input type="text" id="full_name" name="full_name" value="<?= $student['full_name'] ?>" required>
      </div>

      <div class="row">
        <label for="address">Address</label>
        <textarea id="address" name="address" rows="3"><?= $student['address'] ?></textarea>
      </div>

      <div class="row">
        <label for="dob">DOB</label>
        <input type="date" id="dob" name="dob" value="<?= $student['dob'] ?>" required>
      </div>

      <div class="row">
        <label for="gradeID">Grade</label>
        <select id="gradeID" name="gradeID" required>
          <?php while($g = $grades->fetch_assoc()): ?>
            <option value="<?= $g['gradeID'] ?>" <?= ($g['gradeID']==$student['gradeID']?'selected':'') ?>>
              <?= $g['grade_name'] ?>
            </option>
          <?php endwhile; ?>
        </select>
      </div>

      <div class="row">
        <label for="contact">Contact</label>
        <input type="text" id="contact" name="contact" value="<?= $student['contact'] ?>">
      </div>

      <div class="row">
        <label for="User_name">Username</label>
        <input type="text" id="User_name" name="User_name" value="<?= $student['User_name'] ?>">
      </div>

      <!-- Password change -->
      <div class="row">
        <label for="password">New Password</label>
        <input type="password" id="password" name="password" placeholder="If need to change password only type!">
      </div>
      
      <div class="actions">
        <button type="submit" class="btn success">Save</button>
        <a class="btn muted" href="/tutor_management/includes/view_profile.php">Back</a>
      </div>
    </form>
  </div>
</main>

<?php include '../includes/footer.php'; ?>
<script src="/tutor_management/assets/js/profile.js"></script>
<script src="/tutor_management/assets/js/script.js"></script>
</body>
</html>

