<?php
require_once "../Includes/auth.php";
require_once "../Includes/db.php";

$user_id = $_SESSION["user_id"];
$card_id = (int) $_GET["id"];

// Remove old main
$stmt = $pdo->prepare("
    UPDATE cards SET is_main = 0 WHERE user_id = ?
");
$stmt->execute([$user_id]);

// Set new main
$stmt = $pdo->prepare("
    UPDATE cards SET is_main = 1
    WHERE id = ? AND user_id = ?
");
$stmt->execute([$card_id, $user_id]);

header("Location: index.php");
exit;
