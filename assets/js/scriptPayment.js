document.addEventListener("DOMContentLoaded", () => {
    const deleteButtons = document.querySelectorAll(".delete-btn");

    deleteButtons.forEach(btn => {
        btn.addEventListener("click", () => {
            if(!confirm("Are you sure you want to delete this record?")) {
                return false;
            }
            // TODO: add AJAX delete call here if needed
        });
    });
});


document.addEventListener('DOMContentLoaded', function() {
    // Initialize payment counts
    updatePaymentCounts();
    
    // Grade filter functionality
    const gradeButtons = document.querySelectorAll('.grade-btn');
    const paymentRows = document.querySelectorAll('#payments-table tbody tr');
    
    gradeButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            gradeButtons.forEach(btn => btn.classList.remove('active'));
            // Add active class to clicked button
            this.classList.add('active');
            
            const grade = this.getAttribute('data-grade');
            
            // Show/hide rows based on grade filter
            paymentRows.forEach(row => {
                if (grade === 'all' || row.getAttribute('data-grade') === grade) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
    
    // Status toggle functionality
    const statusToggles = document.querySelectorAll('.status-toggle input');
    const refreshBtn = document.getElementById('refresh-btn');
    let pendingUpdates = [];
    
    statusToggles.forEach(toggle => {
        // Set initial status color
        const statusText = toggle.parentElement.previousElementSibling;
        const currentStatus = statusText.getAttribute('data-status');
        updateStatusColor(statusText, currentStatus);
        
        toggle.addEventListener('change', function() {
            const statusText = this.parentElement.previousElementSibling;
            const row = this.closest('tr');
            const paymentId = row.getAttribute('data-id');
            const newStatus = this.checked ? 'Paid' : 'Pending';
            
            // Update UI immediately
            statusText.textContent = newStatus;
            statusText.setAttribute('data-status', newStatus);
            updateStatusColor(statusText, newStatus);
            
            // Store the update for later
            const updateIndex = pendingUpdates.findIndex(update => update.id === paymentId);
            if (updateIndex !== -1) {
                pendingUpdates[updateIndex].status = newStatus;
            } else {
                pendingUpdates.push({ id: paymentId, status: newStatus });
            }
            
            // Show refresh button
            refreshBtn.style.display = 'flex';
            
            // Update payment counts
            updatePaymentCounts();
        });
    });
    
    // Refresh button functionality
    refreshBtn.addEventListener('click', function() {
        if (pendingUpdates.length > 0) {
            // Send all updates to the server
            updatePaymentStatuses(pendingUpdates);
        }
    });
    
    // View button functionality
    const viewButtons = document.querySelectorAll('.view-btn');
    
    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            const row = this.closest('tr');
            const studentId = row.querySelector('td:nth-child(1)').textContent;
            const studentName = row.querySelector('td:nth-child(2)').textContent;
            
            alert(`View details for: ${studentName} (ID: ${studentId})`);
        });
    });
    
    // Print button functionality
    const printButtons = document.querySelectorAll('.print-btn');
    
    printButtons.forEach(button => {
        button.addEventListener('click', function() {
            const row = this.closest('tr');
            const studentId = row.querySelector('td:nth-child(1)').textContent;
            const studentName = row.querySelector('td:nth-child(2)').textContent;
            
            alert(`Print receipt for: ${studentName} (ID: ${studentId})`);
        });
    });
    
    // Function to update payment counts
    function updatePaymentCounts() {
        const paidCount = document.querySelectorAll('.status-text[data-status="Paid"]').length;
        const pendingCount = document.querySelectorAll('.status-text[data-status="Pending"]').length;
        
        document.getElementById('paid-count').textContent = paidCount;
        document.getElementById('pending-count').textContent = pendingCount;
    }
    
    // Function to update status color
    function updateStatusColor(element, status) {
        if (status === 'Paid') {
            element.style.color = '#43B412';
            element.style.fontWeight = 'bold';
        } else {
            element.style.color = '#D6730F';
            element.style.fontWeight = 'bold';
        }
    }
    
    // Function to update payment statuses in database
function updatePaymentStatuses(updates) {
    console.log('Sending updates:', updates);
    
    // Create AJAX request to update database
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'update_payment_status.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            console.log('Response received:', xhr.status, xhr.responseText);
            if (xhr.status === 200) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        console.log('Statuses updated successfully:', response.message);
                        // Clear pending updates
                        pendingUpdates = [];
                        // Hide refresh button
                        refreshBtn.style.display = 'none';
                        // Reload the page to reflect changes
                        location.reload();
                    } else {
                        console.error('Server error:', response.message);
                        alert('Error: ' + response.message);
                    }
                } catch (e) {
                    console.error('JSON parse error:', e, xhr.responseText);
                    alert('Error parsing server response');
                }
            } else {
                console.error('HTTP error:', xhr.status, xhr.statusText);
                alert('Error updating payment statuses. Please try again. (HTTP ' + xhr.status + ')');
            }
        }
    };
    
    xhr.onerror = function() {
        console.error('Request failed');
        alert('Network error. Please check your connection and try again.');
    };
    
    xhr.send(JSON.stringify({ updates: updates }));
}
    
});