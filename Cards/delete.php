<?php
require_once "../Includes/auth.php";
require_once "../Includes/db.php";

$user_id = $_SESSION["user_id"];
$card_id = (int) $_GET["id"];

// Prevent deleting main card
$stmt = $pdo->prepare("
    SELECT is_main FROM cards WHERE id = ? AND user_id = ?
");
$stmt->execute([$card_id, $user_id]);
$card = $stmt->fetch();

if ($card && !$card["is_main"]) {
    $stmt = $pdo->prepare("
        DELETE FROM cards WHERE id = ? AND user_id = ?
    ");
    $stmt->execute([$card_id, $user_id]);
}

header("Location: index.php");
exit;
