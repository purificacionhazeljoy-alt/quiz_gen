<?php
session_start();
require_once "../../backend/database.php";

$stmt = $pdo->query("
SELECT
    qa.*,
    q.title,
    u.firstname,
    u.lastname
FROM quiz_attempts qa
JOIN quizzes q ON qa.quiz_id = q.quiz_id
JOIN users u ON qa.student_id = u.user_id
ORDER BY qa.attempt_id DESC
");

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Results</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="style.css">
</head>

<body style="background:#f4f6f9;">

    <div class="container-fluid">
        <div class="row">

            <div class="col-md-2 p-0">
                <?php include "nav.php"; ?>
            </div>

            <div class="col-md-10 p-4">

                <div class="card border-0 shadow-sm">
                    <div class="card-body">

                        <h4 class="fw-bold mb-3">Quiz Results</h4>

                        <div class="table-responsive dashboard-scroll">

                            <table class="table table-hover align-middle">
                                <thead class="table-light sticky-top">
                                    <tr>
                                        <th>Student</th>
                                        <th>Quiz</th>
                                        <th>Score</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <?php foreach ($results as $row): ?>
                                        <tr>
                                            <td>
                                                <?= htmlspecialchars($row['firstname']) ?>
                                                <?= htmlspecialchars($row['lastname']) ?>
                                            </td>
                                            <td><?= htmlspecialchars($row['title']) ?></td>
                                            <td><?= $row['score'] ?>/<?= $row['total_questions'] ?></td>
                                            <td>
                                                <span class="badge bg-success">
                                                    <?= ucfirst($row['status']) ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>

                                </tbody>
                            </table>

                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

</body>

</html>