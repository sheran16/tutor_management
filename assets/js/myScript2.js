const scheduleData = {
    "Grade 1": [
        {day: "Saturday", time: "8:00 AM - 10:00 AM"},
    ],
    "Grade 2": [
        {day: "Saturday", time: "10:00 AM - 12:00 PM"}
    ],
    "Grade 3": [
        {day: "Friday", time: "3:00 PM - 5:00 PM"}
    ],
    "Grade 4": [
        {day: "Thursday", time: "8:00 AM - 10:00 AM"}
    ],
    "Grade 5": [
        {day: "Saturday", time: "12:00 PM - 2:00 PM"}
    ]
};

function showGradeDetails(grade) {
    document.getElementById('gradesView').style.display = 'none';
    document.getElementById('gradeDetailsView').style.display = 'block';
    document.getElementById('gradeHeader').innerText = grade + " - Class Details";

    const scheduleContainer = document.getElementById('scheduleContainer');
    scheduleContainer.innerHTML = '';
    const schedules = scheduleData[grade] || [];
    schedules.forEach(sch => {
        const div = document.createElement('div');
        div.classList.add('schedule-block');
        div.innerHTML = `<p><strong>Day:</strong> ${sch.day}</p>
                         <p><strong>Time:</strong> ${sch.time}</p>`;
        scheduleContainer.appendChild(div);
    });
}

function backToGrades() {
    document.getElementById('gradesView').style.display = 'flex';
    document.getElementById('gradeDetailsView').style.display = 'none';
}
