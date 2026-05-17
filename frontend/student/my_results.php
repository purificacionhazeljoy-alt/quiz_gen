<?php
session_start();
require_once "../../backend/database.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}

$student_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT 
        qa.attempt_id,
        qa.score,
        qa.total_questions,
        qa.submitted_at,
        q.title
    FROM quiz_attempts qa
    JOIN quizzes q ON qa.quiz_id = q.quiz_id
    WHERE qa.student_id = ?
    ORDER BY qa.attempt_id DESC
");

$stmt->execute([$student_id]);

$attempts = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-light">

    <div class="container-fluid">

        <div class="row">

            <!-- SIDEBAR -->
            <div class="col-md-2 p-0">
                <?php include "nav.php"; ?>
            </div>

            <!-- MAIN CONTENT -->
            <div class="col-md-10 p-0">

                <nav class="navbar navbar-expand-lg topbar px-3">
                    <div class="container-fluid">
                        <h5 class="mb-0">My Quiz Results</h5>

                        <a href="profile.php" class="btn btn-light btn-sm">
                            <i class="bi bi-person-circle"></i> <?= htmlspecialchars($_SESSION['name']); ?>
                        </a>
                    </div>
                </nav>

                <div class="p-4">

                    <div class="row">

                        <?php foreach ($attempts as $a): ?>

                            <?php
                            $total = $a['total_questions'] ?? 0;
                            $score = $a['score'] ?? 0;

                            $percent = ($total > 0) ? ($score / $total) * 100 : 0;
                            ?>

                            <div class="col-md-4 mb-4">

                                <div class="card shadow result-card p-4">

                                    <div class="d-flex justify-content-between align-items-center">

                                        <div>
                                            <h5 class="fw-bold">
                                                <?= htmlspecialchars($a['title']) ?>
                                            </h5>

                                            <small class="text-muted">
                                                <?= $a['submitted_at'] ?? 'Not submitted' ?>
                                            </small>
                                        </div>

                                        <div class="score-circle">
                                            <?= round($percent) ?>%
                                        </div>

                                    </div>

                                    <hr>

                                    <p>
                                        Score:
                                        <b><?= $a['score'] ?? 0 ?>/<?= $a['total_questions'] ?? 0 ?></b>

                                    </p>

                                    <?php if ($percent >= 80): ?>

                                        <span class="badge bg-success">
                                            Excellent
                                        </span>

                                    <?php elseif ($percent >= 60): ?>

                                        <span class="badge bg-warning text-dark">
                                            Good
                                        </span>

                                    <?php else: ?>

                                        <span class="badge bg-danger">
                                            Needs Improvement
                                        </span>

                                    <?php endif; ?>

                                    <button class="btn btn-primary w-100 mt-3" data-bs-toggle="modal"
                                        data-bs-target="#reviewModal<?= $a['attempt_id'] ?>">

                                        View Review

                                    </button>

                                </div>

                            </div>

                            <?php

                            $reviewStmt = $pdo->prepare("
    SELECT 
        aa.*,
        q.question,
        q.correct_answer
    FROM attempt_answers aa
    JOIN questions q 
        ON aa.question_id = q.question_id
    WHERE aa.attempt_id = ?
");

                            $reviewStmt->execute([$a['attempt_id']]);

                            $reviewData = $reviewStmt->fetchAll(PDO::FETCH_ASSOC);

                            ?>

                            <div class="modal fade" id="reviewModal<?= $a['attempt_id'] ?>" tabindex="-1">

                                <div class="modal-dialog modal-lg modal-dialog-scrollable">

                                    <div class="modal-content">

                                        <div class="modal-header">

                                            <h5 class="modal-title">
                                                Quiz Review
                                            </h5>

                                            <button type="button" class="btn-close" data-bs-dismiss="modal">
                                            </button>

                                        </div>

                                        <div class="modal-body">

                                            <?php foreach ($reviewData as $r): ?>

                                                <div class="card p-3 mb-3">

                                                    <h6>
                                                        <?= htmlspecialchars($r['question']) ?>
                                                    </h6>

                                                    <hr>

                                                    <p>
                                                        <b>Your Answer:</b>
                                                        <?= htmlspecialchars($r['selected_answer']) ?>
                                                    </p>

                                                    <p>
                                                        <b>Correct Answer:</b>
                                                        <?= htmlspecialchars($r['correct_answer']) ?>
                                                    </p>

                                                    <?php if ($r['is_correct']): ?>

                                                        <span class="badge bg-success">
                                                            Correct
                                                        </span>

                                                    <?php else: ?>

                                                        <span class="badge bg-danger">
                                                            Wrong
                                                        </span>

                                                    <?php endif; ?>

                                                </div>

                                            <?php endforeach; ?>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        <?php endforeach; ?>

                    </div>

                </div>

            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>