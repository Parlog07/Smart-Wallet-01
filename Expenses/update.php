<?php
require_once "../Includes/auth.php";
require_once "../Includes/db.php";

$user_id = $_SESSION["user_id"];
$id = $_GET["id"];
$amount = $_POST["amount"];
$description = $_POST["description"];
$date = $_POST["date"];

$stmt = $pdo->prepare("UPDATE expenses SET amount=?, description=?, date=? WHERE id=? AND user_id = ?");
$stmt->execute([$amount, $description, $date, $id, $user_id]);

header("Location: index.php");
exit;
?>