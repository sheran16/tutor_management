document.addEventListener('DOMContentLoaded', () => {
    const gradeButtons = document.querySelectorAll('.grade-btn');
    const gradeQuizzes = document.querySelectorAll('.grade-quizzes');

    // Initially hide all quizzes
    gradeQuizzes.forEach(gq => gq.style.display = 'none');

    // Grade button click
    gradeButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const grade = btn.dataset.grade;
            gradeQuizzes.forEach(gq => gq.style.display = 'none');
            const activeGrade = document.getElementById('grade-' + grade);
            if(activeGrade) activeGrade.style.display = 'block';
        });
    });

    // Handle quiz submission
    document.querySelectorAll('.quiz-form').forEach(form => {
    form.addEventListener('submit', e => {
        const inputs = form.querySelectorAll('.question input[type="text"]');
        let allFilled = true;

        inputs.forEach(input => {
            if(input.value.trim() === '') allFilled = false;
        });

        if(!allFilled) {
            e.preventDefault(); // stop submission
            alert('Please answer all questions before submitting!');
            return;
        }

            // If validation passes, show success message
            alert(`${form.querySelector('h3').innerText} submitted successfully!`);

        });
    });
});
