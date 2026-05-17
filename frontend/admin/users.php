<?php
session_start();
require_once "../../backend/database.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../index.php");
    exit();
}

$stmt = $pdo->query("SELECT * FROM users ORDER BY user_id DESC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Management</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
</head>
<body style="background:#f4f6f9;">

<div class="container-fluid">
    <div class="row">

        <div class="col-md-2 p-0">
            <?php include "nav.php"; ?>
        </div>

        <div class="col-md-10 p-0">

            <nav class="navbar bg-white shadow-sm px-4 py-3">
                <div>
                    <h4 class="fw-bold mb-0">Users Management</h4>
                    <small class="text-muted">Manage teachers and students</small>
                </div>
            </nav>

            <div class="p-4">

                <div class="card border-0 shadow-sm">
                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold mb-0">All Users</h5>
                        </div>

                        <div class="table-responsive dashboard-scroll">

                            <table class="table table-hover align-middle">

                                <thead class="table-light sticky-top">
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <?php foreach($users as $index => $user): ?>

                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td>
                                            <?= htmlspecialchars($user['firstname']) ?>
                                            <?= htmlspecialchars($user['lastname']) ?>
                                        </td>
                                        <td><?= htmlspecialchars($user['email']) ?></td>
                                        <td>
                                            <span class="badge bg-primary">
                                                <?= ucfirst($user['role']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-success">Active</span>
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

</body>
</html>