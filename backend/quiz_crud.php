<?php

session_start();

require_once "database.php";
require '../vendor/autoload.php';


/* ================= CREATE ================= */
if (isset($_POST['create_quiz'])) {

    try {

        $stmt = $pdo->prepare("
            CALL AddQuiz(
                :teacher_id,
                :title,
                :quiz_type,
                :difficulty
            )
        ");

        $stmt->execute([
            ':teacher_id' => $_SESSION['user_id'],
            ':title' => $_POST['title'],
            ':quiz_type' => $_POST['quiz_type'],
            ':difficulty' => $_POST['difficulty']
        ]);

        // Important for MySQL procedures
        while ($stmt->nextRowset()) {;}

        require_once "pusher.php";

        $pusher->trigger(
            'quiz-channel',
            'quiz-created',
            [
                'message' => 'New quiz created'
            ]
        );

        header("Location: ../frontend/teacher/my_quizzes.php?created=1");
        exit();

    } catch (PDOException $e) {

        die("Create Quiz Error: " . $e->getMessage());
    }
}


/* ================= UPDATE ================= */
if (isset($_POST['update_quiz'])) {

    try {

        $stmt = $pdo->prepare("
            UPDATE quizzes
            SET
                title = ?,
                status = ?
            WHERE quiz_id = ?
        ");

        $stmt->execute([
            $_POST['title'],
            $_POST['status'],
            $_POST['quiz_id']
        ]);

        header("Location: ../frontend/teacher/my_quizzes.php?updated=1");
        exit();

    } catch (PDOException $e) {

        die("Update Quiz Error: " . $e->getMessage());
    }
}


/* ================= DELETE ================= */
if (isset($_POST['delete_quiz'])) {

    try {

        $stmt = $pdo->prepare("
            DELETE FROM quizzes
            WHERE quiz_id = ?
        ");

        $stmt->execute([
            $_POST['quiz_id']
        ]);

        header("Location: ../frontend/teacher/my_quizzes.php?deleted=1");
        exit();

    } catch (PDOException $e) {

        die("Delete Quiz Error: " . $e->getMessage());
    }
}


/* ================= PUBLISH ================= */
if (isset($_POST['publish_quiz'])) {

    try {

        $stmt = $pdo->prepare("
            UPDATE quizzes
            SET status = 'published'
            WHERE quiz_id = ?
        ");

        $stmt->execute([
            $_POST['quiz_id']
        ]);

        header("Location: ../frontend/teacher/my_quizzes.php?published=1");
        exit();

    } catch (PDOException $e) {

        die("Publish Quiz Error: " . $e->getMessage());
    }
}

?>
