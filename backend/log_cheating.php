<?php
session_start();
require_once '../../config/db.php'; // adjust mo path mo

$data = json_decode(file_get_contents("php://input"), true);

$student_id = $_SESSION['student_id'];
$quiz_id = $_SESSION['quiz_id'];
$activity = $data['activity'];

$stmt = $pdo->prepare("CALL AddCheat(:student_id, :quiz_id, :activity)");
$stmt->execute([
    ':student_id' => $student_id,
    ':quiz_id' => $quiz_id,
    ':activity' => $activity
]);

echo json_encode(["status" => "logged"]);
?>
