<?php
session_start();

include '../../config/db.php'; 
include '../../includes/header.php'; 

$selectedGrade = $_GET['grade'] ?? null;
$studentGradeID = $_SESSION['gradeID'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard - Class Slots</title>
    <link rel="stylesheet" href="../../assets/css/style1.css">
    <link rel="stylesheet" href="../../assets/css/style_student.css">
    <style>
        .grade-btn.disabled {
            opacity: 0.5;
            cursor: not-allowed;
            background-color:rgb(161, 116, 182);
            color: #191818ff;
        }
    </style>
</head>
<body>

<main class="main-content">

    <?php if (!$selectedGrade): ?>
       
        <div id="gradesView" class="grades-container">
            <?php for ($i = 1; $i <= 5; $i++): ?>
                <?php if ($i == $studentGradeID): ?>
                    <a class="grade-btn" href="?grade=<?= $i ?>">Grade <?= $i ?></a>
                <?php else: ?>
                    <span class="grade-btn disabled">Grade <?= $i ?></span>
                <?php endif; ?>
            <?php endfor; ?>
        </div>

    <?php else: ?>
       
        <div id="gradeDetailsView">
            <h2 class="grade-header">Grade <?= htmlspecialchars($selectedGrade) ?></h2>
            
            <div class="content-container">

            <?php
          
            $query = "SELECT DISTINCT time FROM ClassSlot WHERE gradeID = '$selectedGrade'";
            $resSchedule = mysqli_query($conn, $query);

            if ($resSchedule && mysqli_num_rows($resSchedule) > 0) {
                while ($row = mysqli_fetch_assoc($resSchedule)) {
                    echo "<div class='schedule-block'>";
                    echo "<p><strong>Class Time:</strong> " . htmlspecialchars($row['time']) . "</p>";

                   
                    $studentQuery = "SELECT full_name FROM ClassSlot 
                                     WHERE gradeID = '$selectedGrade' 
                                     AND time = '".mysqli_real_escape_string($conn, $row['time'])."'";
                    $studentRes = mysqli_query($conn, $studentQuery);

                    if ($studentRes && mysqli_num_rows($studentRes) > 0) {
                        echo "<p><strong>Students:</strong></p><ul>";
                        while ($s = mysqli_fetch_assoc($studentRes)) {
                            echo "<li>" . htmlspecialchars($s['full_name']) . "</li>";
                        }
                        echo "</ul>";
                    } else {
                        echo "<p>No students assigned yet.</p>";
                    }

                    echo "</div>";
                }
            } else {
                echo "<p>No schedule found for this grade yet.</p>";
            }
            ?>
            </div>
            
            <a class="back-btn" href="student_dashboardCS.php">← Back to Grades</a>
        </div>
    <?php endif; ?>

</main>
<script src="/tutor_management/assets/js/script.js"></script>
<?php include '../../includes/footer.php'; ?>

</body>
</html>
