<?php
session_start();

require_once "../../backend/database.php";

$stmt = $pdo->query("
    SELECT 
        u.firstname,
        u.lastname,
        SUM(qa.score) AS total_score
    FROM quiz_attempts qa
    INNER JOIN users u
        ON qa.student_id = u.user_id
    GROUP BY qa.student_id
    ORDER BY total_score DESC
");

$leaders = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                        <h5 class="mb-0">Leaderboard</h5>

                        <a href="profile.php" class="btn btn-light btn-sm">
                            <i class="bi bi-person-circle"></i> <?= htmlspecialchars($_SESSION['name']); ?>
                        </a>
                    </div>
                </nav>

                <div class="p-4">

                    <table class="table table-bordered">

                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th>Student</th>
                                <th>Total Score</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php foreach ($leaders as $index => $leader): ?>

                                <tr>

                                    <td>#<?= $index + 1; ?></td>

                                    <td>
                                        <?= htmlspecialchars($leader['firstname'] . ' ' . $leader['lastname']); ?>
                                    </td>

                                    <td><?= $leader['total_score']; ?></td>

                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>
    </div>
    

</body>

</html>