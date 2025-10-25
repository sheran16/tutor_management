// Submissions Page JavaScript Functions

let currentSubmissionId = '';
let currentStudentId = '';
let currentQuizNo = '';
let currentGrade = '';

function viewSubmission(button) {
    currentSubmissionId = button.dataset.submissionid;
    currentStudentId = button.dataset.studentid;
    currentQuizNo = button.dataset.quizno;
    currentGrade = button.dataset.gradeid;

    const answerSheet = document.getElementById("answer-sheet");
    answerSheet.style.display = "block";

    const answersDiv = document.getElementById("submission-answers-" + currentSubmissionId);
    if (!answersDiv) {
        answerSheet.innerHTML = `
            <div class="answer-sheet-content">
                <button class="btn-close" onclick="closeAnswerSheet()">&times;</button>
                <p>No answers found.</p>
            </div>
        `;
        return;
    }

    answerSheet.innerHTML = `
        <div class="answer-sheet-content">
            <button class="btn-close" onclick="closeAnswerSheet()">&times;</button>
            <h3>Answer Sheet - Student ${currentStudentId}, Quiz ${currentQuizNo}, Grade ${currentGrade}</h3>
            <div id="submission-questions">${answersDiv.innerHTML}</div>
        </div>
    `;

    updateTotalMarks();
    // Add event listeners to question marks inputs
    document.querySelectorAll('#submission-questions .question-marks').forEach(input => {
        input.addEventListener('input', updateTotalMarks);
    });
}

function closeAnswerSheet() {
    const answerSheet = document.getElementById("answer-sheet");
    answerSheet.style.display = "none";
    answerSheet.innerHTML = '';
}

function updateTotalMarks() {
    let total = 0;
    document.querySelectorAll('#submission-questions .question-marks').forEach(input => {
        total += parseInt(input.value) || 0;
    });
    const totalElement = document.querySelector('#submission-questions .total-marks');
    if (totalElement) {
        totalElement.textContent = total;
    }
}

function submitMarks() {
    const answers = [];
    document.querySelectorAll('#submission-questions .question-marks').forEach(input => {
        answers.push({ answer_id: input.dataset.answerid, marks: input.value });
    });

    const totalMarksElement = document.querySelector('#submission-questions .total-marks');
    const totalMarks = totalMarksElement ? totalMarksElement.textContent : '0';

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'submit_marks.php';

    const submissionInput = document.createElement('input');
    submissionInput.type = 'hidden';
    submissionInput.name = 'submission_id';
    submissionInput.value = currentSubmissionId;
    form.appendChild(submissionInput);

    const answersInput = document.createElement('input');
    answersInput.type = 'hidden';
    answersInput.name = 'answers';
    answersInput.value = JSON.stringify(answers);
    form.appendChild(answersInput);

    const totalInput = document.createElement('input');
    totalInput.type = 'hidden';
    totalInput.name = 'total_marks';
    totalInput.value = totalMarks;
    form.appendChild(totalInput);

    document.body.appendChild(form);
    form.submit();
}

// Close answer sheet when clicking outside of it
window.onclick = function(event) {
    const answerSheet = document.getElementById('answer-sheet');
    if (event.target == answerSheet) {
        closeAnswerSheet();
    }
}
