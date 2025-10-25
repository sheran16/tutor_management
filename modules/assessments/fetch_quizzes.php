<?php
// all quizzes and questions
$quizzes_by_grade = [];

$result = $conn->query("
    SELECT a.assessment_id, a.quizNo, a.gradeID, a.maxMarks, q.questionId, q.text, q.marks 
    FROM assessment a 
    LEFT JOIN question q ON a.assessment_id = q.assessment_id
    WHERE a.active = 1
    ORDER BY a.gradeID, a.quizNo, q.questionId
");

if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
        $grade = $row['gradeID'];
        $quizId = $row['assessment_id'];
        
        if(!isset($quizzes_by_grade[$grade])) $quizzes_by_grade[$grade] = [];
        if(!isset($quizzes_by_grade[$grade][$quizId])) {
            $quizzes_by_grade[$grade][$quizId] = [
                'quizNo' => $row['quizNo'],
                'maxMarks' => $row['maxMarks'],
                'questions' => []
            ];
        }

        if($row['questionId']) {
            $quizzes_by_grade[$grade][$quizId]['questions'][] = [
                'questionId' => $row['questionId'],
                'text' => $row['text'],
                'marks' => $row['marks']
            ];
        }
    }
}
?>
