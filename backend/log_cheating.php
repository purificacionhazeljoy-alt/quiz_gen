<?php
session_start();

require_once "database.php";
require_once "activity_log.php";

header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$student_id = $_SESSION['user_id'];
$quiz_id = $data['quiz_id'] ?? null;
$activity = $data['activity'] ?? 'unknown';

if (!$quiz_id) {

    echo json_encode([
        "status" => "error"
    ]);

    exit;
}

/* =========================
   SAVE CHEATING LOG
========================= */

$stmt = $pdo->prepare("
    INSERT INTO cheating_logs
    (
        student_id,
        quiz_id,
        activity
    )
    VALUES (?, ?, ?)
");

$stmt->execute([
    $student_id,
    $quiz_id,
    $activity
]);

/* =========================
   SAVE ACTIVITY LOG
========================= */

logActivity(
    $pdo,
    $student_id,
    "Cheating detected: " . $activity,
    "security"
);

echo json_encode([
    "status" => "logged"
]);