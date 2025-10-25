<?php


include '../../config/db.php'; 
include '../../includes/headert.php'; 

$message = ""; 
$messageType = ""; 

if (isset($_GET['msg'])) {
    switch ($_GET['msg']) {
        case 'success':
            $message = "Student added to class slot successfully!";
            $messageType = "success";
            break;
        case 'deleted':
            $message = "Student removed from class slot successfully!";
            $messageType = "success";
            break;
        case 'error':
            $message = "Database Error: " . (isset($_GET['err']) ? $_GET['err'] : 'Unknown error');
            $messageType = "error";
            break;
        case 'notfound':
            $message = "Selected student not found.";
            $messageType = "error";
            break;
        case 'empty':
            $message = "Please fill in all fields.";
            $messageType = "error";
            break;
        case 'deleteerror':
            $message = "Delete Error: " . (isset($_GET['err']) ? $_GET['err'] : 'Unknown error');
            $messageType = "error";
            break;
        case 'deletempty':
            $message = "Missing information for deletion.";
            $messageType = "error";
            break;
    }
} 


$gradeMap = [
    1 => "Grade 1",
    2 => "Grade 2",
    3 => "Grade 3",
    4 => "Grade 4",
    5 => "Grade 5"
];


$gradeTimeMap = [
    1 => "Saturday 8-10 AM",
    2 => "Saturday 10-12 AM", 
    3 => "Saturday 12-2 PM",
    4 => "Thursday 3-5 PM",
    5 => "Friday 3-5 PM"
];


if (isset($_POST['addStudent'])) {
    $slotID = $_POST['slotID'] ?? '';
    $studentID = $_POST['studentID'] ?? '';
    $time = $_POST['time'] ?? '';

    if ($slotID && $studentID && $time) {
        
        // Check if student exists in the class slot already
        $checkQuery = "SELECT * FROM ClassSlot WHERE slotID = '$slotID' AND student_id = '$studentID'";
        $checkResult = mysqli_query($conn, $checkQuery);
        
        if ($checkResult && mysqli_num_rows($checkResult) > 0) {
            $message = "This student is already assigned to this class slot!";
            $messageType = "error";
            
            header("Location: " . $_SERVER['PHP_SELF'] . "?msg=duplicate");
            exit();
        } else {
            // Fetch student information from students table
            $res = mysqli_query($conn, "SELECT full_name, gradeID FROM students WHERE student_id = '$studentID'");
            if ($res && mysqli_num_rows($res) > 0) {
                $student = mysqli_fetch_assoc($res);
                $fullName = $student['full_name'];
                $gradeID = $student['gradeID'];
                $gradeName = $gradeMap[$gradeID];

                
                $query = "INSERT INTO ClassSlot (slotID, student_id, gradeID, grade_name, full_name, time, tutor_id)
                          VALUES ('$slotID', '$studentID', $gradeID, '$gradeName', '$fullName', '$time', 1)";
                $result = mysqli_query($conn, $query);

                if ($result) {
                    $message = "Student added to class slot successfully!";
                    $messageType = "success";
                    // Successful insertion: redirect with success message
                    header("Location: " . $_SERVER['PHP_SELF'] . "?msg=success");
                    exit();
                } else {
                    
                    header("Location: " . $_SERVER['PHP_SELF'] . "?msg=error&err=" . urlencode(mysqli_error($conn)));
                    exit();
                }
            } else {
                
                header("Location: " . $_SERVER['PHP_SELF'] . "?msg=notfound");
                exit();
            }
        }
    } else {
        
        header("Location: " . $_SERVER['PHP_SELF'] . "?msg=empty");
        exit();
    }
}




