<?php
require_once "../../backend/database.php";


$stmt = $pdo->prepare("
    SELECT qa.*, q.title, u.firstname
    FROM quiz_attempts qa
    JOIN quizzes q ON qa.quiz_id = q.quiz_id
    JOIN users u ON qa.student_id = u.user_id
    ORDER BY qa.attempt_id DESC
");

$stmt->execute();
$data = $stmt->fetchAll();

foreach ($data as $a) {
    echo "<div class='card p-2 mb-2'>";
    echo "<b>{$a['firstname']}</b> - {$a['title']} - Score: {$a['score']}";
    echo "</div>";
}
?>