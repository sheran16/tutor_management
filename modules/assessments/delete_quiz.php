<?php
include("../../config/db.php");

if(isset($_GET['id'])){
    $quizId = intval($_GET['id']);
    $conn->query("UPDATE assessment SET active = 0 WHERE assessment_id = $quizId");
}

header("Location: tutor_dashboard.php");
exit();

