<?php

session_start();
require_once "../../backend/database.php";

$quiz_id = $_GET['id'] ?? 0;

/* FETCH QUIZ */
$stmt = $pdo->prepare("
    SELECT *
    FROM quizzes
    WHERE quiz_id = ?
");

$stmt->execute([$quiz_id]);

$quiz = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$quiz) {
    exit("Quiz not found");
}

/* FETCH QUESTIONS */
$stmt = $pdo->prepare("
    SELECT *
    FROM questions
    WHERE quiz_id = ?
");

$stmt->execute([$quiz_id]);

$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="quiz-header mb-4">

    <h3 class="fw-bold">
        <?= htmlspecialchars($quiz['title']) ?>
    </h3>

    <p class="text-muted">
        <?= htmlspecialchars($quiz['description']) ?>
    </p>

    <span class="badge bg-primary">
        <?= ucfirst($quiz['difficulty']) ?>
    </span>

</div>

<hr>

<?php foreach($questions as $index => $q): ?>

    <div class="card border-0 shadow-sm mb-3">

        <div class="card-body">

            <div class="d-flex justify-content-between mb-2">

                <h5 class="fw-bold">
                    Question <?= $index + 1 ?>
                </h5>

                <span class="badge bg-dark">
                    <?= ucfirst($q['question_type']) ?>
                </span>

            </div>

            <p class="fs-5">
                <?= htmlspecialchars($q['question']) ?>
            </p>

            <?php if($q['question_type'] == 'multiple_choice'): ?>

                <div class="border rounded p-2 mb-2">
                    A. <?= htmlspecialchars($q['option_a']) ?>
                </div>

                <div class="border rounded p-2 mb-2">
                    B. <?= htmlspecialchars($q['option_b']) ?>
                </div>

                <div class="border rounded p-2 mb-2">
                    C. <?= htmlspecialchars($q['option_c']) ?>
                </div>

                <div class="border rounded p-2">
                    D. <?= htmlspecialchars($q['option_d']) ?>
                </div>

            <?php elseif($q['question_type'] == 'true_false'): ?>

                <div class="border rounded p-2 mb-2">True</div>
                <div class="border rounded p-2">False</div>

            <?php endif; ?>

            <div class="alert alert-success mt-3 mb-0">

                <strong>Correct Answer:</strong>

                <?= htmlspecialchars($q['correct_answer']) ?>

            </div>

        </div>

    </div>

<?php endforeach; ?>