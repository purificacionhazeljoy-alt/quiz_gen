<?php
session_start();
require_once "../../backend/database.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../../index.php");
    exit();
}

$student_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT 
        qa.*,
        q.title,
        q.difficulty
    FROM quiz_attempts qa
    JOIN quizzes q
        ON qa.quiz_id = q.quiz_id
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

    <title>My Attempts</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="style.css">

</head>

<body style="background:#f4f6f9;">

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
                        <h5 class="mb-0">My Attempts</h5>

                        <a href="profile.php" class="btn btn-light btn-sm">
                            <i class="bi bi-person-circle"></i> <?= htmlspecialchars($_SESSION['name']); ?>
                        </a>
                    </div>
                </nav>

                <div class="p-4">

                    <div class="card shadow border-0">

                        <div class="card-body">

                            <div class="table-responsive">

                                <table class="table table-hover align-middle">

                                    <thead class="table-dark">

                                        <tr>

                                            <th>#</th>
                                            <th>Quiz</th>
                                            <th>Difficulty</th>
                                            <th>Score</th>
                                            <th>Status</th>
                                            <th>Submitted</th>
                                            <th>Performance</th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                        <?php if (count($attempts) > 0): ?>

                                            <?php foreach ($attempts as $index => $a): ?>

                                                <?php
                                                $score = $a['score'] ?? 0;
                                                $total = $a['total_questions'] ?? 0;

                                                $percent =
                                                    ($total > 0)
                                                    ? round(($score / $total) * 100)
                                                    : 0;
                                                ?>

                                                <tr>

                                                    <td>
                                                        <?= $index + 1 ?>
                                                    </td>

                                                    <td>
                                                        <strong>
                                                            <?= htmlspecialchars($a['title']) ?>
                                                        </strong>
                                                    </td>

                                                    <td>

                                                        <?php if ($a['difficulty'] == 'easy'): ?>

                                                            <span class="badge bg-success">
                                                                Easy
                                                            </span>

                                                        <?php elseif ($a['difficulty'] == 'medium'): ?>

                                                            <span class="badge bg-warning text-dark">
                                                                Medium
                                                            </span>

                                                        <?php else: ?>

                                                            <span class="badge bg-danger">
                                                                Hard
                                                            </span>

                                                        <?php endif; ?>

                                                    </td>

                                                    <td>

                                                        <?= $score ?> / <?= $total ?>

                                                    </td>

                                                    <td>

                                                        <?php if ($a['status'] == 'submitted'): ?>

                                                            <span class="badge bg-primary">
                                                                Submitted
                                                            </span>

                                                        <?php else: ?>

                                                            <span class="badge bg-secondary">
                                                                Ongoing
                                                            </span>

                                                        <?php endif; ?>

                                                    </td>

                                                    <td>

                                                        <?= $a['submitted_at'] ?? '---' ?>

                                                    </td>

                                                    <td style="width:220px;">

                                                        <div class="progress">

                                                            <div class="progress-bar" role="progressbar"
                                                                style="width: <?= $percent ?>%;">

                                                                <?= $percent ?>%

                                                            </div>

                                                        </div>

                                                    </td>

                                                </tr>

                                            <?php endforeach; ?>

                                        <?php else: ?>

                                            <tr>

                                                <td colspan="7" class="text-center py-4">

                                                    No quiz attempts yet.

                                                </td>

                                            </tr>

                                        <?php endif; ?>

                                    </tbody>

                                </table>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>