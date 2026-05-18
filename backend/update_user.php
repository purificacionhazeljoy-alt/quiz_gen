<?php
session_start();

require_once "database.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    exit();
}

$user_id = $_POST['user_id'];

$firstname = trim($_POST['firstname']);
$lastname = trim($_POST['lastname']);
$email = trim($_POST['email']);
$role = trim($_POST['role']);

$stmt = $pdo->prepare("
    UPDATE users
    SET
        firstname = ?,
        lastname = ?,
        email = ?,
        role = ?
    WHERE user_id = ?
");

$stmt->execute([
    $firstname,
    $lastname,
    $email,
    $role,
    $user_id
]);

header("Location: ../frontend/admin/users.php");
exit();