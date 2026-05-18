<?php

function logActivity($pdo, $user_id, $activity, $type = 'quiz')
{
    try {

        $stmt = $pdo->prepare("
            CALL AddLog(
                :user_id,
                :activity,
                :type
            )
        ");

        $stmt->execute([
            ':user_id'  => $user_id,
            ':activity' => $activity,
            ':type'     => $type
        ]);

        // Important for MySQL stored procedures
        while ($stmt->nextRowset()) {;}

    } catch (PDOException $e) {

        error_log("Activity Log Error: " . $e->getMessage());

    }
}

?>
