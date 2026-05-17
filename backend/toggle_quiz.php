<?php
session_start();
require_once "database.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    header("Location: ../index.php");
    exit();
}

$id = $_GET['id'];
$teacher_id = $_SESSION['user_id'];

/* =========================
   GET CURRENT STATUS
========================= */

$stmt = $pdo->prepare("
    SELECT status FROM quizzes
    WHERE quiz_id = ? AND teacher_id = ?
");

$stmt->execute([$id, $teacher_id]);
$quiz = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$quiz) {
    die("Quiz not found");
}

/* =========================
   TOGGLE STATUS
========================= */

$new_status = ($quiz['status'] == 'published') ? 'draft' : 'published';

$update = $pdo->prepare("
    UPDATE quizzes
    SET status = ?
    WHERE quiz_id = ? AND teacher_id = ?
");

$update->execute([$new_status, $id, $teacher_id]);

header("Location: ../frontend/teacher/my_quizzes.php");
exit();