<?php
session_start();

include '../../config/db.php'; 

$studentGradeID = $_SESSION['gradeID'] ?? null; 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Announcements - Student Dashboard</title>
    <link rel="stylesheet" href="../../assets/css/style1.css">
    <link rel="stylesheet" href="../../assets/css/style_studentAN.css">
</head>
<body>

<?php include '../../includes/header.php'; ?>
<main class="main-content">
    <h2>Announcements</h2>
    <p>Stay updated with the latest announcements!</p>


 
    <div class="all-announcements-container">
        <h3>All Announcements</h3>
        
        <div class="announcements-list">
            <?php
        
            $query = "SELECT * FROM announcement ORDER BY DatePosted DESC";
            $result = mysqli_query($conn, $query);
            $announcementsShown = 0;
            
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $description = htmlspecialchars($row['Description']);
                    $datePosted = date('F j, Y', strtotime($row['DatePosted']));
                    
                    // Check if announcement is for this student's grade or general
                    $showAnnouncement = false;
                    $displayText = $description;
                    
                    if (preg_match('/^\[([^\]]+)\]/', $description, $matches)) {
                        $targetGrade = $matches[1];
                        
                        // Show if it's general or matches student's grade
                        if ($targetGrade === 'General' || $targetGrade === "Grade {$studentGradeID}") {
                            $showAnnouncement = true;
                            // Remove the grade prefix when displaying to students
                            $displayText = preg_replace('/^\[[^\]]+\]\s*/', '', $description);
                        }
                    } else {
                        // If no grade prefix, show to everyone (backward compatibility)
                        $showAnnouncement = true;
                    }
                    
                    if ($showAnnouncement) {
                        $announcementsShown++;
                        echo "<div class='announcement-card'>
                                <div class='announcement-header'>
                                    <span class='announcement-date'>{$datePosted}</span>
                                </div>
                                <div class='announcement-body'>
                                    <p>{$displayText}</p>
                                </div>
                              </div>";
                    }
                }
            }
            
            // Show message if no announcements are visible for this student's grade
            if ($announcementsShown == 0) {
                echo "<div class='announcement-card'>
                        <div class='announcement-header'>
                            <span class='announcement-date'>" . date('F j, Y') . "</span>
                        </div>
                        <div class='announcement-body'>
                            <p>No announcements have been posted for your grade yet. Check back later for updates from your tutor!</p>
                        </div>
                      </div>";
            }
            ?>
        </div>
    </div>
</main>

<?php include '../../includes/footer.php'; ?>


<script src="/tutor_management/assets/js/scriptAN.js"></script>
<script src="/tutor_management/assets/js/script.js"></script>

</body>
</html>
