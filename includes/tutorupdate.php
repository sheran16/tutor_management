<?php
session_start();
require('../config/db.php'); 

if (!isset($_SESSION['tutor_id'])) {
  header('Location: tutor_login.php'); 
  exit();
}
$tutor_id = (int)$_SESSION['tutor_id'];

/* update */
if ($_SERVER['REQUEST_METHOD']==='POST' && ($_POST['action'] ?? '')==='update') {
  $full_name = trim($_POST['full_name'] ?? '');
  $user_name = trim($_POST['user_name'] ?? '');
  $contact   = trim($_POST['contact_no'] ?? '');
  $dob       = $_POST['dob'] ?? '';
  $address   = trim($_POST['address'] ?? '');
  $email     = trim($_POST['email'] ?? '');
  $new_pass  = $_POST['password'] ?? '';

  if ($new_pass === '') {
    $sql = "UPDATE tutor
            SET full_name=?, user_name=?, contact_no=?, dob=?, address=?, email=?
            WHERE tutor_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $full_name, $user_name, $contact, $dob, $address, $email, $tutor_id);
  } else {
    // Hash the new password
    $hashed_password = password_hash($new_pass, PASSWORD_DEFAULT);
    $sql = "UPDATE tutor
            SET full_name=?, user_name=?, contact_no=?, dob=?, address=?, email=?, password=?
            WHERE tutor_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssi", $full_name, $user_name, $contact, $dob, $address, $email, $hashed_password, $tutor_id);
  }
  if (!$stmt->execute()) {
    die("Update failed: " . $stmt->error);
  }
  $stmt->close();

  header('Location: tutorview.php?saved=1'); 
  exit();
}

/* Fetch tutor */
$sql = "SELECT tutor_id, full_name, user_name, contact_no, dob, address, email
        FROM tutor WHERE tutor_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $tutor_id);
$stmt->execute();
$tutor = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$tutor) { die("Tutor not found."); }
?>

<html>
<head>
  <title>Update Tutor Profile</title>
  <link rel="stylesheet" href="/tutor_management/assets/css/style1.css">
  <link rel="stylesheet" href="/tutor_management/assets/css/tutor_profile.css">
  <style>
    body {
      background: url("/tutor_management/tutorBackground.jpg") no-repeat center center fixed;
      background-size: cover;
      background-color: #f2f2f7; /* fallback */
    }
  </style>
</head>
<body>
<?php include '../includes/headert.php'; ?>

<main>
  <div class="wrap">
    <h2>Update Profile</h2>

    <form action="tutorupdate.php" method="POST" id="updateForm" class="js-confirm" data-confirm="Do you want to update?">
      <input type="hidden" name="action" value="update">

      <div class="row">
        <label for="tutor_id">Tutor ID</label>
        <input type="text" id="tutor_id" value="<?= $tutor['tutor_id'] ?>" disabled>
      </div>

      <div class="row">
        <label for="full_name">Full Name</label>
        <input type="text" name="full_name" id="full_name" value="<?= $tutor['full_name'] ?>" required>
      </div>

      <div class="row">
        <label for="user_name">Username</label>
        <input type="text" name="user_name" id="user_name" value="<?= $tutor['user_name'] ?>" required>
      </div>

      <div class="row">
        <label for="contact_no">Contact No</label>
        <input type="text" name="contact_no" id="contact_no" value="<?= $tutor['contact_no'] ?>" required>
      </div>

      <div class="row">
        <label for="dob">DOB</label>
        <input type="date" name="dob" id="dob" value="<?= $tutor['dob'] ?>" required>
      </div>

      <div class="row">
        <label for="address">Address</label>
        <textarea name="address" id="address" rows="3"><?= $tutor['address'] ?></textarea>
      </div>

      <div class="row">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" value="<?= $tutor['email'] ?>" required>
      </div>

      <div class="row">
        <label for="password">New Password</label>
        <input type="password" name="password" id="password" placeholder="If need to change password only type!">
      </div>

      <div class="actions">
        <button type="submit" class="btn success">Save</button>
        <a class="btn muted" href="/tutor_management/includes/tutorview.php">Back</a>
      </div>
    </form>
  </div>
</main>

<?php include '../includes/footer.php'; ?>
<script src="/tutor_management/assets/js/tutor_profile.js"></script>
<script src="/tutor_management/assets/js/script.js"></script>
</body>
</html>

