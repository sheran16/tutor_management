<html>
<head>
  <title>Login | Private Tutor Management System</title>
  <link rel="stylesheet" href="assets/css/style1.css">
  <style>
    body {
      background: url("tutorBackground.jpg") no-repeat center center fixed;
      background-size: cover;
      background-color: #f2f2f7; /* default */
    }
</style>
</head>

<body>

  <div class="login-container">
    <div class="login-box student">
      <h2> Student Login</h2>
      <p>Access your learning dashboard and course materials</p>
      
      <form Action = "login_process.php" method="POST">

        <input type = "hidden" name="login_type" value = "student">

        <label for="User_ID">User ID</label>
        <input type="text" name ="user_name" id="user_name" placeholder="Enter your User_name" required>
        
        <label for="password">Password</label>
        <input type="password" name="password" id="password" placeholder="Enter your password" required>
        
        <button type="submit">LOG IN</button>
      </form>
    </div>
  </div>
 <?php include 'includes/footer.php'; ?>
</body>
</html>
