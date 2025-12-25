<?php
session_start();

require_once "../classes/Database.php";
require_once "../classes/Income.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$userId = $_SESSION['user_id'];
$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: index.php");
    exit;
}

$db = new Database();
$pdo = $db->connect();

$incomeModel = new Income($pdo);
$incomeModel->delete($id, $userId);

header("Location: index.php");
exit;
