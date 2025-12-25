<?php
require_once "../Includes/auth.php";
require_once "../Includes/db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request");
}

$user_id = $_SESSION["user_id"] ?? null;

// ===== VALIDATION =====
$provider     = trim($_POST["provider"] ?? "");
$card_number  = preg_replace('/\s+/', '', $_POST["card_number"] ?? "");
$limit_amount = $_POST["limit_amount"] ?? null;
$expiry_date  = $_POST["expiry_date"] ?? null;
$is_main      = isset($_POST["is_main"]) ? (int)$_POST["is_main"] : 0;

if (
    !$user_id ||
    $provider === "" ||
    $card_number === "" ||
    strlen($card_number) < 4 ||
    !$limit_amount ||
    !$expiry_date
) {
    die("Invalid card data");
}

// ===== SECURITY =====
// Store ONLY last 4 digits
$card_last4 = substr($card_number, -4);

// ===== MAIN CARD LOGIC =====
// If this card is set as main → unset others
if ($is_main === 1) {
    $stmt = $pdo->prepare("UPDATE cards SET is_main = 0 WHERE user_id = ?");
    $stmt->execute([$user_id]);
}

// ===== INSERT CARD =====
$stmt = $pdo->prepare("
    INSERT INTO cards (user_id, provider, card_last4, limit_amount, expiry_date, is_main)
    VALUES (?, ?, ?, ?, ?, ?)
");

$stmt->execute([
    $user_id,
    $provider,
    $card_last4,
    $limit_amount,
    $expiry_date,
    $is_main
]);

// ===== REDIRECT =====
header("Location: index.php");
exit;
