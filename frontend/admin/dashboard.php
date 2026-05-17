<?php
session_start();

$current_page = basename($_SERVER['PHP_SELF']);

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../../index.php");
    exit();
}

require_once "../../backend/database.php";

$totalUsers = $pdo->query("
    SELECT COUNT(*) FROM users
")->fetchColumn();

$totalTeachers = $pdo->query("
    SELECT COUNT(*) FROM users
    WHERE role='teacher'
")->fetchColumn();

$totalStudents = $pdo->query("
    SELECT COUNT(*) FROM users
    WHERE role='student'
")->fetchColumn();

$totalQuizzes = $pdo->query("
    SELECT COUNT(*) FROM quizzes
")->fetchColumn();

/* RECENT USERS */
$stmt = $pdo->query("
    SELECT *
    FROM users
    ORDER BY user_id DESC
    LIMIT 5
");

$recentUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="style.css">

    <style>

        .dashboard-card{
            border-radius: 14px;
            transition: 0.2s ease;
        }

        .dashboard-card:hover{
            transform: translateY(-3px);
        }

        .action-card{
            border-radius: 14px;
            transition: 0.2s ease;
            min-height: 150px;
        }

        .action-card:hover{
            transform: translateY(-4px);
        }

        .dashboard-scroll{
            max-height: 320px;
            overflow-y: auto;
        }

        .dashboard-scroll thead th{
            position: sticky;
            top: 0;
            background: #f8f9fa;
            z-index: 2;
        }

        .table td,
        .table th{
            vertical-align: middle;
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

            <!-- TOPBAR -->
            <nav class="navbar navbar-expand-lg bg-white shadow-sm px-4 py-3">

                <div class="container-fluid">

                    <div>

                        <h4 class="mb-0 fw-bold">
                            Admin Dashboard
                        </h4>

                        <small class="text-muted">
                            Manage users, quizzes, and system activity
                        </small>

                    </div>

                    <a href="#" class="btn btn-light border">

                        <i class="bi bi-person-circle"></i>
                        <?= htmlspecialchars($_SESSION['name']); ?>

                    </a>

                </div>

            </nav>

            <div class="p-4">

                <!-- WELCOME -->
                <div class="card border-0 shadow-sm mb-4 dashboard-card">

                    <div class="card-body p-4">

                        <h3 class="fw-bold mb-2">
                            Welcome back,
                            <?= htmlspecialchars($_SESSION['name']); ?>
                        </h3>

                        <p class="text-muted mb-0">
                            Monitor the platform and manage system users.
                        </p>

                    </div>

                </div>

                <!--ACTIONS -->
                <div class="row g-3 mb-4">

                    <div class="col-md-4">

                        <a href="user.php" class="text-decoration-none">

                            <div class="card border-0 shadow-sm action-card">

                                <div class="card-body">

                                    <i class="bi bi-people fs-2 text-primary"></i>

                                    <h6 class="fw-bold mt-3 mb-1">
                                        Users Management
                                    </h6>

                                    <small class="text-muted">
                                        Manage teachers and students
                                    </small>

                                </div>

                            </div>

                        </a>

                    </div>

                    <div class="col-md-4">

                        <a href="#" class="text-decoration-none">

                            <div class="card border-0 shadow-sm action-card">

                                <div class="card-body">

                                    <i class="bi bi-journal-text fs-2 text-success"></i>

                                    <h6 class="fw-bold mt-3 mb-1">
                                        Quiz Management
                                    </h6>

                                    <small class="text-muted">
                                        View platform quizzes
                                    </small>

                                </div>

                            </div>

                        </a>

                    </div>

                    <div class="col-md-4">

                        <a href="#" class="text-decoration-none">

                            <div class="card border-0 shadow-sm action-card">

                                <div class="card-body">

                                    <i class="bi bi-activity fs-2 text-danger"></i>

                                    <h6 class="fw-bold mt-3 mb-1">
                                        Activity Logs
                                    </h6>

                                    <small class="text-muted">
                                        Monitor system activity
                                    </small>

                                </div>

                            </div>

                        </a>

                    </div>

                </div>

                <!-- TABLES -->
                <div class="row g-3">

                    <!-- SYSTEM OVERVIEW -->
                    <div class="col-md-4">

                        <div class="card border-0 shadow-sm dashboard-card h-100">

                            <div class="card-body">

                                <h5 class="fw-bold mb-4">
                                    System Overview
                                </h5>

                                <div class="mb-3">
                                    <small class="text-muted">Total Users</small>
                                    <h3 class="fw-bold"><?= $totalUsers ?></h3>
                                </div>

                                <div class="mb-3">
                                    <small class="text-muted">Teachers</small>
                                    <h3 class="fw-bold"><?= $totalTeachers ?></h3>
                                </div>

                                <div class="mb-3">
                                    <small class="text-muted">Students</small>
                                    <h3 class="fw-bold"><?= $totalStudents ?></h3>
                                </div>

                                <div>
                                    <small class="text-muted">Quizzes</small>
                                    <h3 class="fw-bold"><?= $totalQuizzes ?></h3>
                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- RECENT USERS -->
                    <div class="col-md-8">

                        <div class="card border-0 shadow-sm dashboard-card">

                            <div class="card-body">

                                <div class="d-flex justify-content-between mb-3">

                                    <h5 class="fw-bold mb-0">
                                        Recent Users
                                    </h5>

                                    <a href="user.php"
                                       class="btn btn-sm btn-outline-primary">
                                        View All
                                    </a>

                                </div>

                                <div class="dashboard-scroll">

                                    <table class="table table-hover align-middle">

                                        <thead class="table-light">

                                            <tr>

                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Role</th>

                                            </tr>

                                        </thead>

                                        <tbody>

                                            <?php foreach($recentUsers as $user): ?>

                                                <tr>

                                                    <td>
                                                        <?= htmlspecialchars($user['firstname']) ?>
                                                        <?= htmlspecialchars($user['lastname']) ?>
                                                    </td>

                                                    <td>
                                                        <?= htmlspecialchars($user['email']) ?>
                                                    </td>

                                                    <td>

                                                        <?php if($user['role'] == 'admin'): ?>

                                                            <span class="badge bg-danger">
                                                                Admin
                                                            </span>

                                                        <?php elseif($user['role'] == 'teacher'): ?>

                                                            <span class="badge bg-primary">
                                                                Teacher
                                                            </span>

                                                        <?php else: ?>

                                                            <span class="badge bg-success">
                                                                Student
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

</body>

</html>
