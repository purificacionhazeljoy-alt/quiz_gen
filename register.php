<?php

session_start();

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>QuizLab Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>

        body {
            background: linear-gradient(to right, #2A102F, #EC4899);
            min-height: 100vh;
        }

        .register-card {
            width: 500px;
            border-radius: 18px;
        }

        .logo {
            width: 110px;
            height: 110px;
            object-fit: cover;
            border-radius: 50%;
            background: #fff;
            padding: 8px;
            border: 2px solid #EC4899;
        }

    </style>

</head>

<body>

<div class="container d-flex justify-content-center align-items-center py-5">

    <div class="card register-card shadow-lg p-4">

        <div class="text-center mb-4">

            <img src="images/logo.png" class="logo mb-3">

            <h2 class="fw-bold">Create Account</h2>

            <p class="text-muted">
                Register to QuizLab
            </p>

        </div>

        <?php if (isset($_GET['success'])): ?>

            <div class="alert alert-success alert-dismissible fade show">

                Registration successful. You can now login.

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>

            </div>

        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>

            <div class="alert alert-danger alert-dismissible fade show">

                <?php

                    if ($_GET['error'] == 'email_exists') {

                        echo "Email already exists.";

                    } elseif ($_GET['error'] == 'password_mismatch') {

                        echo "Passwords do not match.";

                    } elseif ($_GET['error'] == 'invalid_csrf') {

                        echo "Invalid CSRF token.";

                    } else {

                        echo "Registration failed.";
                    }
                ?>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>

            </div>

        <?php endif; ?>

        <form action="backend/registerAuth.php" method="POST">

            <input
                type="hidden"
                name="csrf_token"
                value="<?= $_SESSION['csrf_token']; ?>"
            >

            <div class="row">

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        First Name
                    </label>

                    <input
                        type="text"
                        name="firstname"
                        class="form-control"
                        required
                    >

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Middle Name
                    </label>

                    <input
                        type="text"
                        name="middlename"
                        class="form-control"
                    >

                </div>

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Last Name
                </label>

                <input
                    type="text"
                    name="lastname"
                    class="form-control"
                    required
                >

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Email Address
                </label>

                <input
                    type="email"
                    name="email"
                    class="form-control"
                    required
                >

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Password
                </label>

                <input
                    type="password"
                    name="password"
                    class="form-control"
                    required
                >

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Confirm Password
                </label>

                <input
                    type="password"
                    name="confirm_password"
                    class="form-control"
                    required
                >

            </div>

            <div class="mb-4">

                <label class="form-label">
                    Role
                </label>

                <select
                    name="role"
                    class="form-select"
                    required
                >

                    <option value="">
                        Select Role
                    </option>

                    <option value="teacher">
                        Teacher
                    </option>

                    <option value="student">
                        Student
                    </option>

                </select>

            </div>

            <button type="submit"
                    class="btn btn-primary w-100">

                Register

            </button>

            <div class="text-center mt-3">

                Already have an account?

                <a href="login.php">
                    Login
                </a>

            </div>

        </form>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>