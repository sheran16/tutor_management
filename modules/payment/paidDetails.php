<?php require (dirname(__DIR__, 2) . '/config/db.php'); ?>  
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Status by Grade</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/tutor_management/assets/css/payment.css">
    
    <style>
        body {
            background: url('bg.jpeg') no-repeat center center fixed;
            background-size: cover;
            color: #333;
            line-height: 1.6;
        }
        .container {
            margin-top: 60px;
        }
        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        .btn-custom {
            background-color: #4e73df;
            color: white;
            border-radius: 12px;
            padding: 10px 20px;
            font-weight: 500;
        }
        .btn-custom:hover {
            background-color: #3756c0;
        }
        table {
            border-radius: 10px;
            overflow: hidden;
        }
        th {
            background-color: #4e73df;
            color: white;
        }
        h2 {
            color: #333;
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

<!-- header -->
<?php require("../../includes/headert.php"); ?>

<div class="container" style="min-height: 65vh;">
    <div class="card p-4">
        <h2 class="text-center mb-4"><i class="fas fa-money-check-alt me-2"></i>Grade-wise Payment Status</h2>

        <!-- Dropdown Form -->
        <form method="POST" class="d-flex justify-content-center align-items-center gap-3 flex-wrap">
            <!-- Grade Dropdown -->
            <select name="grade_id" class="form-select w-auto" required>
                <option value="">Select Grade</option>
                <?php
                $grades = $conn->query("SELECT * FROM grade");
                while ($g = $grades->fetch_assoc()) {
                    $selected = (isset($_POST['grade_id']) && $_POST['grade_id'] == $g['gradeID']) ? 'selected' : '';
                    echo "<option value='{$g['gradeID']}' $selected>{$g['grade_name']}</option>";
                }
                ?>
            </select>

            <!-- Month Dropdown -->
            <select name="month" class="form-select w-auto">
                <option value="">Select Month</option>
                <?php
                $months = [
                    'January', 'February', 'March', 'April', 'May', 'June',
                    'July', 'August', 'September', 'October', 'November', 'December'
                ];
                foreach ($months as $m) {
                    $selected = (isset($_POST['month']) && $_POST['month'] == $m) ? 'selected' : '';
                    echo "<option value='$m' $selected>$m</option>";
                }
                ?>
            </select>

            <button type="submit" name="search" class="btn btn-custom"><i class="fas fa-search"></i> Search</button>
        </form>
    </div>

    <?php
    if (isset($_POST['search'])) {
        $grade_id = $_POST['grade_id'];
        $month = $_POST['month'];

        // --- Paid Students (status = 'Paid') ---
        $paid_where = [];
        $paid_where[] = "p.status = 'Paid'";
        if (!empty($grade_id)) {
            $paid_where[] = "s.gradeID = '$grade_id'";
        }
        if (!empty($month)) {
            $paid_where[] = "p.month = '$month'";
        }
        $paid_where_sql = 'WHERE ' . implode(' AND ', $paid_where);

        $paid_sql = "
            SELECT DISTINCT s.student_id, s.full_name
            FROM students s
            JOIN student_payments_new p ON s.student_id = p.student_id
            $paid_where_sql
        ";
        $paid_result = $conn->query($paid_sql);

        // --- Incomplete / Pending Payments (status = 'Pending' only) ---
        $incomplete_where = [];
        $incomplete_where[] = "p.status = 'Pending'"; // only pending as requested
        if (!empty($grade_id)) {
            $incomplete_where[] = "s.gradeID = '$grade_id'";
        }
        if (!empty($month)) {
            $incomplete_where[] = "p.month = '$month'";
        }
        $incomplete_where_sql = 'WHERE ' . implode(' AND ', $incomplete_where);

        $incomplete_sql = "
            SELECT DISTINCT s.student_id, s.full_name
            FROM students s
            JOIN student_payments_new p ON s.student_id = p.student_id
            $incomplete_where_sql
        ";
        $incomplete_result = $conn->query($incomplete_sql);

        // --- Non-Paid Students ---
        // Students in the selected grade (if any) who DO NOT have a payment record for the selected month.
        $non_paid_where_parts = [];
        if (!empty($grade_id)) {
            $non_paid_where_parts[] = "s.gradeID = '$grade_id'";
        }

        // Build subquery to find students who HAVE payments for the selected month (or any payments if month not selected)
        $subquery = "SELECT student_id FROM student_payments_new";
        $subconds = [];
        if (!empty($month)) {
            $subconds[] = "month = '$month'";
        }
        if (!empty($subconds)) {
            $subquery .= ' WHERE ' . implode(' AND ', $subconds);
        }

        // Now assemble main query
        $main_where = '';
        if (!empty($non_paid_where_parts)) {
            $main_where = 'WHERE ' . implode(' AND ', $non_paid_where_parts) . ' AND ';
        } else {
            $main_where = 'WHERE ';
        }

        $non_paid_sql = "
            SELECT DISTINCT s.student_id, s.full_name
            FROM students s
            $main_where s.student_id NOT IN ( $subquery )
        ";
        $non_paid_result = $conn->query($non_paid_sql);
    ?>
    <div class="row mt-5">
        <div class="col-md-4">
            <div class="card p-3">
                <h4 class="text-success"><i class="fas fa-check-circle me-2"></i>Paid Students</h4>
                <table class="table table-striped mt-3">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Student Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($paid_result && $paid_result->num_rows > 0) {
                            $i = 1;
                            while ($row = $paid_result->fetch_assoc()) {
                                echo "<tr><td>$i</td><td>{$row['full_name']}</td></tr>";
                                $i++;
                            }
                        } else {
                            echo "<tr><td colspan='2' class='text-center text-muted'>No Paid Students</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-3">
                <h4 class="text-warning"><i class="fas fa-exclamation-circle me-2"></i>Incomplete Payments</h4>
                <table class="table table-striped mt-3">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Student Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($incomplete_result && $incomplete_result->num_rows > 0) {
                            $i = 1;
                            while ($row = $incomplete_result->fetch_assoc()) {
                                echo "<tr><td>$i</td><td>{$row['full_name']}</td></tr>";
                                $i++;
                            }
                        } else {
                            echo "<tr><td colspan='2' class='text-center text-muted'>No Incomplete/Pending Payments</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-3">
                <h4 class="text-danger"><i class="fas fa-times-circle me-2"></i>Non-Paid Students</h4>
                <table class="table table-striped mt-3">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Student Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($non_paid_result && $non_paid_result->num_rows > 0) {
                            $i = 1;
                            while ($row = $non_paid_result->fetch_assoc()) {
                                echo "<tr><td>$i</td><td>{$row['full_name']}</td></tr>";
                                $i++;
                            }
                        } else {
                            echo "<tr><td colspan='2' class='text-center text-muted'>No Non-Paid Students</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php } ?>
</div>

<!-- footer -->
<footer class="footer">
  <div class="footer-item"><img src="/tutor_management/images/phone.png" alt="Phone" class="footer-icon" /><span>+94 77 123 4567</span></div>
  <div class="footer-item"><img src="/tutor_management/images/email.png" alt="Email" class="footer-icon" /><span>info@SmartKids.lk</span></div>
  <div class="footer-item"><img src="/tutor_management/images/letter.png" alt="Letter" class="footer-icon" /><span>2025 Smart Kids</span></div>
</footer>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>