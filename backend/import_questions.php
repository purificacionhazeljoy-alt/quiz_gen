<?php
session_start();
require_once "database.php";

if (!isset($_SESSION['user_id'])) {
    exit();
}

$quiz_id = $_POST['quiz_id'] ?? null;
$questions = $_POST['questions'] ?? [];

if (!$quiz_id || empty($questions)) {
    header("Location: ../frontend/teacher/manage_questions.php?quiz_id=$quiz_id");
    exit();
}

foreach ($questions as $question_id) {

    /* GET ORIGINAL QUESTION */
    $stmt = $pdo->prepare("
        SELECT *
        FROM questions
        WHERE question_id = ?
    ");

    $stmt->execute([$question_id]);

    $q = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$q) continue;

    /* INSERT COPY */
    $insert = $pdo->prepare("
        INSERT INTO questions (
            quiz_id,
            question_type,
            question,
            option_a,
            option_b,
            option_c,
            option_d,
            correct_answer
        )
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $insert->execute([
        $quiz_id,
        $q['question_type'],
        $q['question'],
        $q['option_a'],
        $q['option_b'],
        $q['option_c'],
        $q['option_d'],
        $q['correct_answer']
    ]);
}

header("Location: ../frontend/teacher/manage_questions.php?quiz_id=$quiz_id");
exit();
?>