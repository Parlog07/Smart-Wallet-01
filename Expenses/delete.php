<?php
include "../Includes/db.php";
$id = $_GET["id"];

$stmt = $pdo->prepare("DELETE FROM expenses WHERE id = ?");
$stmt->execute([$id]);

header("Location: index.php");
exit;
?>