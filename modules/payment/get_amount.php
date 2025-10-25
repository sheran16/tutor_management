<?php
require (dirname(__DIR__, 2) . '/config/db.php');

if (isset($_GET['grade'])) {
    $grade = intval($_GET['grade']);
    $sql = "SELECT fixed_amount FROM amount WHERE gradeID = $grade";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode(['amount' => $row['fixed_amount']]);
    } else {
        echo json_encode(['amount' => 0]);
    }
}
?>
