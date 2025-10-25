//============================================Login========================================

// Toggle dropdown on profile button click
document.querySelector(".dropdown button").addEventListener("click", () => {
  document.querySelector(".dropdown-menu").classList.toggle("show");
});

// Close dropdown if clicked outside
window.addEventListener("click", (e) => {
  if (!e.target.closest(".dropdown")) {
    document.querySelector(".dropdown-menu").classList.remove("show");
  }
});
/*=================================tutor_dashboard.php=====================================================*/
let questionCount = 0;
// ===================== Grade dropdown sync =====================
function updateQuizGrade() {
    const grade = document.getElementById('grade').value;
    document.getElementById('quiz-grade').value = grade;
}
// ===================== Quiz Form Submission =====================
document.getElementById('quiz-form').addEventListener('submit', function(e){
    const grade = document.getElementById('quiz-grade').value;
    if(!grade) {
        alert('Please select the relevant grade!');
        e.preventDefault(); // stop submission
    }
});
// ===================== Add / Remove Questions =====================
function addQuestion() {
    questionCount++;
    const container = document.getElementById('questions-container');

    const div = document.createElement('div');
    div.classList.add('question', 'form-row'); // keep form-row for flex

    div.innerHTML = `
        <label><strong>Q${questionCount}:</strong></label>
        <input type="text" class="question-text" name="questions[${questionCount}][text]" placeholder="Question text" required>
        <input type="number" class="question-marks" name="questions[${questionCount}][marks]" placeholder="Marks" min="1" max="100" required>
        <button type="button" class="btn-delete" onclick="removeQuestion(this)">Remove</button>
    `;

    container.appendChild(div);
}
function removeQuestion(btn){
    btn.parentElement.remove();
    renumberQuestions();
}
function renumberQuestions() {
    const questions = document.querySelectorAll('#questions-container .question');
    questionCount = 0;
    questions.forEach((q, index) => {
        questionCount++;
        q.querySelector('label').innerHTML = `<strong>Q${questionCount}:</strong>`;
    });
}
// Quiz deletion function - used by various pages
function confirmDelete(quizId) {
    if(confirm("Are you sure you want to delete this quiz?")) {
        window.location.href = "delete_quiz.php?id=" + quizId;
    }
}
