<?php
session_start();

require_once "../classes/Database.php";
require_once "../classes/Income.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

$userId = $_SESSION['user_id'];

$amount = $_POST['amount'] ?? null;
$description = $_POST['description'] ?? '';
$date = $_POST['date'] ?? null;

if (!$amount || !$date) {
    die("Invalid input");
}

$db = new Database();
$pdo = $db->connect();

$incomeModel = new Income($pdo);

$created = $incomeModel->create(
    $userId,
    $amount,
    $description,
    $date
);

if ($created) {
    header("Location: index.php");
    exit;
} else {
    die("Failed to add income");
}
