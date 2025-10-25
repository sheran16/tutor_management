<?php 
require (dirname(__DIR__, 2) . '/config/db.php');

// Fetch all payments
$sql = "SELECT * FROM student_payments_new";
$result = $conn->query($sql);

// Collect unique grades
$grades = [];
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        if (!in_array($row['grade'], $grades)) {
            $grades[] = $row['grade'];
        }
    }
    $result->data_seek(0);
}
sort($grades);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard - Payment Management</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="/tutor_management/assets/css/payment.css">
<link rel="stylesheet" href="css/admin.css">
<style>
.grade-buttons {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  margin: 15px 0;
}
.grade-btn {
  padding: 8px 15px;
  border: none;
  background-color: #007bff;
  color: white;
  border-radius: 6px;
  cursor: pointer;
  transition: 0.3s;
}
.grade-btn:hover {
  background-color: #0056b3;
}
.grade-btn.active {
  background-color: #28a745;
}
</style>
<style>
    body {
      background: url("/tutor_management/tutorBackground.jpg") no-repeat center center fixed;
      background-size: cover;
      background-color: #f2f2f7; /* Default */
    }
  </style>
</head>
<body>
  <?php require("../../includes/headert.php"); ?>


<div class="admin-container">
    <div class="admin-header">
        <h1><i class="fas fa-money-check"></i> Payment Management</h1>
        <div class="admin-actions">
            <a href="PaymentInsert.php" class="insert-btn">
                <i class="fas fa-plus-circle"></i> Create Payment
            </a>
        </div>
    </div>

    
        
        

    <div class="grade-filter">
        <h3>Filter by Grade:</h3>
        <div class="grade-buttons">
            <button class="grade-btn active" data-grade="all">All Grades</button>
            <?php foreach($grades as $grade): ?>
            <button class="grade-btn" data-grade="<?php echo htmlspecialchars($grade); ?>">Grade <?php echo htmlspecialchars($grade); ?></button>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="table-container">
        <div class="table-header"><h2>Student Payment Records</h2></div>
        
        <?php if ($result && $result->num_rows > 0): ?>
        <table id="payments-table">
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Payment Date</th>
                    <th>Amount (LKR)</th>
                    <th>Paid Amount (LKR)</th>
                    <th>Amount to be Paid (LKR)</th>
                    <th>Grade</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result->data_seek(0);
                while($row = $result->fetch_assoc()):
                    $status = $row['status'] ?? 'Pending';
                ?>
                <tr data-grade="<?php echo htmlspecialchars($row['grade']); ?>">
                    <td><?php echo htmlspecialchars($row['student_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['student_name']); ?></td>
                    <td><?php echo date('d.m.Y', strtotime($row['payment_date'])); ?></td>
                    <td><?php echo number_format($row['amount'], 2); ?></td>
                    <td><?php echo isset($row['paid_amount']) ? number_format($row['paid_amount'], 2) : '0.00'; ?></td>
                    <td><?php
                        $paid = isset($row['paid_amount']) ? (float)$row['paid_amount'] : 0.0;
                        $amount = isset($row['amount']) ? (float)$row['amount'] : 0.0;
                        $remaining = max(0.0, $amount - $paid);
                        echo number_format($remaining, 2);
                    ?></td>
                    <td><?php echo htmlspecialchars($row['grade']); ?></td>
                    <td>
                        <div class="status-toggle">
                            <span class="status-text" data-status="<?php echo htmlspecialchars($status); ?>"><?php echo htmlspecialchars($status); ?></span>
                        </div>
                    </td>
                    <td>
                        <a href="PaymentEdit.php?id=<?php echo $row['id']; ?>" class="edit-btn">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="no-records">
            <i class="fas fa-file-invoice-dollar"></i>
            <h3>No payment records found</h3>
            <p>There are no payment records in the system yet.</p>
        </div>
        <?php endif; ?>
    </div>
    </div>

    <div style="text-align: center; margin: 20px 0;">
        <a href="paidDetails.php" class="insert-btn">
            <i class="fas fa-list-check"></i> Track Paid Students List
        </a>
    </div>

<footer class="footer">
  <div class="footer-item"><img src="/tutor_management/images/phone.png" alt="Phone" class="footer-icon" /><span>+94 77 123 4567</span></div>
  <div class="footer-item"><img src="/tutor_management/images/email.png" alt="Email" class="footer-icon" /><span>info@SmartKids.lk</span></div>
  <div class="footer-item"><img src="/tutor_management/images/letter.png" alt="Letter" class="footer-icon" /><span>2025 Smart Kids</span></div>
</footer>

<script>
// 🔹 Filter records by grade (without reloading)
document.addEventListener("DOMContentLoaded", function() {
  const gradeButtons = document.querySelectorAll(".grade-btn");
  const rows = document.querySelectorAll("#payments-table tbody tr");

  gradeButtons.forEach(btn => {
    btn.addEventListener("click", () => {
      gradeButtons.forEach(b => b.classList.remove("active"));
      btn.classList.add("active");

      const selectedGrade = btn.getAttribute("data-grade");

      rows.forEach(row => {
        if (selectedGrade === "all" || row.dataset.grade === selectedGrade) {
          row.style.display = "";
        } else {
          row.style.display = "none";
        }
      });
    });
  });
});
</script>

<script src="/tutor_management/assets/js/scriptPayment.js"></script>
<script src="../../assets/js/script.js"></script>

</body>
</html>
