<?php
session_start();
require_once "../../backend/database.php";

$quiz_id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM questions WHERE quiz_id = ?");
$stmt->execute([$quiz_id]);
$questions = $stmt->fetchAll();
?>

<h2>Quiz Questions</h2>

<?php foreach ($questions as $q): ?>

<div class="card p-3 mb-2">

    <h5><?= htmlspecialchars($q['question']) ?></h5>

    <?php if ($q['question_type'] == 'multiple_choice'): ?>

        <p>A. <?= $q['option_a'] ?></p>
        <p>B. <?= $q['option_b'] ?></p>
        <p>C. <?= $q['option_c'] ?></p>
        <p>D. <?= $q['option_d'] ?></p>

    <?php elseif ($q['question_type'] == 'true_false'): ?>

        <p>True / False Question</p>

    <?php else: ?>

        <p>Identification</p>

    <?php endif; ?>

</div>

<?php endforeach; ?>