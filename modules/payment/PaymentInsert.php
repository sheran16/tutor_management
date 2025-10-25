<?php require (dirname(__DIR__, 2) . '/config/db.php'); ?>  
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Payment Form</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/tutor_management/assets/css/payment.css">
     <style>
    body {
      background: url("/tutor_management/tutorBackground.jpg") no-repeat center center fixed;
      background-size: cover;
      background-color: #f2f2f7; /* default */
    }
  </style>
    
</head>
<body>
    <?php require("../../includes/headert.php"); ?>
    

    <div class="container">
        <div class="form-header">
            <h1><i class="fas fa-credit-card"></i> Student Payment Form</h1>
          
        </div>

        <div class="form-container">
            <form action="insert_payment.php" method="POST" class="payment-form">
                
                <!-- Student ID -->
                <div class="form-group">
                    <label for="student_id"><i class="fas fa-id-card"></i> Student ID:</label>
                    <input type="text" id="student_id" name="student_id" class="form-control" required>
                </div>

                <!-- Student Name -->
                <div class="form-group">
                    <label for="student_name"><i class="fas fa-user"></i> Student Name:</label>
                    <!-- Make the name editable so user can enter name if they forget the ID. It is not required because ID is the required value for submission;
                         however the name lookup will fill the ID before submit. -->
                    <input type="text" id="student_name" name="student_name" class="form-control">
                </div>

                <!-- Grade -->
                <div class="form-group">
                    <label for="grade"><i class="fas fa-graduation-cap"></i> Grade:</label>
                    <input type="text" id="grade" name="grade" class="form-control" required readonly>
                </div>

                <!-- Payment Month -->
                <div class="form-group">
                    <label for="payment_month"><i class="fas fa-calendar-alt"></i> Payment Month:</label>
                    <select id="payment_month" name="payment_month" class="form-control" required>
                        <option value="">-- Select Month --</option>
                        <option>January</option><option>February</option><option>March</option>
                        <option>April</option><option>May</option><option>June</option>
                        <option>July</option><option>August</option><option>September</option>
                        <option>October</option><option>November</option><option>December</option>
                    </select>
                </div>

                <!-- Payment Date -->
                <div class="form-group">
                    <label for="payment_date"><i class="fas fa-calendar-day"></i> Payment Date:</label>
                    <input type="date" id="payment_date" name="payment_date" class="form-control" required max="<?php echo date('Y-m-d'); ?>">
                </div>

                <!-- Fixed Amount -->
                <div class="form-group">
                    <label for="amount"><i class="fas fa-dollar-sign"></i> Fixed Amount:</label>
                    <input type="number" id="amount" name="amount" class="form-control" required readonly placeholder="0.00">
                </div>


                <!-- Paid Amount -->
                <div class="form-group">
                    <label for="paid_amount"><i class="fas fa-money-bill-wave"></i> Paid Amount:</label>
                    <input
                        type="number"
                        id="paid_amount"
                        name="paid_amount"
                        class="form-control"
                        required
                        placeholder="Enter Paid Amount"
                        min="0"
                        step="0.01"
                        oninput="updateRemaining()">
                </div>

                <!-- Amount to be Paid (remaining) -->
                <div class="form-group">
                    <label for="amount_to_pay"><i class="fas fa-balance-scale-right"></i> Amount to be Paid:</label>
                    <input type="number" id="amount_to_pay" name="amount_to_pay" class="form-control" readonly placeholder="0.00">
                </div>

                <script>
                    // Update remaining amount and validate paid amount
                    function updateRemaining() {
                        const amountField = document.getElementById('amount');
                        const paidField = document.getElementById('paid_amount');
                        const remainingField = document.getElementById('amount_to_pay');

                        const amount = parseFloat(amountField.value) || 0;
                        const paid = parseFloat(paidField.value) || 0;
                        const remaining = Math.max(0, amount - paid);

                        // Show two decimals
                        remainingField.value = remaining.toFixed(2);

                        // Validation: paid must not exceed amount
                        if (paidField.value && paid > amount) {
                            paidField.setCustomValidity('Paid amount cannot exceed total amount.');
                        } else {
                            paidField.setCustomValidity('');
                        }
                    }

                    // Initialize remaining value on load
                    document.addEventListener('DOMContentLoaded', function() {
                        updateRemaining();
                    });
                </script>

             

                <!-- Payment Status -->
                <div class="form-group">
                    <label for="payment_status"><i class="fas fa-info-circle"></i> Payment Status:</label>
                    <select id="payment_status" name="payment_status" class="form-control" required>
                        <option value="">-- Select Status --</option>
                        <option value="Paid">Paid</option>
                        <option value="Incomplete">Incomplete</option>
                    </select>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="insert-btn">
                    <i class="fas fa-plus-circle"></i> Add Payment
                </button>
            </form>
        </div>
    </div>

    <footer class="footer">
        <div class="footer-item">
            <img src="/tutor_management/images/phone.png" alt="Phone" class="footer-icon" />
            <span>+94 77 123 4567</span>
        </div>
        <div class="footer-item">
            <img src="/tutor_management/images/email.png" alt="Email" class="footer-icon" />
            <span>info@SmartKids.lk</span>
        </div>
        <div class="footer-item">
            <img src="/tutor_management/images/letter.png" alt="Letter" class="footer-icon" />
            <span>2025 Smart Kids</span>
        </div>
    </footer>

    <script>
        // Fetch student info and grade-based amount
        document.getElementById('student_id').addEventListener('blur', function() {
            const studentId = this.value.trim();
            if (!studentId) return;

            fetch('get_student.php?id=' + studentId)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('student_name').value = data.name;
                    document.getElementById('grade').value = data.grade;

                    // Get amount from 'amount' table
                    fetch('get_amount.php?grade=' + data.grade)
                    .then(res => res.json())
                    .then(amountData => {
                        document.getElementById('amount').value = amountData.amount || 0;
                    });
                } else {
                    document.getElementById('student_name').value = '';
                    document.getElementById('grade').value = '';
                    document.getElementById('amount').value = '';
                    alert(data.message);
                }
            })
            .catch(err => console.error(err));
        });

        // Allow lookup by student name when student_id is not provided.
        document.getElementById('student_name').addEventListener('blur', function() {
            const name = this.value.trim();
            const studentIdField = document.getElementById('student_id');
            // If ID already provided, prefer ID lookup (do nothing)
            if (!name || studentIdField.value.trim() !== '') return;

            fetch('get_student.php?name=' + encodeURIComponent(name))
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Fill the ID and other fields based on the found student
                    studentIdField.value = data.id || '';
                    document.getElementById('student_name').value = data.name || '';
                    document.getElementById('grade').value = data.grade || '';

                    // Get amount for the returned grade
                    if (data.grade) {
                        fetch('get_amount.php?grade=' + encodeURIComponent(data.grade))
                        .then(res => res.json())
                        .then(amountData => {
                            document.getElementById('amount').value = amountData.amount || 0;
                        });
                    }
                } else {
                    // Clear any fields set previously
                    // Do not clear the name the user typed, but reset others
                    document.getElementById('grade').value = '';
                    document.getElementById('amount').value = '';
                    // Optionally notify user
                    alert(data.message);
                }
            })
            .catch(err => console.error(err));
        });
    </script>
    <script src="/tutor_management/assets/js/scriptPayment.js"></script>
<script src="../../assets/js/script.js"></script>


</body>
</html>
