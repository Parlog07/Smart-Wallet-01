<?php
include "../Includes/db.php";
$amount = $_POST["amount"];
$description = $_POST["description"];
$date = $_POST["date"];
$stmt = $pdo->prepare("INSERT INTO expenses (amount, description, date) VALUES (?, ?, ?)");
$stmt->execute([$amount, $description, $date]);
header("Location: index.php");
exit;
?>