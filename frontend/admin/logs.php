<?php
session_start();
require_once "../../backend/database.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../index.php");
    exit();
}

/* SYSTEM LOGS */
$activityStmt = $pdo->query("
    SELECT 
        al.log_id,
        al.activity,
        al.log_type,
        al.created_at,
        u.firstname,
        u.lastname
    FROM activity_logs al
    JOIN users u
        ON al.user_id = u.user_id
    ORDER BY al.log_id DESC
    LIMIT 30
");

$activityLogs = $activityStmt->fetchAll(PDO::FETCH_ASSOC);

/* CHEATING LOGS */
$cheatStmt = $pdo->query("
    SELECT
        cl.cheat_id,
        cl.activity,
        cl.created_at,
        q.title,
        u.firstname,
        u.lastname
    FROM cheating_logs cl
    JOIN users u
        ON cl.student_id = u.user_id
    JOIN quizzes q
        ON cl.quiz_id = q.quiz_id
    ORDER BY cl.cheat_id DESC
    LIMIT 30
");

$cheatLogs = $cheatStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Activity Logs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="style.css">
    <style>
        .dashboard-scroll {
            max-height: 450px;
            overflow-y: auto;
            overflow-x: auto;
            border-radius: 12px;
        }

        .dashboard-scroll::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        .dashboard-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 20px;
        }

        .dashboard-scroll::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        table {
            margin-bottom: 0 !important;
        }

        thead.sticky-top th {
            position: sticky;
            top: 0;
            z-index: 10;
            background: #f8fafc !important;
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

            <!-- MAIN CONTENT -->
            <div class="col-md-10 p-4">

                <div class="d-flex justify-content-between align-items-center mb-4">

                    <div>
                        <h3 class="fw-bold mb-0">
                            Activity Logs
                        </h3>

                        <small class="text-muted">
                            Monitor system and cheating activities
                        </small>
                    </div>

                </div>

                <div class="row g-3">

                    <div class="row mb-3">

                        <div class="col-md-4">
                            <input type="text" id="searchInput" class="form-control" placeholder="Search logs...">
                        </div>

                        <div class="col-md-3">
                            <select id="typeFilter" class="form-select">
                                <option value="">All Types</option>
                                <option value="login">Login</option>
                                <option value="quiz">Quiz</option>
                                <option value="security">Security</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>

                    </div>

                    <!-- SYSTEM LOGS -->
                    <div class="col-md-6">

                        <div class="card border-0 shadow-sm">

                            <div class="card-body">

                                <h5 class="fw-bold mb-3">
                                    System Activity Logs
                                </h5>

                                <div class="dashboard-scroll table-responsive">

                                    <table class="table table-hover align-middle">

                                        <thead class="table-light sticky-top">

                                            <tr>
                                                <th>User</th>
                                                <th>Activity</th>
                                                <th>Type</th>
                                                <th>Date</th>
                                            </tr>

                                        </thead>

                                        <tbody>

                                            <?php foreach ($activityLogs as $log): ?>

                                                <tr>

                                                    <td>
                                                        <?= htmlspecialchars($log['firstname']) ?>
                                                        <?= htmlspecialchars($log['lastname']) ?>
                                                    </td>

                                                    <td>
                                                        <?= htmlspecialchars($log['activity']) ?>
                                                    </td>

                                                    <td>

                                                        <span class="badge bg-primary">
                                                            <?= ucfirst($log['log_type']) ?>
                                                        </span>

                                                    </td>

                                                    <td>
                                                        <?= $log['created_at'] ?>
                                                    </td>

                                                </tr>

                                            <?php endforeach; ?>

                                        </tbody>

                                    </table>

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- CHEATING LOGS -->
                    <div class="col-md-6">

                        <div class="card border-0 shadow-sm">

                            <div class="card-body">

                                <h5 class="fw-bold mb-3 text-danger">
                                    Cheating Detection Logs
                                </h5>

                                <div class="dashboard-scroll">

                                    <table class="table table-hover align-middle">

                                        <thead class="table-light sticky-top">

                                            <tr>
                                                <th>Student</th>
                                                <th>Quiz</th>
                                                <th>Activity</th>
                                                <th>Date</th>
                                            </tr>

                                        </thead>

                                        <tbody>

                                            <?php foreach ($cheatLogs as $log): ?>

                                                <tr>

                                                    <td>
                                                        <?= htmlspecialchars($log['firstname']) ?>
                                                        <?= htmlspecialchars($log['lastname']) ?>
                                                    </td>

                                                    <td>
                                                        <?= htmlspecialchars($log['title']) ?>
                                                    </td>

                                                    <td>

                                                        <span class="badge bg-danger">
                                                            <?= htmlspecialchars($log['activity']) ?>
                                                        </span>

                                                    </td>

                                                    <td>
                                                        <?= $log['created_at'] ?>
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


    <script>

        const searchInput = document.getElementById("searchInput");
        const typeFilter = document.getElementById("typeFilter");

        searchInput.addEventListener("keyup", filterLogs);
        typeFilter.addEventListener("change", filterLogs);

        function filterLogs() {

            const search =
                searchInput.value.toLowerCase();

            const type =
                typeFilter.value.toLowerCase();

            const rows =
                document.querySelectorAll("tbody tr");

            rows.forEach(row => {

                const text =
                    row.innerText.toLowerCase();

                const badge =
                    row.querySelector(".badge");

                const badgeText =
                    badge ? badge.innerText.toLowerCase() : "";

                const matchSearch =
                    text.includes(search);

                const matchType =
                    type === "" || badgeText.includes(type);

                row.style.display =
                    matchSearch && matchType
                        ? ""
                        : "none";
            });
        }

    </script>
</body>

</html>