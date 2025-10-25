// All Quizzes Page JavaScript Functions

let allQuizzesUpdateQuestionCount = 0;

// Override the openUpdateQuiz function for this page
function openUpdateQuiz(assessmentId) {
    // Find quiz details from DOM
    const quizCard = event.target.closest('.quiz-card');
    
    if (!quizCard) {
        alert('Error: Quiz card not found!');
        return;
    }
    
    const quizTitle = quizCard.querySelector('.quiz-title').innerText;
    const quizMarks = quizCard.querySelector('.quiz-marks').innerText;
    
    const quizNoMatch = quizTitle.match(/Quiz No: (\d+)/);
    const maxMarksMatch = quizMarks.match(/Max Marks: (\d+)/);

    const quizNo = quizNoMatch ? quizNoMatch[1] : '';
    const maxMarks = maxMarksMatch ? maxMarksMatch[1] : '';

    document.getElementById('update-assessment-id').value = assessmentId;
    document.getElementById('update-quiz-no').value = quizNo;
    document.getElementById('update-max-marks').value = maxMarks;

    // Clear existing questions container
    const questionsContainer = document.getElementById('update-questions-container');
    questionsContainer.innerHTML = '';
    allQuizzesUpdateQuestionCount = 0;

    // Populate existing questions
    const questionItems = quizCard.querySelectorAll('.questions-list ol li');
    questionItems.forEach((li, index) => {
        allQuizzesUpdateQuestionCount++;
        const fullText = li.textContent;
        // Parse text and marks from the structure: "Question text (Marks: X)"
        const text = fullText.split(' (Marks:')[0].trim();
        const marksMatch = fullText.match(/\(Marks:\s*(\d+)\)/);
        const marks = marksMatch ? marksMatch[1] : '';
        
        const div = document.createElement('div');
        div.classList.add('question');
        div.innerHTML = `
            <input type="text" name="questions[${allQuizzesUpdateQuestionCount}][text]" value="${text}" required>
            <input type="number" name="questions[${allQuizzesUpdateQuestionCount}][marks]" value="${marks}" min="1" required>
            <button type="button" class="btn-delete" onclick="removeUpdateQuestion(this)">Remove</button>
        `;
        questionsContainer.appendChild(div);
    });

    const modal = document.getElementById('update-quiz-modal');
    if (modal) {
        modal.style.display = 'block';
    } else {
        alert('Error: Modal element not found!');
    }
}

function closeUpdateModal() {
    document.getElementById('update-quiz-modal').style.display = 'none';
}

function addUpdateQuestion() {
    allQuizzesUpdateQuestionCount++;
    const container = document.getElementById('update-questions-container');
    const div = document.createElement('div');
    div.classList.add('question');
    div.innerHTML = `
        <input type="text" name="questions[${allQuizzesUpdateQuestionCount}][text]" placeholder="Question text" required>
        <input type="number" name="questions[${allQuizzesUpdateQuestionCount}][marks]" placeholder="Marks" min="1" required>
        <button type="button" class="btn-delete" onclick="removeUpdateQuestion(this)">Remove</button>
    `;
    container.appendChild(div);
}

function removeUpdateQuestion(btn){
    btn.parentElement.remove();
}

function confirmDelete(quizId) {
    if(confirm("Are you sure you want to delete this quiz?")) {
        window.location.href = "delete_quiz.php?id=" + quizId;
    }
}

// Close modal when clicking outside of it
window.onclick = function(event) {
    const modal = document.getElementById('update-quiz-modal');
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
