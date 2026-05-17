<?php
require_once "../../backend/database.php";

$stmt = $pdo->query("
    SELECT 
        qa.*,
        q.title,
        u.firstname,
        u.lastname
    FROM quiz_attempts qa
    JOIN quizzes q
        ON qa.quiz_id = q.quiz_id
    JOIN users u
        ON qa.student_id = u.user_id
    ORDER BY qa.attempt_id DESC
    LIMIT 10
");

$recentAttempts = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($recentAttempts as $attempt):
?>

<tr>

    <td>
        <?= htmlspecialchars($attempt['firstname']) ?>
        <?= htmlspecialchars($attempt['lastname']) ?>
    </td>

    <td>
        <?= htmlspecialchars($attempt['title']) ?>
    </td>

    <td>
        <?= $attempt['score'] ?>/<?= $attempt['total_questions'] ?>
    </td>

    <td>

        <?php if ($attempt['status'] == 'submitted'): ?>

            <span class="badge bg-success">
                Submitted
            </span>

        <?php else: ?>

            <span class="badge bg-warning text-dark">
                Ongoing
            </span>

        <?php endif; ?>

    </td>

</tr>

<?php endforeach; ?>