<?php
session_start();
require_once "../../backend/database.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../../index.php");
    exit();
}

/* FETCH AVAILABLE QUIZZES */

$stmt = $pdo->prepare("
    SELECT 
        q.quiz_id,
        q.title,
        q.description,
        q.difficulty,
        q.created_at,
        CONCAT(u.firstname, ' ', u.lastname) AS teacher_name
    FROM quizzes q
    INNER JOIN users u ON q.teacher_id = u.user_id
    WHERE q.status = 'published'
    ORDER BY q.created_at DESC
");

$stmt->execute();
$quizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Available Quizzes</title>

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

            <!-- CONTENT -->
            <div class="col-md-10 p-0">

                <nav class="navbar navbar-expand-lg topbar px-3">
                    <div class="container-fluid">
                        <h5 class="mb-0">Quizzes</h5>

                        <a href="profile.php" class="btn btn-light btn-sm">
                            <i class="bi bi-person-circle"></i> <?= htmlspecialchars($_SESSION['name']); ?>
                        </a>
                    </div>
                </nav>


                <div class="p-4">

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3>Available Quizzes</h3>
                    </div>

                    <?php if (count($quizzes) == 0): ?>
                        <div class="alert alert-info">
                            No quizzes available at the moment.
                        </div>
                    <?php endif; ?>

                    <div class="row">

                        <?php foreach ($quizzes as $quiz): ?>

                            <div class="col-md-4 mb-4">

                                <div class="card shadow-sm border-0 h-100">

                                    <div class="card-body d-flex flex-column">

                                        <h5 class="fw-bold">
                                            <?= htmlspecialchars($quiz['title']); ?>
                                        </h5>

                                        <p class="text-muted small">
                                            <?= htmlspecialchars($quiz['description'] ?: 'No description'); ?>
                                        </p>

                                        <p class="mb-1">
                                            <strong>Teacher:</strong>
                                            <?= htmlspecialchars($quiz['teacher_name']); ?>
                                        </p>

                                        <p class="mb-1">
                                            <strong>Difficulty:</strong>
                                            <?= ucfirst($quiz['difficulty']); ?>
                                        </p>

                                        <p class="mb-3">
                                            <strong>Timer:</strong>
                                            <?= $quiz['timer']; ?> mins
                                        </p>

                                        <div class="mt-auto">

                                            <a href="take_quiz.php?id=<?= $quiz['quiz_id']; ?>"
                                                class="btn btn-primary w-100">

                                                Take Quiz
                                            </a>

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

</body>

</html>