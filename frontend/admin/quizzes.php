<?php
session_start();
require_once "../../backend/database.php";

$stmt = $pdo->query("SELECT * FROM quizzes ORDER BY quiz_id DESC");
$quizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Quizzes</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="style.css">
    <style>
        .quiz-card {
            border-radius: 20px;
            transition: 0.3s ease;
            overflow: hidden;
        }

        .quiz-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
        }

        .quiz-icon {
            width: 65px;
            height: 65px;
            border-radius: 18px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
        }

        .quiz-title {
            min-height: 50px;
        }

        .form-control,
        .form-select {
            height: 50px;
            border-radius: 14px !important;
        }

        .input-group {
            border-radius: 14px;
            overflow: hidden;
        }

        .input-group-text {
            border-radius: 14px 0 0 14px !important;
        }

        .badge {
            font-size: 13px;
        }

        @media(max-width:768px) {

            .quiz-title {
                min-height: auto;
            }

        }
    </style>
</head>

<body style="background:#f4f6f9;">

    <div class="container-fluid">
        <div class="row">

            <div class="col-md-2 p-0">
                <?php include "nav.php"; ?>
            </div>

            <div class="col-md-10 p-0">

                <nav class="navbar bg-white shadow-sm px-4 py-3">
                    <div>
                        <h4 class="fw-bold mb-0">Quizzes</h4>
                        <small class="text-muted">All created quizzes</small>
                    </div>
                </nav>

                <div class="p-4">

                    <div class="row mb-4 g-3">

                        <div class="col-md-8">

                            <div class="input-group shadow-sm">

                                <span class="input-group-text bg-white border-0">
                                    <i class="bi bi-search"></i>
                                </span>

                                <input type="text" id="searchQuiz" class="form-control border-0"
                                    placeholder="Search quizzes...">

                            </div>

                        </div>

                        <div class="col-md-4">

                            <select id="difficultyFilter" class="form-select shadow-sm">

                                <option value="">All Difficulty</option>
                                <option value="easy">Easy</option>
                                <option value="medium">Medium</option>
                                <option value="hard">Hard</option>

                            </select>

                        </div>

                    </div>

                    <div class="row g-4" id="quizContainer">

                        <?php foreach ($quizzes as $quiz): ?>

                            <?php

                            $difficulty = strtolower($quiz['difficulty']);

                            $badgeClass = "bg-primary";

                            if ($difficulty == "easy")
                                $badgeClass = "bg-success";

                            if ($difficulty == "medium")
                                $badgeClass = "bg-warning text-dark";

                            if ($difficulty == "hard")
                                $badgeClass = "bg-danger";

                            ?>

                            <div class="col-lg-4 col-md-6 quiz-card-item">

                                <div class="card border-0 shadow-sm h-100 quiz-card">

                                    <div class="card-body d-flex flex-column">

                                        <div class="quiz-icon mb-3">
                                            <i class="bi bi-patch-question-fill"></i>
                                        </div>

                                        <h5 class="fw-bold mb-2 quiz-title">
                                            <?= htmlspecialchars($quiz['title']) ?>
                                        </h5>

                                        <p class="text-muted small flex-grow-1">
                                            <?= htmlspecialchars($quiz['description'] ?? 'No description available.') ?>
                                        </p>

                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <span class="badge <?= $badgeClass ?> px-3 py-2">
                                                <?= ucfirst($quiz['difficulty']) ?>
                                            </span>

                                            <button class="btn btn-dark btn-sm rounded-pill px-3 viewQuizBtn"
                                                data-id="<?= $quiz['quiz_id'] ?>" data-bs-toggle="modal"
                                                data-bs-target="#quizModal">

                                                View Quiz

                                            </button>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        <?php endforeach; ?>

                    </div>

                </div>

            </div>

        </div>
    </div>

    <!-- QUIZ MODAL -->
    <div class="modal fade" id="quizModal" tabindex="-1">

        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">

            <div class="modal-content border-0 rounded-4">

                <div class="modal-header">

                    <h5 class="modal-title fw-bold">
                        Quiz Preview
                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body" id="quizModalBody">

                    <div class="text-center py-5">

                        <div class="spinner-border text-primary"></div>

                    </div>

                </div>

            </div>

        </div>

    </div>
    <script>

        const searchQuiz =
            document.getElementById("searchQuiz");

        const difficultyFilter =
            document.getElementById("difficultyFilter");

        searchQuiz.addEventListener("keyup", filterQuiz);

        difficultyFilter.addEventListener("change", filterQuiz);

        function filterQuiz() {

            const search =
                searchQuiz.value.toLowerCase();

            const difficulty =
                difficultyFilter.value.toLowerCase();

            const cards =
                document.querySelectorAll(".quiz-card-item");

            cards.forEach(card => {

                const text =
                    card.innerText.toLowerCase();

                const matchesSearch =
                    text.includes(search);

                const matchesDifficulty =
                    difficulty === "" ||
                    text.includes(difficulty);

                card.style.display =
                    matchesSearch && matchesDifficulty
                        ? ""
                        : "none";

            });

        }

        /* VIEW QUIZ MODAL */

        const viewButtons =
            document.querySelectorAll(".viewQuizBtn");

        const modalBody =
            document.getElementById("quizModalBody");

        viewButtons.forEach(button => {

            button.addEventListener("click", () => {

                const quizId =
                    button.dataset.id;

                modalBody.innerHTML = `
                <div class="text-center py-5">
                    <div class="spinner-border text-primary"></div>
                </div>
            `;

                fetch(`fetch_quiz.php?id=${quizId}`)
                    .then(response => response.text())
                    .then(data => {

                        modalBody.innerHTML = data;

                    });

            });

        });

    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>