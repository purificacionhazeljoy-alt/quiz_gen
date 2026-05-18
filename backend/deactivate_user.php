<?php
session_start();
require_once "database.php";

if ($_SESSION['role'] !== 'admin') {
    exit();
}

$id = $_GET['id'];

$stmt = $pdo->prepare("
    UPDATE users
    SET status='inactive'
    WHERE user_id=?
");

$stmt->execute([$id]);

header("Location: ../frontend/admin/users.php");