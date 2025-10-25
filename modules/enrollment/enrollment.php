 <!--<?php require("../../includes/auth_tutor.php"); ?> -->
<html>
<head>
  <title>Student Enrollment</title>
  <link rel="stylesheet" href="/tutor_management/assets/css/enrollment.css">
  <link rel="stylesheet" href="/tutor_management/assets/css/style1.css">
   <link rel="stylesheet" href="/tutor_management/assets/css/view.css">
  <link rel="stylesheet" href="/tutor_management/assets/css/update.css">
  <style>
    body {
      background: url("/tutor_management/tutorBackground.jpg") no-repeat center center fixed;
      background-size: cover;
      background-color: #f2f2f7; /* Default */
    }
  </style>
</head>
<body>
 <?php require("../../includes/headert.php"); ?>
<main>
  <!-- Enrolled Students Table -->
  <div class="table-container">
    <h2>Enrolled Students</h2>
    <table>
      <thead>
        <tr>
          <th>Student_ID</th>
          <th>Name</th>
          <th>Address</th>
          <th>DOB</th>
          <th>Grade</th>
          <th>Phone</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
           require(__DIR__ . '/../../config/db.php');
           $result = mysqli_query($conn, "SELECT * FROM students");
           while($row = mysqli_fetch_assoc($result)) {
             $status = ($row['deleted'] == 0) ? "Active" : "Inactive";
             echo "<tr>
                     <td>".$row['student_id']."</td>
                     <td>".$row['full_name']."</td>
                      <td>".$row['address']."</td>
                      <td>".$row['dob']."</td>
                      <td>".$row['gradeID']."</td>
                      <td>".$row['contact']."</td>
                      <td>".$status."</td>
                      <td> 
                  <button 
                   type='button' 
                   class='view-btn'
                   data-id='".$row['student_id']."'
                   data-name='".$row['full_name']."'
                   data-address='".$row['address']."'
                   data-dob='".$row['dob']."'
                   data-grade='".$row['gradeID']."'
                   data-contact='".$row['contact']."'
                  >View</button>

                  <button 
                   type='button' 
                   class='update-btn'
                   data-id='".$row['student_id']."'
                   data-name='".$row['full_name']."'
                   data-address='".$row['address']."'
                   data-dob='".$row['dob']."'
                   data-grade='".$row['gradeID']."'
                   data-contact='".$row['contact']."'
                 >Update</button>
               </td>
             </tr>";
           }
        ?>
      </tbody>
    </table>
  </div>
    <div id="viewModal" class="modal" aria-hidden="true">
     <div class="modal-backdrop"></div>
      <div class="modal-dialog">
        <div class="modal-header">
        <h3>Student Details</h3>
        <button type="button" class="modal-close">&times;</button>
        </div>
        <div class="modal-body">
        <p><b>ID:</b> <span id="m_id"></span></p>
        <p><b>Name:</b> <span id="m_name"></span></p>
        <p><b>Address:</b> <span id="m_address"></span></p>
        <p><b>DOB:</b> <span id="m_dob"></span></p>
        <p><b>Grade:</b> <span id="m_grade"></span></p>
        <p><b>Contact:</b> <span id="m_contact"></span></p>
        </div>
    </div>
  </div>
</main>
<!-- UPDATE MODAL -->
<div id="updateModal" class="umodal" aria-hidden="true">
  <div class="umodal-backdrop" data-close-update></div>
   <div class="umodal-dialog" role="dialog" aria-modal="true" aria-labelledby="uTitle">
      <div class="umodal-header">
        <h3 id="uTitle">Update Student</h3>
        <button type="button" class="umodal-close" title="Close" data-close-update>&times;</button>
      </div>
        <div class="umodal-body">
          <form method="POST" action="/tutor_management/modules/enrollment/update.php" id="updateForm" class="uform">
            <input type="hidden" name="student_id" id="u_id">

            <label for="u_name">Name</label>
            <input type="text" name="full_name" id="u_name" required>

            <label for="u_address">Address</label>
            <input type="text" name="address" id="u_address" required>

            <label for="u_dob">DOB</label>
            <input type="date" name="dob" id="u_dob" required>

            <label for="u_grade">Grade</label>
            <select name="gradeID" id="u_grade" required>
              <option value="">-- Select Grade --</option>
              <option value="1">Grade 1</option>
              <option value="2">Grade 2</option>
              <option value="3">Grade 3</option>
              <option value="4">Grade 4</option>
              <option value="5">Grade 5</option>
            </select>

            <label for="u_contact">Phone</label>
            <input type="text" name="contact" id="u_contact" required>

            <div class="umodal-actions">
              <button type="button" class="ubtn flat" data-close-update>Cancel</button>
              <button type="submit" class="ubtn save" onclick="return confirm('Are you sure you want to update this student?');">Save Changes</button>
            </div>
        </form>
      </div>
  </div>
</div>

<?php require("../../includes/footer.php"); ?>
<script src="../../assets/js/script.js"></script>
<script src="../../assets/js/view.js"></script>
<script src="../../assets/js/update.js"></script>
</body>
</html>
