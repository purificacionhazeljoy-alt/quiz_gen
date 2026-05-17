<?php
session_start();
require_once "../../backend/database.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    header("Location: ../../index.php");
    exit();
}

$selectedQuiz = $_GET['filter_quiz'] ?? 'all';

$sql = "
    SELECT 
        aa.*,
        q.question,
        qa.quiz_id,
        qu.title,
        u.firstname,
        u.lastname
    FROM attempt_answers aa
    JOIN questions q 
        ON aa.question_id = q.question_id
    JOIN quiz_attempts qa 
        ON aa.attempt_id = qa.attempt_id
    JOIN quizzes qu 
        ON qa.quiz_id = qu.quiz_id
    JOIN users u 
        ON qa.student_id = u.user_id
";

if ($selectedQuiz !== 'all') {
    $sql .= " WHERE qa.quiz_id = ?";
}

$sql .= " ORDER BY aa.answer_id DESC LIMIT 30";

$stmt = $pdo->prepare($sql);

if ($selectedQuiz !== 'all') {
    $stmt->execute([$selectedQuiz]);
} else {
    $stmt->execute();
}

$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="style.css">
    <style>
        .chart-card {
            height: 70vh;
        }
        .chart-wrapper {
            height: 55vh;
        }
        .table-card {
            height: 70vh;
        }
        .live-table {
            max-height: 60vh;
            overflow-y: auto;
        }
        .live-table thead th {
            position: sticky;
            top: 0;
            z-index: 10;
        }
    </style>
</head>

<body class="bg-light">

    <div class="container-fluid">

        <div class="row">

            <!-- SIDEBAR -->
            <div class="col-md-2 p-0">
                <?php include "nav.php"; ?>
            </div>

            <!-- MAIN CONTENT -->
            <div class="col-md-10 p-4">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h3 class="mb-0 fw-bold">Live Submissions</h3>
                        <small class="text-muted">Realtime student quiz monitoring</small>
                    </div>
                </div>

                <div class="row g-3">

                    <div class="card shadow-sm border-0 mb-3">
                        <div class="card-body d-flex justify-content-between align-items-center">

                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-funnel"></i>
                                <strong>Filter Quiz:</strong>
                            </div>

                            <div style="width: 300px;">
                                <select id="quizFilter" class="form-select form-select-sm">
                                    <option value="all">All Quizzes</option>

                                    <?php
                                    $quizStmt = $pdo->query("
                    SELECT DISTINCT q.quiz_id, q.title
                    FROM quizzes q
                    JOIN quiz_attempts qa ON q.quiz_id = qa.quiz_id
                    ORDER BY q.title ASC
                ");

                                    while ($quiz = $quizStmt->fetch(PDO::FETCH_ASSOC)):
                                        ?>
                                        <option value="<?= $quiz['quiz_id'] ?>">
                                            <?= htmlspecialchars($quiz['title']) ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                        </div>
                    </div>

                    <!-- CHART -->
                    <div class="col-md-5">

                        <div class="card shadow-sm border-0 chart-card">
                            <div class="card-body">
                                <h5 class="mb-3">Performance Overview</h5>
                                <div class="chart-wrapper">
                                    <canvas id="scoreChart"></canvas>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- TABLE -->
                    <div class="col-md-7">

                        <div class="card shadow-sm border-0 table-card">

                            <div class="card-body">

                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0">Live Submissions</h5>
                                </div>

                                <div class="live-table">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>#</th>
                                                <th>Student</th>
                                                <th>Quiz</th>
                                                <th>Question</th>
                                                <th>Answer</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>

                                        <tbody id="live-area">

                                            <?php foreach ($logs as $index => $log): ?>
                                                <tr>
                                                    <td><?= $index + 1 ?></td>
                                                    <td><?= htmlspecialchars($log['firstname']) ?>
                                                        <?= htmlspecialchars($log['lastname']) ?>
                                                    </td>
                                                    <td><?= htmlspecialchars($log['title']) ?></td>
                                                    <td style="max-width:250px;">
                                                        <?= htmlspecialchars($log['question']) ?>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-secondary text-wrap">
                                                            <?= htmlspecialchars($log['selected_answer']) ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <?php if ($log['is_correct']): ?>
                                                            <span class="badge bg-success">Correct</span>
                                                        <?php else: ?>
                                                            <span class="badge bg-danger">Wrong</span>
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

            <div class="position-fixed bottom-0 end-0 p-3" style="z-index:9999">

                <div id="liveToast" class="toast">

                    <div class="toast-header">
                        <strong class="me-auto">Realtime Update</strong>

                        <button type="button" class="btn-close" data-bs-dismiss="toast">
                        </button>
                    </div>

                    <div class="toast-body">
                        New submission received
                    </div>

                </div>

            </div>
        </div>
    </div>

    <script>

        Pusher.logToConsole = true;

        var pusher = new Pusher("653b3d0e7ec2a46070d5", {
            cluster: "ap1"
        });

        var channel = pusher.subscribe("quiz-channel");

        let chart;


        function loadLiveTable() {

            let selectedQuiz =
                document.getElementById("quizFilter").value;

            fetch(
                "../pusher-content/live_fetch.php?filter_quiz="
                + selectedQuiz
            )
                .then(response => response.text())
                .then(html => {

                    document.getElementById("live-area").innerHTML = html;

                    let toast = new bootstrap.Toast(
                        document.getElementById("liveToast")
                    );

                    toast.show();

                })
                .catch(error => {
                    console.log(error);
                });

        }

        async function loadChart() {

            let selectedQuiz =
                document.getElementById("quizFilter").value;

            const response = await fetch(
                "../pusher-content/chart_fetch.php?filter_quiz="
                + selectedQuiz
            );

            const data = await response.json();

            const labels = data.map(item => item.title);
            const scores = data.map(item => item.avg_score);

            const ctx = document
                .getElementById("scoreChart")
                .getContext("2d");

            if (chart) {
                chart.destroy();
            }

            chart = new Chart(ctx, {

                type: "bar",

                data: {

                    labels: labels,

                    datasets: [{
                        label: "Average Score %",
                        data: scores
                    }]
                },

                options: {

                    responsive: true,

                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100
                        }
                    }

                }

            });

        }


        document.getElementById("quizFilter")
            .addEventListener("change", function () {

                loadLiveTable();
                loadChart();

            });


        channel.bind("submission-event", function (data) {

            console.log("Realtime received:", data);

            loadLiveTable();
            loadChart();

        });

        loadChart();

    </script>

</body>

</html>