<?php
session_start();

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

                        <input type="hidden" name="create_quiz" value="1">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Quiz Title
                            </label>

                            <input type="text" name="title" class="form-control" placeholder="Enter quiz title"
                                required>

                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Quiz Type
                            </label>

                            <select name="quiz_type" class="form-select" required>

                                <option value="multiple_choice">
                                    Multiple Choice
                                </option>

                                <option value="true_false">
                                    True or False
                                </option>

                                <option value="identification">
                                    Identification
                                </option>
                            </select>
                        </div>

                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Difficulty
                            </label>

                            <select name="difficulty" class="form-select" required>

                                <option value="easy">
                                    Easy
                                </option>

                                <option value="medium">
                                    Medium
                                </option>

                                <option value="hard">
                                    Hard
                                </option>

                            </select>

                        </div>

                        <button type="submit" class="btn btn-primary">

                            <i class="bi bi-plus-circle"></i>
                            Create Quiz

                        </button>

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