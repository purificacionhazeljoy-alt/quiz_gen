<?php
session_start();

session_regenerate_id(true);

$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {

    switch ($_SESSION['role']) {

        case 'admin':
            header("Location: frontend/admin/dashboard.php");
            break;

        case 'teacher':
            header("Location: frontend/teacher/dashboard.php");
            break;

        case 'student':
            header("Location: frontend/student/dashboard.php");
            break;
    }

    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>QuizLab Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        body {
            background: linear-gradient(to right, #2A102F, #EC4899);
            min-height: 100vh;
        }

        .login-card {
            width: 400px;
            border-radius: 18px;
        }

        .form-control {
            height: 45px;
        }

        .btn-primary {
            height: 45px;
            font-weight: bold;
        }
    </style>

</head>

<body>

    <div class="container d-flex justify-content-center align-items-center min-vh-100">

        <div class="card login-card shadow-lg p-4 border-0">

            <!-- LOGO -->
            <div class="mb-3 text-center">

                <h2 class="fw-bold" style="letter-spacing:1px;">
                    Quiz<span style="color:#EC4899;">Lab</span>
                </h2>

                <p class="text-muted">
                    Quiz Generator System
                </p>

            </div>

            <!-- SUCCESS ALERT -->
            <?php if (isset($_GET['success'])): ?>

                <div class="alert alert-success alert-dismissible fade show">

                    Registration successful.
                    You can now login.

                    <button type="button" class="btn-close" data-bs-dismiss="alert">
                    </button>

                </div>

            <?php endif; ?>

            <!-- ERROR ALERT -->
            <?php if (isset($_GET['error'])): ?>

                <div class="alert alert-danger alert-dismissible fade show">

                    <?php

                    switch ($_GET['error']) {

                        case 'wrong_password':
                            echo "Wrong password.";
                            break;

                        case 'user_not_found':
                            echo "User not found.";
                            break;

                        case 'inactive_account':
                            echo "Your account is inactive.";
                            break;

                        case 'invalid_csrf':
                            echo "Invalid CSRF token.";
                            break;

                        default:
                            echo "Login failed.";
                    }
                    ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert">
                    </button>

                </div>

            <?php endif; ?>

            <!-- LOGIN FORM -->
            <form action="backend/loginAuth.php" method="POST">

                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">

                <!-- EMAIL -->
                <div class="mb-3">

                    <label class="form-label">
                        Email Address
                    </label>

                    <div class="input-group">

                        <span class="input-group-text">
                            <i class="bi bi-envelope"></i>
                        </span>

                        <input type="email" name="email" class="form-control" placeholder="Enter your email"
                            value="<?= isset($_GET['email']) ? htmlspecialchars($_GET['email']) : ''; ?>" required>

                    </div>

                </div>

                <!-- PASSWORD -->
                <div class="mb-4">

                    <label class="form-label">
                        Password
                    </label>

                    <div class="input-group">

                        <span class="input-group-text">
                            <i class="bi bi-lock"></i>
                        </span>

                        <input type="password" name="password" class="form-control" placeholder="Enter your password"
                            required>

                    </div>

                </div>

                <!-- BUTTON -->
                <button type="submit" class="btn btn-primary w-100">

                    Login

                </button>

                <!-- REGISTER -->
                <div class="text-center mt-3">

                    Don't have an account?

                    <a href="register.php" class="text-decoration-none">

                        Register

                    </a>

                </div>

            </form>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>