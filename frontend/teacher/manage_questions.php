<?php
session_start();
require_once "../../backend/database.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    header("Location: ../../index.php");
    exit();
}

$quiz_id = $_GET['quiz_id'] ?? null;
$quiz_type_filter = $_GET['quiz_type'] ?? '';

if (!$quiz_id) {
    die("Invalid Quiz ID");
}

/* FETCH QUIZ */
$stmt = $pdo->prepare("
    SELECT * FROM quizzes
    WHERE quiz_id = ?
");
$stmt->execute([$quiz_id]);

$quiz = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$quiz) {
    die("Quiz not found");
}

$sqlBank = "
    SELECT *
    FROM questions
    WHERE quiz_id != ?
";

$params = [$quiz_id];

if (!empty($quiz_type_filter)) {
    $sqlBank .= " AND question_type = ?";
    $params[] = $quiz_type_filter;
}

$sqlBank .= " ORDER BY question_id DESC";

$bankStmt = $pdo->prepare($sqlBank);
$bankStmt->execute($params);

$bankQuestions = $bankStmt->fetchAll(PDO::FETCH_ASSOC);

/* FETCH QUESTIONS */
$qStmt = $pdo->prepare("
    SELECT * FROM questions
    WHERE quiz_id = ?
    ORDER BY question_id DESC
");

$qStmt->execute([$quiz_id]);
$questions = $qStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Manage Questions</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

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

            <div class="col-md-10 p-4">

                <div class="card shadow-sm border-0 mb-4">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <h3 class="mb-1">
                                    Manage Questions
                                </h3>

                                <p class="text-muted mb-0">
                                    <?= htmlspecialchars($quiz['title']) ?>
                                </p>

                            </div>

                            <a href="my_quizzes.php" class="btn btn-outline-dark">
                                <i class="bi bi-arrow-left"></i>
                                Back
                            </a>

                        </div>

                    </div>

                </div>

                <!-- ADD QUESTION -->
                <div class="card shadow-sm border-0 mb-4">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center mb-3">

                            <h5 class="mb-0">
                                Add New Question
                            </h5>

                            <button type="button" class="btn btn-dark" data-bs-toggle="modal"
                                data-bs-target="#questionBankModal">
                                <i class="bi bi-database"></i>
                                Add From Question Bank
                            </button>


                        </div>

                        <form method="POST" action="../../backend/question_crud.php">

                            <input type="hidden" name="quiz_id" value="<?= $quiz_id ?>">

                            <div class="mb-3">

                                <label class="form-label">
                                    Question Type
                                </label>

                                <input type="hidden" name="question_type" value="<?= $quiz['quiz_type'] ?>">

                                <input type="text" class="form-control"
                                    value="<?= ucfirst(str_replace('_', ' ', $quiz['quiz_type'])) ?>" readonly>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    Question
                                </label>

                                <textarea name="question" class="form-control" rows="3" required></textarea>

                            </div>

                            <?php if ($quiz['quiz_type'] == 'multiple_choice'): ?>

                                <div class="row">

                                    <div class="col-md-6 mb-3">

                                        <label class="form-label">Option A</label>

                                        <input type="text" name="option_a" class="form-control">

                                    </div>

                                    <div class="col-md-6 mb-3">

                                        <label class="form-label">Option B</label>

                                        <input type="text" name="option_b" class="form-control">

                                    </div>

                                    <div class="col-md-6 mb-3">

                                        <label class="form-label">Option C</label>

                                        <input type="text" name="option_c" class="form-control">

                                    </div>

                                    <div class="col-md-6 mb-3">

                                        <label class="form-label">Option D</label>

                                        <input type="text" name="option_d" class="form-control">

                                    </div>

                                </div>

                            <?php elseif ($quiz['quiz_type'] == 'true_false'): ?>

                                <div class="alert alert-info">

                                    Choices: <b>True</b> or <b>False</b>

                                </div>

                            <?php endif; ?>

                            <div class="mb-3">

                                <label class="form-label">
                                    Correct Answer
                                </label>

                                <?php if ($quiz['quiz_type'] == 'true_false'): ?>

                                    <select name="correct_answer" class="form-select" required>

                                        <option value="True">True</option>
                                        <option value="False">False</option>

                                    </select>

                                <?php else: ?>

                                    <input type="text" name="correct_answer" class="form-control"
                                        placeholder="Enter correct answer" required>

                                <?php endif; ?>

                            </div>

                            <button class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i>
                                Add Question
                            </button>

                        </form>

                    </div>

                </div>

                <!-- QUESTION LIST -->
                <div class="card shadow-sm border-0">

                    <div class="card-body">

                        <h5 class="mb-3">
                            Question List
                        </h5>

                        <?php if (count($questions) > 0): ?>

                            <?php foreach ($questions as $index => $q): ?>

                                <div class="card mb-3 shadow-sm border-0">

                                    <div class="card-body">

                                        <div class="d-flex justify-content-between">

                                            <div>
                                                <h6 class="mb-1">
                                                    <?= htmlspecialchars($q['question']) ?>
                                                </h6>

                                                <small class="text-muted">
                                                    Correct: <?= htmlspecialchars($q['correct_answer']) ?>
                                                </small>
                                            </div>

                                            <div class="d-flex gap-2">

                                                <!-- EDIT BUTTON -->
                                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                                    data-bs-target="#editModal<?= $q['question_id'] ?>">
                                                    Edit
                                                </button>

                                                <!-- DELETE -->
                                                <form method="POST" action="../../backend/question_crud.php">
                                                    <input type="hidden" name="question_id" value="<?= $q['question_id'] ?>">
                                                    <input type="hidden" name="quiz_id" value="<?= $quiz_id ?>">
                                                    <button name="delete_question" value="1" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Delete this question?')">
                                                        Delete
                                                    </button>
                                                </form>

                                            </div>

                                        </div>

                                    </div>

                                </div>


                                <!-- EDIT MODAL -->
                                <div class="modal fade" id="editModal<?= $q['question_id'] ?>" tabindex="-1">

                                    <div class="modal-dialog modal-lg">

                                        <div class="modal-content">

                                            <form method="POST" action="../../backend/question_crud.php">

                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Question</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>

                                                <div class="modal-body">

                                                    <input type="hidden" name="question_id" value="<?= $q['question_id'] ?>">
                                                    <input type="hidden" name="quiz_id" value="<?= $quiz_id ?>">
                                                    <input type="hidden" name="question_type"
                                                        value="<?= $q['question_type'] ?>">

                                                    <label>Question</label>
                                                    <textarea name="question" class="form-control mb-2"
                                                        required><?= htmlspecialchars($q['question']) ?></textarea>

                                                    <?php if ($q['question_type'] == 'multiple_choice'): ?>
                                                        <div class="row">

                                                            <div class="col-md-6">
                                                                <input name="option_a" class="form-control mb-2"
                                                                    value="<?= htmlspecialchars($q['option_a']) ?>">
                                                            </div>

                                                            <div class="col-md-6">
                                                                <input name="option_b" class="form-control mb-2"
                                                                    value="<?= htmlspecialchars($q['option_b']) ?>">
                                                            </div>

                                                            <div class="col-md-6">
                                                                <input name="option_c" class="form-control mb-2"
                                                                    value="<?= htmlspecialchars($q['option_c']) ?>">
                                                            </div>

                                                            <div class="col-md-6">
                                                                <input name="option_d" class="form-control mb-2"
                                                                    value="<?= htmlspecialchars($q['option_d']) ?>">
                                                            </div>

                                                        </div>
                                                    <?php endif; ?>

                                                    <label>Correct Answer</label>
                                                    <input name="correct_answer" class="form-control"
                                                        value="<?= htmlspecialchars($q['correct_answer']) ?>">

                                                </div>

                                                <div class="modal-footer">

                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                        Cancel
                                                    </button>

                                                    <button type="submit" name="update_question" value="1"
                                                        class="btn btn-primary">
                                                        Save Changes
                                                    </button>

                                                </div>

                                            </form>

                                        </div>

                                    </div>

                                </div>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <div class="alert alert-secondary">
                                No questions found.
                            </div>

                        <?php endif; ?>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <?php
    $bankStmt = $pdo->prepare("
    SELECT *
    FROM questions
    WHERE quiz_id != ?
    ORDER BY question_id DESC
");

    $bankStmt->execute([$quiz_id]);

    $bankQuestions = $bankStmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <!-- QUESTION BANK MODAL -->
    <div class="modal fade" id="questionBankModal" tabindex="-1">

        <div class="modal-dialog modal-xl modal-dialog-scrollable">

            <div class="modal-content border-0">

                <form method="POST" action="../../backend/import_questions.php">

                    <div class="modal-header">

                        <h5 class="modal-title fw-bold">

                            Question Bank

                        </h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>


                    </div>




                    <div class="modal-body">

                        <select id="typeFilter" class="form-select mb-3" onchange="filterBank(this.value)">
                            <option value="">All</option>
                            <option value="multiple_choice">Multiple Choice</option>
                            <option value="true_false">True / False</option>
                            <option value="identification">Identification</option>
                        </select>

                        <input type="hidden" name="quiz_id" value="<?= $quiz_id ?>">
                        <input type="hidden" id="quizTypeInput" value="<?= $quiz['quiz_type'] ?>">

                        <?php if (count($bankQuestions) > 0): ?>

                            <div class="table-responsive">

                                <table class="table table-hover align-middle">

                                    <thead class="table-light">

                                        <tr>

                                            <th width="50"></th>
                                            <th>Question</th>
                                            <th>Type</th>
                                            <th>Correct Answer</th>

                                        </tr>

                                    </thead>

                                    <tbody id="bankTable">

                                        <?php foreach ($bankQuestions as $bq): ?>

                                            <tr data-type="<?= $bq['question_type'] ?>">

                                                <td>

                                                    <input type="checkbox" name="questions[]" value="<?= $bq['question_id'] ?>">

                                                </td>

                                                <td>

                                                    <?= htmlspecialchars($bq['question']) ?>

                                                </td>

                                                <td>

                                                    <span class="badge bg-primary">

                                                        <?= ucfirst(str_replace('_', ' ', $bq['question_type'])) ?>

                                                    </span>

                                                </td>

                                                <td>

                                                    <span class="badge bg-success">

                                                        <?= htmlspecialchars($bq['correct_answer']) ?>

                                                    </span>

                                                </td>

                                            </tr>

                                        <?php endforeach; ?>

                                    </tbody>

                                </table>

                            </div>

                        <?php else: ?>

                            <div class="alert alert-secondary mb-0">

                                No reusable questions found.

                            </div>

                        <?php endif; ?>

                    </div>

                    <div class="modal-footer">

                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">

                            Cancel

                        </button>

                        <button class="btn btn-primary">

                            <i class="bi bi-download"></i>
                            Import Selected

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

    <script>
        function filterBank(type) {
            let rows = document.querySelectorAll("#bankTable tr");

            rows.forEach(row => {
                if (!type || row.dataset.type === type) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        }
    </script>
</body>

</html>