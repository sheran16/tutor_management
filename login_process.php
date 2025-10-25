<?php
session_start();
require('config/db.php');

$login_type = $_POST['login_type'] ?? '';
$username   = trim($_POST['user_name'] ?? '');   
$password   = $_POST['password'] ?? '';

// redirect page
$login_page = ($login_type === 'student') ? 'student_login.php' : 'tutor_login.php';

if ($login_type === 'student') {
    $sql = "SELECT student_id, user_name, password, gradeID, deleted 
            FROM students 
            WHERE user_name = ?";
} elseif ($login_type === 'tutor') {
    $sql = "SELECT tutor_id, user_name, password 
            FROM tutor 
            WHERE user_name = ?";
}

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    if ($login_type === 'student' && (int)$row['deleted'] === 1) {
        echo "<script>alert('Your account has been deleted. Please contact admin.'); window.location.href='$login_page';</script>";
        exit();
    }

    //password hashed and plain text 
    $password_valid = false;
    
    // First hashed verification
    if (password_verify($password, $row['password'])) {
        $password_valid = true;
    } 
    //  fail do plain text
    else if ($password === $row['password']) {
        $password_valid = true;
        
        // plain text password to hashed
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        if ($login_type === 'student') {
            $update_sql = "UPDATE students SET password = ? WHERE student_id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("si", $hashed_password, $row['student_id']);
        } else {
            $update_sql = "UPDATE tutor SET password = ? WHERE tutor_id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("si", $hashed_password, $row['tutor_id']);
        }
        $update_stmt->execute();
        $update_stmt->close();
    }
    
    if ($password_valid) {
        if ($login_type === 'student') {
            $_SESSION['student_id'] = $row['student_id'];
            $_SESSION['user_name']  = $row['user_name'];
            $_SESSION['gradeID']    = $row['gradeID'];
            echo "<script>
                alert('Login Successful! Welcome " . htmlspecialchars($row['user_name']) . "');
                window.location.href='stunav.php';
            </script>";
        } else {
            $_SESSION['tutor_id']   = $row['tutor_id'];
            $_SESSION['user_name']  = $row['user_name'];
            echo "<script>
                alert('Login Successful! Welcome " . htmlspecialchars($row['user_name']) . "');
                window.location.href='tutornav.php';
            </script>";
        }
        exit();
    } else {
        echo "<script>alert('Incorrect password'); window.location.href='$login_page';</script>";
    }

} else {
        echo "<script>alert('Username not found'); window.location.href='$login_page';</script>";
        exit();
    }
$stmt->close();
$conn->close();
?>
