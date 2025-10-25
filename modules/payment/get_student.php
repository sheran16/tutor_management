<?php
require (dirname(__DIR__, 2) . '/config/db.php');

header('Content-Type: application/json');

// Accept either id or name for lookup
$student_id = isset($_GET['id']) && $_GET['id'] !== '' ? $_GET['id'] : null;
$student_name = isset($_GET['name']) && $_GET['name'] !== '' ? $_GET['name'] : null;

if (!$student_id && !$student_name) {
    echo json_encode(['success' => false, 'message' => 'No student identifier provided']);
    exit;
}

if ($student_id) {
    // Lookup by student_id
    $sql = "SELECT student_id, full_name, gradeID FROM students WHERE student_id = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $student_id);
} else {
    // Lookup by name (partial match). Return the first match.
    $sql = "SELECT student_id, full_name, gradeID FROM students WHERE full_name LIKE ? LIMIT 1";
    $like = "%" . $student_name . "%";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $like);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode([
        'success' => true,
        'id' => $row['student_id'],
        'name' => $row['full_name'],
        'grade' => $row['gradeID']
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Student not found']);
}

$stmt->close();
$conn->close();
?>
