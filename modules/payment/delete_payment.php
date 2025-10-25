<?php 
require (dirname(__DIR__, 2) . '/config/db.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Delete the payment
    $sql = "DELETE FROM student_payments_new WHERE id = $id";
    if ($conn->query($sql)) {
        header("Location: admin_dashboard.php?msg=deleted");
        exit;
    } else {
        echo "Error deleting payment: " . $conn->error;
    }
} else {
    header("Location: admin_dashboard.php");
    exit;
}
?>
