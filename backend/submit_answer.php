<?php
session_start();
require_once "../../backend/database.php";

/* save answer */
$stmt = $pdo->prepare("
    INSERT INTO attempt_answers (attempt_id, question_id, selected_answer)
    VALUES (?, ?, ?)
");

$stmt->execute([
    $_SESSION['attempt_id'] ?? 0,
    $_POST['question_id'],
    $_POST['answer']
]);

/* go next */
header("Location: take_quiz.php?quiz_id=" . $_POST['quiz_id'] . "&q=" . ($_POST['q_index'] + 1));
exit();
?>