<?php
session_start();
require_once "database.php";

/* ================= CREATE QUESTION ================= */
    if (isset($_POST['quiz_id']) && !isset($_POST['update_question']) && !isset($_POST['delete_question'])) {

    $question_type = $_POST['question_type'];

    // default null values for non-multiple choice
    $option_a = '';
    $option_b = '';
    $option_c = '';
    $option_d = '';

    if ($question_type === 'multiple_choice') {
        $option_a = $_POST['option_a'];
        $option_b = $_POST['option_b'];
        $option_c = $_POST['option_c'];
        $option_d = $_POST['option_d'];
    }

    $stmt = $pdo->prepare("
        INSERT INTO questions 
        (quiz_id, question_type, question, option_a, option_b, option_c, option_d, correct_answer)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $_POST['quiz_id'],
        $question_type,
        $_POST['question'],
        $option_a,
        $option_b,
        $option_c,
        $option_d,
        $_POST['correct_answer']
    ]);

    header("Location: ../frontend/teacher/manage_questions.php?quiz_id=" . $_POST['quiz_id']);
    exit();
}


/* UPDATE QUESTION */
if (isset($_POST['update_question'])) {

    $question_type = $_POST['question_type'];

    $option_a = '';
    $option_b = '';
    $option_c = '';
    $option_d = '';

    if ($question_type === 'multiple_choice') {
        $option_a = $_POST['option_a'];
        $option_b = $_POST['option_b'];
        $option_c = $_POST['option_c'];
        $option_d = $_POST['option_d'];
    }

    $stmt = $pdo->prepare("
        UPDATE questions
        SET
            question_type = ?,
            question = ?,
            option_a = ?,
            option_b = ?,
            option_c = ?,
            option_d = ?,
            correct_answer = ?
        WHERE question_id = ?
    ");

    $stmt->execute([
        $question_type,
        $_POST['question'],
        $option_a,
        $option_b,
        $option_c,
        $option_d,
        $_POST['correct_answer'],
        $_POST['question_id']
    ]);

    header("Location: ../frontend/teacher/manage_questions.php?quiz_id=" . $_POST['quiz_id']);
    exit();
}

/* ================= DELETE QUESTION ================= */
if (isset($_POST['delete_question'])) {

    $stmt = $pdo->prepare("DELETE FROM questions WHERE question_id = ?");
    $stmt->execute([$_POST['question_id']]);

    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}
?>