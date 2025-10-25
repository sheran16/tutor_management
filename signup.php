<html>
<head>
  <title>Student Sign Up</title>
  <link rel="stylesheet" href="assets/css/style1.css">
  <link rel="stylesheet" href="assets/css/signup.css">
  <style>
    body {
      background: url("tutorBackground.jpg") no-repeat center center fixed;
      background-size: cover;
      background-color: #f2f2f7; /* Default */
    }
</style>
</head>
<body>
  <div class="form-container">
    <form action="signup_submit.php" method="POST" class="signup-form" novalidate>
      <h2>Sign Up</h2>
       <label for="fullname">Full-name:</label>
      <input type="text" name="full_name" placeholder="Full Name" required>

      <label for="address">Address:</label>
      <input type="text" name="address" placeholder="Address" required>

      <label for="dob">DOB:</label>
      <input type="date" name="dob" id="dob" required>

      <label for="gradeID">Grade:</label>
      <select name="gradeID" id="gradeID" required>
      <option value="">-- Select Grade --</option>
      <option value="1">Grade 1</option>
      <option value="2">Grade 2</option>
      <option value="3">Grade 3</option>
      <option value="4">Grade 4</option>
      <option value="5">Grade 5</option>
      </select>
      
      <label for="contact">Contact:</label>
      <input type="text" name="contact" placeholder="Contact No" required>

      <label for="Username">User-name:</label>
      <input type="text" name="User_name" placeholder="User Name" required>

      <label for="password">Password:</label>
      <input type="password" name="password" id="password" placeholder="Create your password" required>

      <button type="submit">Submit</button>
    </form>
  </div>

 <?php include 'includes/footer.php'; ?>

<script src="assets/js/signup.js"></script>
</body>
</html>
