<?php

session_start();

require_once "../../backend/database.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}

$quiz_id = $_GET['id'] ?? 0;

/* FETCH QUIZ */
$stmt = $pdo->prepare("
    SELECT *
    FROM quizzes
    WHERE quiz_id = ?
");

$stmt->execute([$quiz_id]);

$quiz = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$quiz) {
    die("Quiz not found");
}

/* FETCH QUESTIONS */
$stmt = $pdo->prepare("
    SELECT *
    FROM questions
    WHERE quiz_id = ?
");

$stmt->execute([$quiz_id]);

$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>View Quiz</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>

        body{
            background:#f4f6f9;
        }

        .quiz-header{
            background: linear-gradient(135deg,#4f46e5,#7c3aed);
            color:white;
            border-radius:20px;
            padding:35px;
        }

        .question-card{
            border-radius:18px;
            transition:0.3s ease;
        }

        .question-card:hover{
            transform:translateY(-3px);
        }

        .option-box{
            background:#f8fafc;
            border-radius:12px;
            padding:12px 15px;
            margin-bottom:10px;
        }

    </style>

</head>

<body>

<div class="container py-4">

    <div class="quiz-header shadow-sm mb-4">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

            <div>

                <h2 class="fw-bold mb-2">

                    <?= htmlspecialchars($quiz['title']) ?>

                </h2>

                <p class="mb-0">

                    <?= htmlspecialchars($quiz['description'] ?? 'No description') ?>

                </p>

            </div>

            <div>

                <span class="badge bg-light text-dark fs-6 px-3 py-2">

                    <?= ucfirst($quiz['difficulty']) ?>

                </span>

            </div>

        </div>

    </div>


    <!-- STATS -->
    <div class="row g-3 mb-4">

        <div class="col-md-4">

            <div class="card border-0 shadow-sm p-3">

                <h6 class="text-muted">
                    Total Questions
                </h6>

                <h2 class="fw-bold">

                    <?= count($questions) ?>

                </h2>

            </div>

        </div>

    </div>


    <!-- QUESTIONS -->
    <div class="row g-4">

        <?php foreach($questions as $index => $q): ?>

            <div class="col-12">

                <div class="card border-0 shadow-sm question-card">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center mb-3">

                            <h5 class="fw-bold mb-0">

                                Question <?= $index + 1 ?>

                            </h5>

                            <span class="badge bg-dark">

                                <?= ucfirst($q['question_type']) ?>

                            </span>

                        </div>

                        <p class="fs-5">

                            <?= htmlspecialchars($q['question']) ?>

                        </p>

                        <?php if($q['question_type'] == 'multiple_choice'): ?>

                            <div class="option-box">
                                A. <?= htmlspecialchars($q['option_a']) ?>
                            </div>

                            <div class="option-box">
                                B. <?= htmlspecialchars($q['option_b']) ?>
                            </div>

                            <div class="option-box">
                                C. <?= htmlspecialchars($q['option_c']) ?>
                            </div>

                            <div class="option-box">
                                D. <?= htmlspecialchars($q['option_d']) ?>
                            </div>

                        <?php elseif($q['question_type'] == 'true_false'): ?>

                            <div class="option-box">True</div>

                            <div class="option-box">False</div>

                        <?php endif; ?>


                        <div class="alert alert-success mt-3 mb-0">

                            <strong>Correct Answer:</strong>

                            <?= htmlspecialchars($q['correct_answer']) ?>

                        </div>

                    </div>

                </div>

            </div>

        <?php endforeach; ?>

    </div>

</div>

</body>
</html>