<?php
include "../Includes/db.php";
$id = $_GET["id"];
$amount = $_POST["amount"];
$description = $_POST["description"];
$date = $_POST["date"];

$stmt = $pdo->prepare("UPDATE expenses SET amount=?, description=?, date=? WHERE id=?");
$stmt->execute([$amount, $description, $date, $id]);

header("Location: index.php");
exit;
?>