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
$amount = $_POST['amount'] ?? null;
$description = $_POST['description'] ?? null;
$date = $_POST['date'] ?? null;

if (!$id || !$amount || !$description || !$date) {
    header("Location: index.php");
    exit;
}

$db = new Database();
$pdo = $db->connect();

$incomeModel = new Income($pdo);
$incomeModel->update($id, $userId, $amount, $description, $date);

header("Location: index.php");
exit;
