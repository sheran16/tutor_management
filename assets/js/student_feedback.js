document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('feedbackForm');
    const textarea = document.getElementById('feedback-text');
    const charCount = document.getElementById('charCount');
    
    textarea.addEventListener('input', function() { // Character counter
        charCount.textContent = this.value.length;
    });

    form.addEventListener('submit', function(e) {    // Form validation
        let isValid = true;
        let errorMessage = '';
        
        const feedbackTypes = document.querySelectorAll('input[name="feedback_type"]');// Check feedback type
        const isTypeSelected = Array.from(feedbackTypes).some(radio => radio.checked);
        
        if (!isTypeSelected) {
            errorMessage = 'Please select a feedback type.';
            isValid = false;
        }
        
        const feedbackText = textarea.value.trim(); // Check feedback text
        if (isValid && feedbackText.length === 0) {
            errorMessage = 'Please describe your feedback.';
            isValid = false;
        } else if (isValid && feedbackText.length < 10) {
            errorMessage = 'Feedback must be at least 10 characters long.';
            isValid = false;
        } else if (isValid && feedbackText.length > 500) {
            errorMessage = 'Feedback cannot exceed 500 characters.';
            isValid = false;
        }
        
        if (!isValid) {
            alert(errorMessage);
            e.preventDefault();
        }
    });
});