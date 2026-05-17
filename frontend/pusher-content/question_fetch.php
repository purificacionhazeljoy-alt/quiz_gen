<?php
require_once "../../backend/database.php";

$quiz_id = $_GET['quiz_id'];

$stmt = $pdo->prepare("SELECT * FROM questions WHERE quiz_id = ?");
$stmt->execute([$quiz_id]);
$questions = $stmt->fetchAll();

foreach ($questions as $q) {
    echo "<div class='border p-2 mb-2'>";
    echo "<b>{$q['question']}</b>";
    echo "</div>";
}
?>