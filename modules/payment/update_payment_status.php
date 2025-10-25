<?php
// update_payment_statuses.php
require ('../config/db.php');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

// Check database connection
if ($con->connect_error) {
    echo json_encode([
        'success' => false,
        'message' => 'Database connection failed: ' . $con->connect_error
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the raw POST data
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    if (isset($data['updates']) && is_array($data['updates'])) {
        $successCount = 0;
        $errorCount = 0;
        
        // Prepare statement
        $stmt = $con->prepare("UPDATE student_payments SET status = ? WHERE id = ?");
        
        if (!$stmt) {
            echo json_encode([
                'success' => false,
                'message' => 'Prepare failed: ' . $con->error
            ]);
            exit;
        }
        
        foreach ($data['updates'] as $update) {
            $id = $update['id'];
            $status = $update['status'];
            
            // Validate data
            if (!is_numeric($id) || !in_array($status, ['Paid', 'Pending'])) {
                $errorCount++;
                continue;
            }
            
            // Bind parameters and execute
            $stmt->bind_param("si", $status, $id);
            
            if ($stmt->execute()) {
                $successCount++;
            } else {
                $errorCount++;
                error_log("Error updating status for ID $id: " . $stmt->error);
            }
        }
        
        $stmt->close();
        
        echo json_encode([
            'success' => true,
            'message' => "Updated $successCount records successfully. $errorCount failed."
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid data format'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
}

$con->close();
?>