<?php
session_start();
require_once "../../backend/database.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    header("Location: ../../index.php");
    exit();
}

$teacher_id = $_SESSION['user_id'];

/* FETCH QUIZZES */

$stmt = $pdo->prepare("
    SELECT * FROM quizzes
    WHERE teacher_id = ?
    ORDER BY created_at DESC
");

$stmt->execute([$teacher_id]);
$quizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>

<head>
    <title>My Quizzes</title>

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

            <!-- MAIN CONTENT -->
            <div class="col-md-10 p-4">

                <div class="d-flex justify-content-between align-items-center mb-3">

                    <h3 class="mb-0">My Quizzes</h3>

                    <a href="create_quiz.php" class="btn btn-primary">
                        + Create Quiz
                    </a>

                </div>

                <div class="card shadow p-3">

                    <table class="table table-bordered table-hover">

                        <thead class="table-dark">
                            <tr>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Difficulty</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php if (count($quizzes) > 0): ?>

                                <?php foreach ($quizzes as $quiz): ?>

                                    <tr>

                                        <td><?= htmlspecialchars($quiz['title']); ?></td>

                                        <td>
                                            <span
                                                class="badge bg-<?= $quiz['status'] == 'published' ? 'success' : 'secondary'; ?>">
                                                <?= ucfirst($quiz['status']); ?>
                                            </span>
                                        </td>

                                        <td>
                                            <span class="badge bg-<?=
                                                $quiz['difficulty'] == 'easy' ? 'success' :
                                                ($quiz['difficulty'] == 'medium' ? 'warning' : 'danger');
                                            ?>">
                                                <?= ucfirst($quiz['difficulty']); ?>
                                            </span>
                                        </td>

                                        <td><?= $quiz['created_at']; ?></td>

                                        <td>

                                            <!-- EDIT -->
                                            <a href="edit_quiz.php?quiz_id=<?= $quiz['quiz_id']; ?>"
                                                class="btn btn-sm btn-warning">
                                                Edit
                                            </a>

                                            <!-- QUESTIONS -->
                                            <a href="manage_questions.php?quiz_id=<?= $quiz['quiz_id']; ?>"
                                                class="btn btn-sm btn-primary">
                                                Questions
                                            </a>

                                            <!-- PUBLISH / UNPUBLISH -->
                                            <a href="../../backend/toggle_quiz.php?id=<?= $quiz['quiz_id']; ?>"
                                                class="btn btn-sm btn-info">
                                                <?= $quiz['status'] == 'published' ? 'Unpublish' : 'Publish'; ?>
                                            </a>

                                            <!-- DELETE -->
                                            <form method="POST" action="../../backend/quiz_crud.php" class="d-inline">

                                                <input type="hidden" name="quiz_id" value="<?= $quiz['quiz_id']; ?>">

                                                <button type="submit" name="delete_quiz" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Delete this quiz?')">

                                                    Delete

                                                </button>

                                            </form>
                                        </td>

                                    </tr>

                                <?php endforeach; ?>

                            <?php else: ?>

                                <tr>
                                    <td colspan="5" class="text-center text-muted">
                                        No quizzes found.
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