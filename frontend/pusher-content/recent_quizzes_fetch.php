<?php
require_once "../../backend/database.php";

$stmt = $pdo->query("
    SELECT *
    FROM quizzes
    ORDER BY created_at DESC
    LIMIT 10
");

$recentQuizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($recentQuizzes as $quiz):
?>

<tr>
    <td><?= htmlspecialchars($quiz['title']) ?></td>

    <td><?= ucfirst($quiz['difficulty']) ?></td>
</tr>

<?php endforeach; ?>