<?php
require_once "../Includes/auth.php";
require_once "../classes/Database.php";
require_once "../classes/Expense.php";

$db = new Database();
$pdo = $db->connect();
$expenseModel = new Expense($pdo);

$expenseModel->update(
    $_GET["id"],
    $_SESSION["user_id"],
    $_POST["amount"],
    $_POST["description"],
    $_POST["date"]
);

header("Location: index.php");
exit;