if (isset($_POST['deleteStudent'])) {
    $slotID = $_POST['slotID'] ?? '';
    $studentID = $_POST['studentID'] ?? '';

    if ($slotID && $studentID) {
        $query = "DELETE FROM ClassSlot WHERE slotID = '$slotID' AND student_id = '$studentID'";
        $result = mysqli_query($conn, $query);

        if ($result) {
            
            header("Location: " . $_SERVER['PHP_SELF'] . "?msg=deleted");
            exit();
        } else {
            
            header("Location: " . $_SERVER['PHP_SELF'] . "?msg=deleteerror&err=" . urlencode(mysqli_error($conn)));
            exit();
        }
    } else {
        
        header("Location: " . $_SERVER['PHP_SELF'] . "?msg=deletempty");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Class Slots</title>
    <link rel="stylesheet" href="/tutor_management/assets/css/style1.css">
    <link rel="stylesheet" href="/tutor_management/assets/css/style_tutor.css">
</head>
<body>

<div id="popupMessage" class="<?= $messageType ?>"><?= $message ?></div>

<main class="main-content">
    <h2>Class Slots</h2>
    <p>Manage your students' class slots here.</p>

    <button id="addRowBtn" class="btn-add">Add Student</button>

    
    <div id="addStudentForm" style="display:none;">
        <form method="POST" action="">
            <select name="studentID" id="addStudentSelect" required>
                <option value="">Select Student</option>
                <?php
                // Only show students who don't have a class slot assigned yet
                $resStudents = mysqli_query($conn, "SELECT s.student_id, s.full_name, s.gradeID 
                                                   FROM students s 
                                                   LEFT JOIN ClassSlot cs ON s.student_id = cs.student_id 
                                                   WHERE s.deleted = 0 AND cs.student_id IS NULL 
                                                   ORDER BY s.full_name");
                while ($row = mysqli_fetch_assoc($resStudents)) {
                    echo "<option value='{$row['student_id']}' data-grade='{$row['gradeID']}'>{$row['full_name']}</option>";
                }
                ?>
            </select>

            <input type="text" name="slotID" id="addSlotID" placeholder="Grade" readonly required>

            <input type="text" name="time" id="addTime" placeholder="Time Slot" readonly required>

            <button type="submit" name="addStudent" class="btn-add">Add Student</button>
        </form>
    </div>

    <!-- Delete confirmation modal -->
    <div id="deleteModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Confirm Delete</h3>
                <span class="close" onclick="closeDeleteModal()">&times;</span>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this student's class slot?</p>
                <p class="warning-text">This action cannot be undone.</p>
            </div>
            <form method="POST" action="" id="deleteConfirmForm">
                <input type="hidden" name="slotID" id="deleteSlotID">
                <input type="hidden" name="studentID" id="deleteStudentID">
                
                <div class="form-actions">
                    <button type="button" class="btn-cancel" onclick="closeDeleteModal()">Cancel</button>
                    <button type="submit" name="deleteStudent" class="btn-delete">Yes, Delete</button>
                </div>
            </form>
        </div>
    </div>

   
    <table border="1" id="studentsTable">
        <thead>
            <tr>
                <th>Student ID</th>
                <th>Slot ID</th>
                <th>Name</th>
                <th>Grade</th>
                <th>Time</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $query = "SELECT * FROM ClassSlot";
        $result = mysqli_query($conn, $query);

        if ($result === false) {
            echo "<tr><td colspan='6'>Query error: " . htmlspecialchars(mysqli_error($conn)) . "</td></tr>";
        } elseif (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                        <td>{$row['student_id']}</td>
                        <td>{$row['slotID']}</td>
                        <td>{$row['full_name']}</td>
                        <td>{$row['grade_name']}</td>
                        <td>{$row['time']}</td>
                        <td>
                            <button class='btn-delete' onclick='deleteSlot(\"{$row['slotID']}\", \"{$row['student_id']}\")'>Delete</button>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No records found</td></tr>";
        }
        ?>
        </tbody>
    </table>
</main>

<?php include '../../includes/footer.php'; ?>

<script src="/tutor_management/assets/js/script.js"></script>
<script src="/tutor_management/assets/js/myScript.js"></script>

</body>
</html>
