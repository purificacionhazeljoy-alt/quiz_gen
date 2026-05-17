<?php
require_once "../../backend/database.php";

$selectedQuiz = $_GET['filter_quiz'] ?? 'all';

$sql = "
    SELECT 
        aa.*,
        q.question,
        qa.quiz_id,
        qu.title,
        u.firstname,
        u.lastname
    FROM attempt_answers aa
    JOIN questions q 
        ON aa.question_id = q.question_id
    JOIN quiz_attempts qa 
        ON aa.attempt_id = qa.attempt_id
    JOIN quizzes qu 
        ON qa.quiz_id = qu.quiz_id
    JOIN users u 
        ON qa.student_id = u.user_id
";

if ($selectedQuiz !== 'all') {
    $sql .= " WHERE qa.quiz_id = ?";
}

$sql .= " ORDER BY aa.answer_id DESC LIMIT 30";

$stmt = $pdo->prepare($sql);

if ($selectedQuiz !== 'all') {
    $stmt->execute([$selectedQuiz]);
} else {
    $stmt->execute();
}

$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($logs as $index => $log):
?>

<tr>

    <td><?= $index + 1 ?></td>

    <td>
        <?= htmlspecialchars($log['firstname']) ?>
        <?= htmlspecialchars($log['lastname']) ?>
    </td>

    <td>
        <?= htmlspecialchars($log['title']) ?>
    </td>

    <td style="max-width:300px;">
        <?= htmlspecialchars($log['question']) ?>
    </td>

    <td>
        <?= htmlspecialchars($log['selected_answer']) ?>
    </td>

    <td>

        <?php if ($log['is_correct']): ?>

            <span class="badge bg-success">
                Correct
            </span>

        <?php else: ?>

            <span class="badge bg-danger">
                Wrong
            </span>

        <?php endif; ?>

    </td>

</tr>

<?php endforeach; ?>