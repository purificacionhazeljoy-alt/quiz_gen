<?php

session_start();

require_once __DIR__ . '/database.php';




/* =========================
   ONLY ALLOW POST
========================= */

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header("Location: ../register.php");
    exit();
}

/* =========================
   CSRF VALIDATION
========================= */

if (
    !isset($_POST['csrf_token']) ||
    $_POST['csrf_token'] !== $_SESSION['csrf_token']
) {

    header("Location: ../register.php?error=invalid_csrf");
    exit();
}

/* =========================
   SANITIZE INPUTS
========================= */

$firstname = trim($_POST['firstname']);
$middlename = trim($_POST['middlename']);
$lastname = trim($_POST['lastname']);
$email = trim($_POST['email']);
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];
$role = $_POST['role'];

/* =========================
   VALIDATION
========================= */

if ($password !== $confirm_password) {

    header("Location: ../register.php?error=password_mismatch");
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    header("Location: ../register.php?error=invalid_email");
    exit();
}

/* =========================
   VALID ROLES ONLY
========================= */

$allowed_roles = ['teacher', 'student'];

if (!in_array($role, $allowed_roles)) {

    header("Location: ../register.php?error=invalid_role");
    exit();
}

/* =========================
   CHECK EMAIL EXISTENCE
========================= */

$check_stmt = $pdo->prepare(

    "SELECT user_id
     FROM users
     WHERE email = ?"
);

$check_stmt->execute([$email]);

if ($check_stmt->rowCount() > 0) {

    header("Location: ../register.php?error=email_exists");
    exit();
}

/* =========================
   HASH PASSWORD
========================= */

$hashed_password = password_hash(
    $password,
    PASSWORD_BCRYPT
);

/* =========================
   INSERT USER
========================= */

$insert_stmt = $pdo->prepare(

    "INSERT INTO users (

        firstname,
        middlename,
        lastname,
        email,
        password,
        role

    )

    VALUES (?, ?, ?, ?, ?, ?)"
);

$success = $insert_stmt->execute([

    $firstname,
    $middlename,
    $lastname,
    $email,
    $hashed_password,
    $role
]);

if ($success) {

    // clear session AFTER successful register
    session_unset();
    session_destroy();

    header("Location: ../login.php?success=registered");
    exit();

} else {

    header("Location: ../register.php?error=registration_failed");
    exit();
}