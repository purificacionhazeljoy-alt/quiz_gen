<?php
session_start();
require_once "../../backend/database.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    header("Location: ../../index.php");
    exit();
}

/* FETCH RESULTS */
$stmt = $pdo->prepare("
    SELECT 
        qa.*,
        q.title,
        u.firstname,
        u.lastname,

        ROUND(
            (qa.score / qa.total_questions) * 100
        ) AS percentage

    FROM quiz_attempts qa

    JOIN quizzes q
        ON qa.quiz_id = q.quiz_id

    JOIN users u
        ON qa.student_id = u.user_id

    WHERE qa.status = 'submitted'

    ORDER BY qa.attempt_id DESC
");

$stmt->execute();

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student Results</title>

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

            

        <div class="col-md-10 p-0">

            <nav class="navbar navbar-expand-lg bg-white shadow-sm px-4 py-3">

                <div class="container-fluid">

                    <div>

                        <h4 class="mb-0 fw-bold">
                            Student Results
                        </h4>

                        <small class="text-muted">
                            Monitor student results
                        </small>

                    </div>

                    <a href="profile.php" class="btn btn-light border">

                        <i class="bi bi-person-circle"></i>
                        <?= htmlspecialchars($_SESSION['name']); ?>

                    </a>

                </div>

            </nav>

            <div class="p-4">

                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-body p-4">
                        <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                            <table class="table table-hover align-middle mb-0">

                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Student</th>
                                        <th>Quiz</th>
                                        <th>Score</th>
                                        <th>Percentage</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php foreach ($results as $index => $result): ?>

                                        <tr>

                                            <td>
                                                <?= $index + 1 ?>
                                            </td>

                                            <td>
                                                <?= htmlspecialchars($result['firstname']) ?>
                                                <?= htmlspecialchars($result['lastname']) ?>
                                            </td>

                                            <td>
                                                <?= htmlspecialchars($result['title']) ?>
                                            </td>

                                            <td>
                                                <?= $result['score'] ?> /
                                                <?= $result['total_questions'] ?>
                                            </td>

                                            <td>
                                                <?= $result['percentage'] ?>%
                                            </td>

                                            <td>

                                                <?php if ($result['percentage'] >= 75): ?>

                                                    <span class="badge bg-success">
                                                        Passed
                                                    </span>

                                                <?php else: ?>

                                                    <span class="badge bg-danger">
                                                        Failed
                                                    </span>

                                                <?php endif; ?>

                                            </td>

                                            <td>
                                                <?= $result['submitted_at'] ?>
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