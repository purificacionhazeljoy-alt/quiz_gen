<?php
session_start();
require_once "../../backend/database.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    header("Location: ../../index.php");
    exit();
}

$teacher_id = $_SESSION['user_id'];
$quiz_type = $_GET['quiz_type'] ?? '';

$qzStmt = $pdo->prepare("
    SELECT quiz_id, title
    FROM quizzes
    WHERE teacher_id = ?
    ORDER BY created_at DESC
");

$qzStmt->execute([$teacher_id]);
$quizzes = $qzStmt->fetchAll(PDO::FETCH_ASSOC);

$search = $_GET['search'] ?? '';

$sql = "
    SELECT 
        q.question_id,
        q.question,
        q.question_type,
        q.correct_answer,
        q.option_a,
        q.option_b,
        q.option_c,
        q.option_d,
        q.created_at
    FROM questions q
    WHERE 1=1
";

$params = [];

if (!empty($search)) {
    $sql .= " AND q.question LIKE ? ";
    $params[] = "%$search%";
}

if (!empty($_GET['type'])) {
    $sql .= " AND q.question_type = ? ";
    $params[] = $_GET['type'];
}

if (!empty($quiz_type)) {
    $sql .= " AND q.question_type = ? ";
    $params[] = $quiz_type;
}

$sql .= " ORDER BY q.question_id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Question Bank</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

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

            <!-- MAIN -->
            <div class="col-md-10 p-0">

                <nav class="navbar navbar-expand-lg bg-white shadow-sm px-4 py-3">

                    <div class="container-fluid">

                        <div>

                            <h4 class="mb-0 fw-bold">
                                Question Bank
                            </h4>

                            <small class="text-muted">
                                Reusable quiz questions
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
                        <div class="card-body">

                            <form method="GET">
                                <div class="row g-2">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Filter by Type</label>

                                        <select name="type" class="form-select" onchange="this.form.submit()">
                                            <option value="">All Types</option>
                                            <option value="multiple_choice" <?= ($_GET['type'] ?? '') == 'multiple_choice' ? 'selected' : '' ?>>
                                                Multiple Choice
                                            </option>
                                            <option value="true_false" <?= ($_GET['type'] ?? '') == 'true_false' ? 'selected' : '' ?>>
                                                True / False
                                            </option>
                                            <option value="identification" <?= ($_GET['type'] ?? '') == 'identification' ? 'selected' : '' ?>>
                                                Identification
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-md-10">

                                        <input type="text" name="search" class="form-control"
                                            placeholder="Search reusable questions..."
                                            value="<?= htmlspecialchars($search) ?>">

                                    </div>

                                    <div class="col-md-2">

                                        <button class="btn btn-primary w-100">

                                            <i class="bi bi-search"></i>
                                            Search

                                        </button>

                                    </div>

                                </div>

                            </form>

                        </div>

                    </div>

                    <!-- TABLE -->
                    <div class="card border-0 shadow-sm">

                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h5 class="mb-0">
                                        Reusable Questions
                                    </h5>
                                    <small class="text-muted">
                                        Total:
                                        <?= count($questions) ?>
                                   </small>
                                </div>
                            </div>

                            <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                                <table class="table table-hover align-middle mb-0">

                                    <thead class="table-light position-sticky top-0" style="z-index: 2;">
                                        <tr>
                                            <th>#</th>
                                            <th>Question</th>
                                            <th>Type</th>
                                            <th>Correct Answer</th>
                                            <th>Date Added</th>
                                            <th width="120">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php if (count($questions) > 0): ?>
                                            <?php foreach ($questions as $index => $question): ?>

                                                <?php
                                                $type = ucfirst(
                                                    str_replace(
                                                        '_',
                                                        ' ',
                                                        $question['question_type']
                                                    )
                                                );
                                                ?>

                                                <tr>
                                                    <td>
                                                        <?= $index + 1 ?>
                                                    </td>
                                                    <td style="max-width:350px;">
                                                        <?= htmlspecialchars($question['question']) ?>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-primary">
                                                            <?= $type ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-success">
                                                            <?= htmlspecialchars($question['correct_answer']) ?>
                                                        </span>
                                                    </td>

                                                    <td>
                                                        <?= date(
                                                            "M d, Y",
                                                            strtotime($question['created_at'])
                                                        ) ?>
                                                    </td>

                                                    <td>
                                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                            data-bs-target="#addModal<?= $question['question_id'] ?>">

                                                            <i class="bi bi-plus-circle"></i>
                                                            Add
                                                        </button>
                                                    </td>
                                                </tr>


                                                <div class="modal fade" id="addModal<?= $question['question_id'] ?>"
                                                    tabindex="-1">

                                                    <div class="modal-dialog">

                                                        <div class="modal-content">

                                                            <form method="POST" action="../../backend/add_to_quiz.php">

                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">
                                                                        Add Question to Quiz
                                                                    </h5>

                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"></button>
                                                                </div>

                                                                <div class="modal-body">

                                                                    <input type="hidden" name="question_id"
                                                                        value="<?= $question['question_id'] ?>">

                                                                    <label class="form-label">Select Quiz</label>

                                                                    <select name="quiz_id" class="form-select" required>

                                                                        <?php foreach ($quizzes as $quiz): ?>
                                                                            <option value="<?= $quiz['quiz_id'] ?>">
                                                                                <?= htmlspecialchars($quiz['title']) ?>
                                                                            </option>
                                                                        <?php endforeach; ?>

                                                                    </select>

                                                                </div>

                                                                <div class="modal-footer">

                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">
                                                                        Cancel
                                                                    </button>

                                                                    <button class="btn btn-primary">
                                                                        Add to Quiz
                                                                    </button>

                                                                </div>

                                                            </form>

                                                        </div>

                                                    </div>

                                                </div>

                                            <?php endforeach; ?>

                                        <?php else: ?>

                                            <tr>

                                                <td colspan="7" class="text-center py-5">

                                                    <i class="bi bi-database-x fs-1 d-block mb-2"></i>

                                                    No reusable questions found.

                                                </td>

                                            </tr>

                                        <?php endif; ?>

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