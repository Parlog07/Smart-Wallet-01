<?php
require_once "../Includes/auth.php";
require_once "../Includes/db.php";

$user_id = $_SESSION["user_id"];

$amount = $_POST["amount"];
$description = $_POST["description"];
$date = $_POST["date"];
$card_id = $_POST["card_id"];

if (!$amount || !$date || !$card_id) {
    die("Invalid input");
}

$stmt = $pdo->prepare("
    INSERT INTO incomes (amount, description, date, user_id, card_id)
    VALUES (?, ?, ?, ?, ?)
");

$stmt->execute([
    $amount,
    $description,
    $date,
    $user_id,
    $card_id
]);

header("Location: index.php");
exit;
