<?php
require_once "../Includes/auth.php";
require_once "../Includes/db.php";

$user_id = $_SESSION["user_id"];

$email   = $_POST["email"] ?? null;
$card_id = $_POST["card_id"] ?? null;
$amount  = $_POST["amount"] ?? null;

if (!$email || !$card_id || !$amount || $amount <= 0) {
    die("Invalid transfer data");
}

// Get receiver
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$email]);
$receiver = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$receiver) {
    die("Receiver not found");
}

$receiver_id = $receiver["id"];

if ($receiver_id == $user_id) {
    die("You cannot send money to yourself");
}

// Check card ownership
$stmt = $pdo->prepare("
    SELECT id FROM cards
    WHERE id = ? AND user_id = ?
");
$stmt->execute([$card_id, $user_id]);

if (!$stmt->fetch()) {
    die("Invalid card");
}

// Save transfer
$stmt = $pdo->prepare("
    INSERT INTO transfer (sender_id, receiver_id, sender_card_id, amount)
    VALUES (?, ?, ?, ?)
");
$stmt->execute([
    $user_id,
    $receiver_id,
    $card_id,
    $amount
]);

header("Location: index.php");
exit;
