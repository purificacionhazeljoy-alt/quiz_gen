<?php
session_start();

require_once "../../backend/database.php";
require_once "../../vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();


if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}

if ($_SESSION['role'] !== 'teacher') {
    header("Location: ../../login.php");
    exit();
}

/* RECENT QUIZ ATTEMPTS */
$stmt = $pdo->query("
    SELECT 
        qa.*,
        q.title,
        u.firstname,
        u.lastname
    FROM quiz_attempts qa
    JOIN quizzes q
        ON qa.quiz_id = q.quiz_id
    JOIN users u
        ON qa.student_id = u.user_id
    ORDER BY qa.attempt_id DESC
    LIMIT 4
");

$recentAttempts = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* RECENT QUIZZES */
$stmt = $pdo->query("
    SELECT *
    FROM quizzes
    ORDER BY created_at DESC
    LIMIT 4
");

$recentQuizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Teacher Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="style.css">

    <style>
        .stat-card {
            border-radius: 16px;
        }

        .action-card {
            transition: 0.2s ease;
            border-radius: 16px;
        }

        .action-card:hover {
            transform: translateY(-3px);
        }

        .dashboard-table-card {
            height: 300px;
            border-radius: 16px;
        }

        .dashboard-scroll {
            max-height: 200px;
            overflow-y: auto;
        }

        .dashboard-scroll thead th {
            position: sticky;
            top: 0;
            z-index: 5;
        }
    </style>

</head>

<body style="background:#f4f6f9;">

    <div class="container-fluid">

        <div class="row">

            <!-- SIDEBAR -->
            <div class="col-md-2 p-0">
                <?php include "nav.php"; ?>
            </div>

            <!-- MAIN -->
            <div class="col-md-10 p-0">

                <nav class="navbar navbar-expand-lg bg-white shadow-sm px-4 py-3">

                    <div class="container-fluid">

                        <div>

                            <h4 class="mb-0 fw-bold">
                                Teacher Dashboard
                            </h4>

                            <small class="text-muted">
                                Manage quizzes and monitor student activity
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

                            <h3 class="fw-bold mb-2">
                                Welcome back,
                                <?= htmlspecialchars($_SESSION['name']); ?>
                            </h3>

                            <p class="text-muted mb-0">
                                Create quizzes, manage questions, and monitor realtime submissions.
                            </p>

                        </div>

                    </div>


                    <!-- QUICK ACTIONS -->
                    <div class="row g-3 mb-4">

                        <div class="col-md-4">
                            <a href="create_quiz.php" class="text-decoration-none">
                                <div class="card border-0 shadow-sm action-card">
                                    <div class="card-body">
                                        <i class="bi bi-plus-circle fs-2 text-primary"></i>
                                        <h6 class="fw-bold mt-3 mb-1">Create Quiz</h6>
                                        <small class="text-muted">Add new quiz</small>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-4">
                            <a href="my_quizzes.php" class="text-decoration-none">
                                <div class="card border-0 shadow-sm action-card">
                                    <div class="card-body">
                                        <i class="bi bi-journal-text fs-2 text-success"></i>
                                        <h6 class="fw-bold mt-3 mb-1">Manage Quizzes</h6>
                                        <small class="text-muted">Edit quiz content</small>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-4">
                            <a href="live_submissions.php" class="text-decoration-none">
                                <div class="card border-0 shadow-sm action-card">
                                    <div class="card-body">
                                        <i class="bi bi-broadcast fs-2 text-danger"></i>
                                        <h6 class="fw-bold mt-3 mb-1">Live Monitoring</h6>
                                        <small class="text-muted">Realtime activity</small>
                                    </div>
                                </div>
                            </a>
                        </div>

                    </div>

                    <!-- TABLES -->
                    <div class="row g-3">
                        <!-- RECENT QUIZZES -->
                        <div class="col-md-5">

                            <div class="card border-0 shadow-sm dashboard-table-card">

                                <div class="card-body">

                                    <div class="d-flex justify-content-between mb-3">
                                        <h5 class="fw-bold mb-0">Recent Quizzes</h5>

                                        <a href="my_quizzes.php" class="btn btn-sm btn-outline-primary">
                                            View All
                                        </a>
                                    </div>

                                    <div class="dashboard-scroll">

                                        <table class="table table-hover align-middle">

                                            <thead class="table-light">
                                                <tr>
                                                    <th>Quiz</th>
                                                    <th>Difficulty</th>
                                                </tr>
                                            </thead>

                                            <tbody id="recent-quizzes-body"></tbody>

                                            <?php foreach ($recentQuizzes as $quiz): ?>

                                                <tr>

                                                    <td>
                                                        <?= htmlspecialchars($quiz['title']) ?>
                                                    </td>

                                                    <td>
                                                        <?= ucfirst($quiz['difficulty']) ?>
                                                    </td>

                                                </tr>

                                            <?php endforeach; ?>

                                            </tbody>

                                        </table>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <!-- RECENT ATTEMPTS -->
                        <div class="col-md-7">

                            <div class="card border-0 shadow-sm dashboard-table-card">

                                <div class="card-body">

                                    <h5 class="fw-bold mb-3">
                                        Recent Student Attempts
                                    </h5>

                                    <div class="dashboard-scroll">

                                        <table class="table table-hover align-middle">

                                            <thead class="table-light">

                                                <tr>
                                                    <th>Student</th>
                                                    <th>Quiz</th>
                                                    <th>Score</th>
                                                    <th>Status</th>
                                                </tr>

                                            </thead>

                                            <tbody id="recent-attempts-body">

                                                <?php foreach ($recentAttempts as $attempt): ?>

                                                    <tr>

                                                        <td>
                                                            <?= htmlspecialchars($attempt['firstname']) ?>
                                                            <?= htmlspecialchars($attempt['lastname']) ?>
                                                        </td>

                                                        <td>
                                                            <?= htmlspecialchars($attempt['title']) ?>
                                                        </td>

                                                        <td>
                                                            <?= $attempt['score'] ?>/<?= $attempt['total_questions'] ?>
                                                        </td>

                                                        <td>

                                                            <?php if ($attempt['status'] == 'submitted'): ?>

                                                                <span class="badge bg-success">
                                                                    Submitted
                                                                </span>

                                                            <?php else: ?>

                                                                <span class="badge bg-warning text-dark">
                                                                    Ongoing
                                                                </span>

                                                            <?php endif; ?>

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
            </div>
        </div>
    </div>

    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>

    <script>

        Pusher.logToConsole = true;

        const pusher = new Pusher("<?= $_ENV['PUSHER_APP_KEY'] ?>", {
            cluster: "<?= $_ENV['PUSHER_APP_CLUSTER'] ?>"
        });

        const channel = pusher.subscribe("quiz-channel");

        /* LOAD RECENT QUIZZES */
        function loadRecentQuizzes() {

            fetch("../pusher-content/recent_quizzes_fetch.php")
                .then(res => res.text())
                .then(data => {

                    document.getElementById(
                        "recent-quizzes-body"
                    ).innerHTML = data;

                });

        }

        /* LOAD RECENT ATTEMPTS */
        function loadRecentAttempts() {

            fetch("../pusher-content/recent_attempts_fetch.php")
                .then(res => res.text())
                .then(data => {

                    document.getElementById(
                        "recent-attempts-body"
                    ).innerHTML = data;

                });

        }

        /* REALTIME EVENT */
        channel.bind("submission-event", function (data) {

            loadRecentAttempts();

        });

        channel.bind("quiz-created", function (data) {

            loadRecentQuizzes();

        });

    </script>
</body>

</html>