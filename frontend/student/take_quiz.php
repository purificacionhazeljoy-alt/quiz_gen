<?php
session_start();
require_once "../../backend/database.php";
require_once "../../backend/pusher.php";
require_once "../../backend/activity_log.php";


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../../index.php");
    exit();
}

$quiz_id = $_GET['id'] ?? null;

if (!$quiz_id) {
    die("Invalid quiz ID");
}

/* FETCH QUIZ */
$stmt = $pdo->prepare("SELECT * FROM quizzes WHERE quiz_id = ?");
$stmt->execute([$quiz_id]);
$quiz = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$quiz) {
    die("Quiz not found");
}

/* FETCH QUESTIONS */
$stmt = $pdo->prepare("SELECT * FROM questions WHERE quiz_id = ?");
$stmt->execute([$quiz_id]);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$questions || count($questions) === 0) {
    die("No questions found");
}

if (!isset($_SESSION['attempt_id'][$quiz_id])) {

    logActivity(
        $pdo,
        $_SESSION['user_id'],
        "Started quiz: " . $quiz['title'],
        "quiz"
    );

    $stmt = $pdo->prepare("
        INSERT INTO quiz_attempts (student_id, quiz_id, status)
        VALUES (?, ?, 'ongoing')
    ");

    $stmt->execute([$_SESSION['user_id'], $quiz_id]);
    $_SESSION['attempt_id'][$quiz_id] = $pdo->lastInsertId();
}

$attempt_id = $_SESSION['attempt_id'][$quiz_id];


if (!isset($_SESSION['quiz_progress'][$quiz_id])) {
    $_SESSION['quiz_progress'][$quiz_id] = [
        'index' => 0,
        'score' => 0
    ];
}

$progress = &$_SESSION['quiz_progress'][$quiz_id];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $selected = $_POST['answer'] ?? '';
    $question_id = $_POST['question_id'] ?? null;

    $stmt = $pdo->prepare("
        SELECT *
        FROM questions
        WHERE question_id = ?
    ");
    $stmt->execute([$question_id]);
    $q = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$q) {
        exit("Question not found");
    }

    // check duplicate
    $checkStmt = $pdo->prepare("
        SELECT answer_id
        FROM attempt_answers
        WHERE attempt_id = ?
        AND question_id = ?
    ");
    $checkStmt->execute([$attempt_id, $question_id]);
    $exists = $checkStmt->fetch();

    // DEFAULT: wrong agad
    $isCorrect = 0;

    // treat empty answer as "No Answer"
    if ($selected === '' || $selected === null) {
        $selected = 'No Answer';
    } else {

        if ($q['question_type'] === 'identification') {
            $isCorrect =
                strtolower(trim($selected)) === strtolower(trim($q['correct_answer']));
        } else {
            $isCorrect = ($selected === $q['correct_answer']);
        }
    }

    // SAVE ANSWER (kahit empty or timeout)
    if (!$exists) {

        $stmt = $pdo->prepare("
            INSERT INTO attempt_answers
            (
                attempt_id,
                question_id,
                selected_answer,
                is_correct
            )
            VALUES (?, ?, ?, ?)
        ");

        $stmt->execute([
            $attempt_id,
            $question_id,
            $selected,
            $isCorrect
        ]);
    }

    // update score ONLY if correct
    if ($isCorrect) {
        $progress['score']++;
    }

    $progress['index']++;

    $pusher->trigger('quiz-channel', 'submission-event', [
        'student_id' => $_SESSION['user_id'],
        'quiz_id' => $quiz_id,
        'question' => $q['question'],
        'answer' => $selected,
        'correct' => $isCorrect
    ]);

    header("Location: take_quiz.php?id=$quiz_id");
    exit();
}


$currentIndex = $progress['index'] ?? 0;

if (!isset($questions[$currentIndex])) {

    $stmt = $pdo->prepare("
        UPDATE quiz_attempts 
        SET score = ?, total_questions = ?, status = 'submitted'
        WHERE attempt_id = ?
    ");
    $stmt->execute([
        $progress['score'],
        count($questions),
        $attempt_id
    ]);

    unset($_SESSION['quiz_progress'][$quiz_id]);
    unset($_SESSION['attempt_id'][$quiz_id]);

    logActivity(
        $pdo,
        $_SESSION['user_id'],
        "Finished quiz: " . $quiz['title'],
        "quiz"
    );
    header("Location: result.php?quiz_id=$quiz_id");
    exit();
}

$current = $questions[$currentIndex];

/* TIMER PER QUESTIONS */
$timer = 20;
if ($quiz['difficulty'] == 'easy')
    $timer = 15;
if ($quiz['difficulty'] == 'medium')
    $timer = 20;
if ($quiz['difficulty'] == 'hard')
    $timer = 25;

?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Take Quiz</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="style.css">

    <style>
        body {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            min-height: 100vh;
            font-family: 'Segoe UI', sans-serif;
        }

        .quiz-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .quiz-card {
            width: 100%;
            max-width: 750px;
            background: #fff;
            border-radius: 25px;
            padding: 35px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .quiz-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            gap: 15px;
        }

        .quiz-title {
            font-weight: 700;
            margin: 0;
            color: #222;
        }

        .question-count {
            margin: 0;
            color: #777;
        }

        .timer-box {
            background: #111827;
            color: #fff;
            padding: 12px 18px;
            border-radius: 15px;
            font-size: 20px;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .question-box {
            margin-top: 25px;
        }

        .question-text {
            font-weight: 600;
            margin-bottom: 25px;
            line-height: 1.5;
            color: #222;
        }

        .answers {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .answer-option {
            border: 2px solid #e5e7eb;
            padding: 18px;
            border-radius: 15px;
            cursor: pointer;
            transition: 0.3s ease;
            display: flex;
            align-items: center;
            gap: 15px;
            font-size: 18px;
            font-weight: 500;
        }

        .answer-option:hover {
            border-color: #4f46e5;
            background: #eef2ff;
            transform: translateY(-2px);
        }

        .answer-option input {
            transform: scale(1.3);
        }

        .answer-option:has(input:checked) {
            border-color: #4f46e5;
            background: #eef2ff;
        }

        .answer-input {
            height: 60px;
            border-radius: 15px;
            font-size: 18px;
            padding-left: 20px;
        }

        .next-btn {
            margin-top: 30px;
            width: 100%;
            height: 55px;
            border: none;
            border-radius: 15px;
            font-size: 18px;
            font-weight: 600;
            background: #4f46e5;
            transition: 0.3s ease;
        }

        .next-btn:hover {
            background: #4338ca;
            transform: translateY(-2px);
        }

        .progress {
            border-radius: 20px;
            overflow: hidden;
        }

        @media(max-width:768px) {

            .quiz-card {
                padding: 25px;
            }

            .quiz-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .timer-box {
                width: 100%;
                justify-content: center;
            }

            .question-text {
                font-size: 20px;
            }

            .answer-option {
                font-size: 16px;
                padding: 15px;
            }
        }
    </style>

</head>

<body>

    <div class="quiz-wrapper">

        <div class="quiz-card">

            <!-- TOP BAR -->
            <div class="quiz-header">

                <div>
                    <h2 class="quiz-title">
                        <?= htmlspecialchars($quiz['title']); ?>
                    </h2>

                    <p class="question-count">
                        Question <?= $currentIndex + 1 ?> of <?= count($questions) ?>
                    </p>
                </div>

                <div class="timer-box">
                    <i class="bi bi-clock-fill"></i>
                    <span id="time"><?= $timer ?></span>s
                </div>

            </div>

            <!-- PROGRESS -->
            <div class="progress mb-4" style="height:10px;">
                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                    style="width: <?= (($currentIndex + 1) / count($questions)) * 100 ?>%">
                </div>
            </div>

            <!-- QUESTION -->
            <div class="question-box">

                <h4 class="question-text">
                    <?= htmlspecialchars($current['question']); ?>
                </h4>

                <form method="POST" id="quizForm">

                    <input type="hidden" name="question_id" value="<?= $current['question_id'] ?>">

                    <!-- MULTIPLE CHOICE -->
                    <?php if ($current['question_type'] == 'multiple_choice'): ?>

                        <div class="answers">

                            <label class="answer-option">
                                <input type="radio" name="answer" value="A" required>
                                <span><?= $current['option_a'] ?></span>
                            </label>

                            <label class="answer-option">
                                <input type="radio" name="answer" value="B">
                                <span><?= $current['option_b'] ?></span>
                            </label>

                            <label class="answer-option">
                                <input type="radio" name="answer" value="C">
                                <span><?= $current['option_c'] ?></span>
                            </label>

                            <label class="answer-option">
                                <input type="radio" name="answer" value="D">
                                <span><?= $current['option_d'] ?></span>
                            </label>

                        </div>

                        <!-- TRUE FALSE -->
                    <?php elseif ($current['question_type'] == 'true_false'): ?>

                        <div class="answers">

                            <label class="answer-option">
                                <input type="radio" name="answer" value="True" required>
                                <span>True</span>
                            </label>

                            <label class="answer-option">
                                <input type="radio" name="answer" value="False">
                                <span>False</span>
                            </label>

                        </div>

                        <!-- IDENTIFICATION -->
                    <?php else: ?>

                        <input type="text" name="answer" class="form-control answer-input" placeholder="Type your answer..."
                            required>

                    <?php endif; ?>

                    <button class="btn btn-primary next-btn">
                        Next Question
                        <i class="bi bi-arrow-right"></i>
                    </button>

                </form>

            </div>

        </div>

    </div>




    <script>

        const quizId = <?= $quiz_id ?>;
        let strikes = 0;
        let hiddenStart = null;
        let cheatCooldown = false;


        document.addEventListener("visibilitychange", () => {

            if (document.hidden) {

                hiddenStart = Date.now();

            } else {

                if (hiddenStart) {

                    const secondsAway =
                        (Date.now() - hiddenStart) / 1000;

                    if (secondsAway >= 3) {

                        logCheat("tab_switch");

                    }

                    hiddenStart = null;
                }
            }
        });



        document.addEventListener("copy", () => {
            logCheat("copy");
        });

        document.addEventListener("paste", () => {
            logCheat("paste");
        });

        document.addEventListener("cut", () => {
            logCheat("cut");
        });


        function logCheat(activity) {

            if (cheatCooldown) return;

            cheatCooldown = true;

            setTimeout(() => {

                cheatCooldown = false;

            }, 5000);

            strikes++;

            fetch("../../backend/log_cheating.php", {

                method: "POST",

                headers: {
                    "Content-Type": "application/json"
                },

                body: JSON.stringify({
                    quiz_id: quizId,
                    activity
                })

            });

            console.log("Cheating detected:", activity);

            // auto submit
            if (strikes >= 3) {

                Swal.fire({
                    icon: "warning",
                    title: "Cheating Detected",
                    text: "Your quiz will now be submitted."
                }).then(() => {

                    document.getElementById("quizForm").submit();

                });
            }
        }


        /* TIMER */

        let timeLeft = <?= $timer ?>;

        const timerEl = document.getElementById("time");

        const timerBox = document.querySelector(".timer-box");

        timerEl.textContent = timeLeft;

        const interval = setInterval(() => {

            timeLeft--;

            timerEl.textContent = timeLeft;

            // warning color
            if (timeLeft <= 5) {

                timerBox.style.background = "#dc2626";

                timerEl.style.color = "#fff";

            }

            // auto submit
            if (timeLeft <= 0) {

                clearInterval(interval);

                document.getElementById("quizForm").submit();

            }

        }, 1000);

    </script>

</body>

</html>