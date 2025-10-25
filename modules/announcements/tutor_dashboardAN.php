<?php


include '../../config/db.php'; 
include '../../includes/headert.php';

$message = ""; 
$messageType = ""; 

// Check for success messages 
if (isset($_GET['success']) && $_GET['success'] == '1') {
    $message = "Announcement posted successfully!";
    $messageType = "success";
}

if (isset($_GET['edit_success']) && $_GET['edit_success'] == '1') {
    $message = "Announcement updated successfully!";
    $messageType = "success";
} 


if (isset($_POST['postAnnouncement'])) {
    $announcementText = $_POST['announcementText'] ?? '';
    $targetGrade = $_POST['targetGrade'] ?? 'General';
    $tutorId = 1; 
    
    $description = mysqli_real_escape_string($conn, "[{$targetGrade}] " . trim($announcementText));
    
    $idQuery = "SELECT Announcement_id FROM announcement ORDER BY Announcement_id DESC LIMIT 1";
    $idResult = mysqli_query($conn, $idQuery);
    
    if ($idResult && mysqli_num_rows($idResult) > 0) {
        $lastId = mysqli_fetch_assoc($idResult)['Announcement_id'];
        
        $lastNumber = intval(substr($lastId, 3));
        $newNumber = $lastNumber + 1;
    } else {
        $newNumber = 1;
    }
    
    $announcementId = "ANN" . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    
    $query = "INSERT INTO announcement (Announcement_id, Description, DatePosted, tutor_id) 
              VALUES ('$announcementId', '$description', CURDATE(), '$tutorId')";
    $result = mysqli_query($conn, $query);
    
    if ($result) {
        header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
        exit();
    } else {
        $message = "Error posting announcement: " . mysqli_error($conn);
        $messageType = "error";
    }
}


if (isset($_POST['editAnnouncement'])) {
    $announcementId = $_POST['announcementId'] ?? '';
    $editedText = $_POST['editedText'] ?? '';
    
    $description = mysqli_real_escape_string($conn, trim($editedText));
    $announcementId = mysqli_real_escape_string($conn, $announcementId);
    
    $query = "UPDATE announcement 
              SET Description = '$description' 
              WHERE Announcement_id = '$announcementId'";
    $result = mysqli_query($conn, $query);
    
    if ($result) {
        header("Location: " . $_SERVER['PHP_SELF'] . "?edit_success=1");
        exit();
    } else {
        $message = "Error updating announcement: " . mysqli_error($conn);
        $messageType = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Announcements - Tutor Dashboard</title>
    <link rel="stylesheet" href="/tutor_management/assets/css/style1.css">
    <link rel="stylesheet" href="/tutor_management/assets/css/style_tutorAN.css">
    <style>
       
        #popupMessage {
            display: none;
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: bold;
            z-index: 1000;
        }
        #popupMessage.success { 
            background-color: #ffffff; 
            color: #000000;
            border: 2px solid #4e4e94;
        }
        #popupMessage.error { 
            background-color: #f44336; 
            color: #fff;
        }
    </style>
</head>
<body>

<div id="popupMessage" class="<?= $messageType ?>"><?= $message ?></div>

<main class="main-content">
    <h2>Announcements</h2>
    <p>Post and manage announcements for your students.</p>

    
    <div class="announcement-form-container">
        <h3>Post New Announcement</h3>
        <form method="POST" action="" id="postAnnouncementForm">
            <div class="form-group">
                <label for="targetGrade">Target Audience:</label>
                <select name="targetGrade" id="targetGrade" required>
                    <option value="General">General Announcement (All Grades)</option>
                    <option value="Grade 1">Grade 1</option>
                    <option value="Grade 2">Grade 2</option>
                    <option value="Grade 3">Grade 3</option>
                    <option value="Grade 4">Grade 4</option>
                    <option value="Grade 5">Grade 5</option>
                </select>
            </div>
            <div class="form-group">
                <textarea 
                    name="announcementText" 
                    id="announcementText" 
                    placeholder="Type your announcement here..."
                    rows="4"
                    required
                ></textarea>
            </div>
            <div class="form-actions">
                <button type="submit" name="postAnnouncement" class="btn-post">Post</button>
            </div>
        </form>
    </div>

   
    <div class="announcements-container">
        <h3>Posted Announcements</h3>
        
        <?php
        
        $query = "SELECT * FROM announcement ORDER BY DatePosted DESC";
        $result = mysqli_query($conn, $query);
        
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $announcementId = $row['Announcement_id'];
                $description = htmlspecialchars($row['Description']);
                $datePosted = date('F j, Y', strtotime($row['DatePosted']));
                
                // Extract target grade from description
                $targetGrade = "General";
                if (preg_match('/^\[([^\]]+)\]/', $description, $matches)) {
                    $targetGrade = $matches[1];
                }
                
                echo "<div class='announcement-item'>
                        <div class='announcement-content'>
                            <p class='announcement-text'>{$description}</p>
                            <div class='announcement-meta' style='display: flex; justify-content: space-between; align-items: center;'>
                                <span class='announcement-time'>Posted: {$datePosted}</span>
                            </div>
                        </div>
                        <div class='announcement-actions'>
                            <button class='btn-edit' onclick='editAnnouncement(\"{$announcementId}\")'>Edit</button>
                        </div>
                      </div>";
            }
        } else {
            echo "<div class='announcement-item'>
                    <div class='announcement-content'>
                        <p class='announcement-text'>No announcements posted yet. Create your first announcement above!</p>
                    </div>
                  </div>";
        }
        ?>
    </div>

   
    <div id="editModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Edit Announcement</h3>
                <span class="close" onclick="closeEditModal()">&times;</span>
            </div>
            <form method="POST" action="" id="editAnnouncementForm">
                <input type="hidden" name="announcementId" id="editAnnouncementId">
                <div class="form-group">
                    <textarea 
                        name="editedText" 
                        id="editedText" 
                        rows="4"
                        required
                    ></textarea>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn-cancel" onclick="closeEditModal()">Cancel</button>
                    <button type="submit" name="editAnnouncement" class="btn-save">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</main>

<?php include '../../includes/footer.php'; ?>

<script src="/tutor_management/assets/js/scriptAN.js"></script>
<script src="/tutor_management/assets/js/script.js"></script>

</body>
</html>
