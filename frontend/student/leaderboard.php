<?php
session_start();

require_once "../../backend/database.php";

/* =========================
   GET TOP STUDENTS
========================= */

$stmt = $pdo->prepare("CALL GetTopStudents()");
$stmt->execute();

$leaders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Important for MySQL procedures
while ($stmt->nextRowset()) {;}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Leaderboard</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="style.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body class="bg-light">

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

                        <h5 class="mb-0">
                            Leaderboard
                        </h5>

                        <a href="profile.php" class="btn btn-light btn-sm">

                            <i class="bi bi-person-circle"></i>

                            <?= htmlspecialchars($_SESSION['name']); ?>

                        </a>

                    </div>

                </nav>

                <div class="p-4">

                    <table class="table table-bordered table-hover">

                        <thead class="table-dark">

                            <tr>

                                <th>Rank</th>
                                <th>Student</th>
                                <th>Average Score</th>

                            </tr>

                        </thead>

                        <tbody>

                            <?php if (count($leaders) > 0): ?>

                                <?php foreach ($leaders as $index => $leader): ?>

                                    <tr>

                                        <td>
                                            #<?= $index + 1; ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($leader['fullname']); ?>
                                        </td>

                                        <td>
                                            <?= round($leader['average_score'], 2); ?>
                                        </td>

                                    </tr>

                                <?php endforeach; ?>

                            <?php else: ?>

                                <tr>

                                    <td colspan="3" class="text-center">
                                        No leaderboard data found.
                                    </td>

                                </tr>

                            <?php endif; ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</body>

</html>
