<?php
session_start();
require_once "database.php";

/* ================= CREATE ================= */
if (isset($_POST['create_quiz'])) {

    $stmt = $pdo->prepare("
        INSERT INTO quizzes (teacher_id, title, quiz_type, difficulty)
VALUES (?, ?, ?, ?)
    ");


    $stmt->execute([
        $_SESSION['user_id'],
        $_POST['title'],
        $_POST['quiz_type'],
        $_POST['difficulty']
    ]);

    header("Location: ../frontend/teacher/my_quizzes.php?created=1");
    exit();
}


/* ================= UPDATE ================= */
if (isset($_POST['update_quiz'])) {

    $stmt = $pdo->prepare("
        UPDATE quizzes
        SET
            title = ?,
            status = ?
        WHERE quiz_id = ?
    ");

    $stmt->execute([
        $_POST['title'],
        $_POST['status'],
        $_POST['quiz_id']
    ]);

    header("Location: ../frontend/teacher/my_quizzes.php?updated=1");
    exit();
}

/* ================= DELETE ================= */
if (isset($_POST['delete_quiz'])) {

    $stmt = $pdo->prepare("DELETE FROM quizzes WHERE quiz_id = ?");
    $stmt->execute([$_POST['quiz_id']]);

    header("Location: ../frontend/teacher/my_quizzes.php?deleted=1");
    exit();
}

if (isset($_POST['publish_quiz'])) {

    $stmt = $pdo->prepare("UPDATE quizzes SET status = 'published' WHERE quiz_id = ?");
    $stmt->execute([$_POST['quiz_id']]);

    header("Location: ../frontend/teacher/my_quizzes.php?published=1");
    exit();
}
?>