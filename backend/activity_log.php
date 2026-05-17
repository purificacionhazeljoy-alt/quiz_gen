<?php

function logActivity($pdo, $user_id, $activity, $type = 'quiz')
{
    $stmt = $pdo->prepare("
        INSERT INTO activity_logs
        (
            user_id,
            activity,
            log_type
        )
        VALUES (?, ?, ?)
    ");

    $stmt->execute([
        $user_id,
        $activity,
        $type
    ]);
}
?>