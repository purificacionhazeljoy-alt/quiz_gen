<?php

require_once 'database.php';
require_once "activity_log.php";

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // CSRF validation
    if (
        !isset($_POST['csrf_token']) ||
        !isset($_SESSION['csrf_token']) ||
        $_POST['csrf_token'] !== $_SESSION['csrf_token']
    ) {
        die("CSRF validation failed");
    }


    // Input sanitization
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Prepared statement
    $stmt = $pdo->prepare("
        SELECT 
            user_id,
            firstname,
            middlename,
            lastname,
            email,
            password,
            role,
            status
        FROM users
        WHERE email = ?
        LIMIT 1
    ");

    $stmt->execute([$email]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {

        // CHECK STATUS
        if ($user['status'] !== 'active') {

            header("Location: ../login.php?error=inactive_account");
            exit();
        }

        // Verify password
        if (password_verify($password, $user['password'])) {

            session_regenerate_id(true);

            // Full name
            $fullname = trim(
                $user['firstname'] . ' ' .
                $user['middlename'] . ' ' .
                $user['lastname']
            );

            // Session variables
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['name'] = $fullname;

            /* LOG ACTIVITY */
            logActivity(
                $pdo,
                $user['user_id'],
                "User logged in",
                "login"
            );

            // Redirect by role
            if ($user['role'] === 'admin') {

                header("Location: ../frontend/admin/dashboard.php");

            } elseif ($user['role'] === 'teacher') {

                header("Location: ../frontend/teacher/dashboard.php");

            } else {

                header("Location: ../frontend/student/dashboard.php");
            }

            exit();

        } else {

            header("Location: ../login.php?error=wrong_password");
            exit();
        }

    } else {

        header("Location: ../login.php?error=user_not_found");
        exit();
    }
}
?>