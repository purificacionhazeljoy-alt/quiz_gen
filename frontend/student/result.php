<?php
session_start();
require_once "../../backend/database.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../../index.php");
    exit();
}

$quiz_id = $_GET['quiz_id'] ?? null;

if (!$quiz_id) {
    die("Invalid quiz ID");
}

/* QUIZ ATTEMPT */
$stmt = $pdo->prepare("
    SELECT * FROM quiz_attempts 
    WHERE quiz_id = ? AND student_id = ?
    ORDER BY attempt_id DESC
    LIMIT 1
");
$stmt->execute([$quiz_id, $_SESSION['user_id']]);
$attempt = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$attempt) {
    die("No attempt found");
}

/* SCORE */
$score = $attempt['score'] ?? 0;
$total = $attempt['total_questions'] ?? 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Quiz Result</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">

    <div class="card p-4 text-center">

        <h2>Quiz Completed</h2>

        <h4 class="mt-3">
            Score: <?= $score ?> / <?= $total ?>
        </h4>

        <a href="available_quizzes.php" class="btn btn-primary mt-3">
            Back to Quizzes
        </a>

    </div>

</div>

</body>
</html>