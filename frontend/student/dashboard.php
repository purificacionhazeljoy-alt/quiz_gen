<?php
session_start();
include "../../backend/database.php";

// AUTH CHECK
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}

// ROLE CHECK
if ($_SESSION['role'] !== 'student') {
    header("Location: ../../login.php");
    exit();
}

$student_id = $_SESSION['user_id'];

/* TOTAL QUIZZES TAKEN */
$stmt = $pdo->prepare("
    SELECT COUNT(*)
    FROM quiz_attempts
    WHERE student_id = ?
    AND status = 'submitted'
");
$stmt->execute([$student_id]);

$totalQuizTaken = $stmt->fetchColumn();


$stmt = $pdo->prepare("
    SELECT COUNT(*)
    FROM quiz_attempts
    WHERE student_id = ?
    AND status = 'submitted'
    AND (
        (score / total_questions) * 100
    ) >= 75
");

$stmt->execute([$student_id]);

$passedQuizzes = $stmt->fetchColumn();

/* AVERAGE SCORE PERCENTAGE */
$stmt = $pdo->prepare("
    SELECT AVG(
        (score / total_questions) * 100
    ) as averageScore
    FROM quiz_attempts
    WHERE student_id = ?
    AND status = 'submitted'
    AND total_questions > 0
");

$stmt->execute([$student_id]);

$averageData = $stmt->fetch(PDO::FETCH_ASSOC);

$averageScore = round($averageData['averageScore'] ?? 0);

/* AVAILABLE QUIZZES */
$stmt = $pdo->prepare("
    SELECT COUNT(*)
    FROM quizzes
    WHERE status = 'published'
");
$stmt->execute();

$totalAvailableQuiz = $stmt->fetchColumn();

/* RECENT RESULTS */
$stmt = $pdo->prepare("
    SELECT qa.*, q.title
    FROM quiz_attempts qa
    JOIN quizzes q
        ON qa.quiz_id = q.quiz_id
    WHERE qa.student_id = ?
AND qa.status = 'submitted'
AND qa.total_questions > 0
    ORDER BY qa.attempt_id DESC
    LIMIT 5
");
$stmt->execute([$student_id]);

$recentResults = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* AVAILABLE QUIZ LIST */
$stmt = $pdo->prepare("
    SELECT *
    FROM quizzes
    WHERE status = 'published'
    ORDER BY created_at DESC
    LIMIT 5
");
$stmt->execute();

$quizList = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                        <h5 class="mb-0">Dashboard</h5>

                        <a href="profile.php" class="btn btn-light btn-sm">
                            <i class="bi bi-person-circle"></i> <?= htmlspecialchars($_SESSION['name']); ?>
                        </a>
                    </div>
                </nav>

                <div class="p-4">
                    <div class="row">

                        <div class="col-md-3 mb-4">

                            <div class="card shadow border-0 bg-primary text-white">

                                <div class="card-body">

                                    <h5>Total Quizzes</h5>

                                    <h1>
                                        <?= $totalQuizTaken; ?>
                                    </h1>

                                </div>

                            </div>

                        </div>

                        <div class="col-md-3 mb-4">

                            <div class="card shadow border-0 bg-success text-white">

                                <div class="card-body">

                                    <h5>Average Score</h5>

                                    <h1>
                                        <?= $averageScore; ?>%
                                    </h1>

                                </div>

                            </div>

                        </div>

                        <div class="col-md-3 mb-4">

                            <div class="card shadow border-0 bg-warning text-white">

                                <div class="card-body">

                                    <h5>Passed Quizzes</h5>
                                    <h1><?= $passedQuizzes ?></h1>

                                </div>

                            </div>

                        </div>

                        <div class="col-md-3 mb-4">

                            <div class="card shadow border-0 bg-danger text-white">

                                <div class="card-body">

                                    <h5>Status</h5>

                                    <h1>Active</h1>

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="card shadow border-0">

                        <div class="card-body">

                            <h4 class="mb-3">
                                Recent Quiz Results
                            </h4>

                            <table class="table table-bordered">

                                <thead>

                                    <tr>

                                        <th>Quiz</th>
                                        <th>Score</th>
                                        <th>Total Questions</th>
                                        <th>Status</th>
                                        <th>Date</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    <?php foreach ($recentResults as $result) { ?>

                                        <tr>

                                            <td>
                                                <?= htmlspecialchars($result['title']); ?>
                                            </td>

                                            <td>
                                                <?= $result['score']; ?> / <?= $result['total_questions']; ?>
                                            </td>

                                            <td>
                                                <?= $result['total_questions']; ?>
                                            </td>

                                            <td>

                                                <?php
                                                $percentage = 0;

                                                if ($result['total_questions'] > 0) {
                                                    $percentage =
                                                        ($result['score'] / $result['total_questions']) * 100;
                                                }

                                                if ($percentage >= 75) {
                                                    echo "<span class='badge bg-success'>Passed</span>";
                                                } else {
                                                    echo "<span class='badge bg-danger'>Failed</span>";
                                                }
                                                ?>

                                            </td>

                                            <td>
                                                <?= date("M d, Y h:i A", strtotime($result['submitted_at'])); ?>
                                            </td>

                                        </tr>
                                    <?php } ?>


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