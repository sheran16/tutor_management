<?php
// (Optional) Start session if needed
// session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Private Tutor Management System</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&display=swap" rel="stylesheet">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">

  <!-- External CSS -->
  <link rel="stylesheet" href="assets/css/home.css">
  <link rel="stylesheet" href="assets/css/style1.css">
</head>
<body style="background: url('homeBackground.jpg') no-repeat center center fixed; background-size: cover;">
  <!-- Main Heading -->
<h1 class="main-heading">Smart-Kids</h1>

  <!-- Hero Section -->
  <section class="hero">
    <div class="container">
      <div class="row justify-content-center mb-4 mt-5"><!-- Added margin-top -->
        <div class="col-md-5 mb-3">
          <div class="card role-card student p-5 text-center" onclick="window.location.href='student_login.php?role=student'">
            <i class="bi bi-mortarboard-fill display-3 text-white"></i>
            <h3 class="mt-3 text-white">Are you a Student?</h3>
          </div>
        </div>
        <div class="col-md-5 mb-3">
          <div class="card role-card tutor p-5 text-center" onclick="window.location.href='tutor_login.php?role=tutor'">
            <i class="bi bi-person-workspace display-3 text-white"></i>
            <h3 class="mt-3 text-white">Are you a Tutor?</h3>
          </div>
        </div>
      </div>
      <button class="btn btn-signup mt-4 px-4 py-2" onclick="window.location.href='signup.php'">
        <i class="bi bi-person-plus-fill"></i> New here? Sign Up
      </button>
    </div>
  </section>

  <!-- About Section -->
  <section class="about">
    <div class="container">
      <div class="row align-items-center mb-5">
        <div class="col-md-6">
          <h2>About Us</h2>
          <p>
            The Private Tutor Management System is designed to simplify the process of connecting 
            students and tutors. It provides a platform for class scheduling, progress tracking, 
            and secure payment handling — ensuring effective learning experiences for students.
          </p>
        </div>
        <div class="col-md-6 text-center">
          <img src="images/About Us.jpg" alt="About Us">
        </div>
      </div>

      <div class="row align-items-center mb-5">
        <div class="col-md-6 order-md-2">
          <h2>Who She Is</h2>
          <p>
            Meet <b>Ms. Zuhriya Zahir</b>, a dedicated private tutor with years of teaching experience. 
            Passionate about guiding students to success, she specializes in personalized learning 
            strategies that fit every student’s unique needs.
          </p>
        </div>
        <div class="col-md-6 order-md-1 text-center">
          <img src="images/Tutor Image.jpg" alt="Tutor Image">
        </div>
      </div>

      <div class="row align-items-center mb-5">
        <div class="col-md-6">
          <h2>Your Future</h2>
          <p>
            This system is not just about tutoring — it’s about shaping futures. With 
            structured learning paths, progress reports, and personalized support, students 
            gain the confidence and knowledge needed to excel academically and beyond.
          </p>
        </div>
        <div class="col-md-6 text-center">
          <img src="images/Students Future.jpg" alt="Students Future">
        </div>
      </div>
    </div>
  </section>

  <?php include("includes/footer.php"); ?>


  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
