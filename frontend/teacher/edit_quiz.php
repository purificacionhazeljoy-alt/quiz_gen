<?php
session_start();

require_once "../../backend/database.php";

$quiz_id = $_GET['quiz_id'] ?? null;

if (!$quiz_id) {
    die("Invalid Quiz ID");
}

$stmt = $pdo->prepare("
    SELECT * FROM quizzes
    WHERE quiz_id = ?
");

$stmt->execute([$quiz_id]);

$quiz = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$quiz) {
    die("Quiz not found");
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    header("Location: ../../index.php");
    exit();
}

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Create Quiz</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-light">

    <div class="container-fluid">

        <div class="row">

            <!-- SIDEBAR -->
            <div class="col-md-2 p-0">
                <?php include "nav.php"; ?>
            </div>

            <!-- MAIN CONTENT -->
            <div class="col-md-10 p-4">

                <div class="card shadow p-4">

                    <h3>Create Quiz</h3>

                    <form method="POST" action="../../backend/quiz_crud.php">

                        <input type="hidden" name="update_quiz" value="1">
                        <input type="hidden" name="quiz_id" value="<?= $quiz_id ?>">

                        <input type="text" name="title" value="<?= $quiz['title'] ?>">

                        <input type="number" name="timer" value="<?= $quiz['timer'] ?>">

                        <select name="status">
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                        </select>

                        <button type="submit">Update</button>

                    </form>

                </div>

            </div>

        </div>

    </div>

    <script>

        function addQuestion() {

            let container = document.getElementById("questions");
            let box = document.querySelector(".question-box");

            let clone = box.cloneNode(true);

            clone.querySelectorAll("input").forEach(i => i.value = "");
            clone.querySelector("select").value = "";

            container.appendChild(clone);
        }

        function removeQuestion(btn) {

            let box = btn.closest(".question-box");

            let all = document.querySelectorAll(".question-box");

            if (all.length > 1) {
                box.remove();
            } else {
                alert("At least one question is required.");
            }
        }

    </script>

</body>

</html>