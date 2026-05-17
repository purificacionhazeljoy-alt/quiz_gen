<?php
require_once "../../backend/database.php";

$stmt = $pdo->prepare("
    SELECT 
        qu.title,
        ROUND(
            (SUM(aa.is_correct) / COUNT(aa.answer_id)) * 100
        ) AS avg_score
    FROM attempt_answers aa
    JOIN quiz_attempts qa
        ON aa.attempt_id = qa.attempt_id
    JOIN quizzes qu
        ON qa.quiz_id = qu.quiz_id
    GROUP BY qa.quiz_id
");

$stmt->execute();

$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($data);