<?php
require_once "../Includes/auth.php";
require_once "../Includes/db.php";

$user_id     = $_SESSION["user_id"];
$card_id     = $_POST["card_id"] ?? null;
$category_id = $_POST["category_id"] ?? null;
$amount      = $_POST["amount"] ?? null;
$description = $_POST["description"] ?? null;
$date        = $_POST["date"] ?? null;

if (!$card_id || !$category_id || !$amount || !$description || !$date) {
    die("Invalid expense data");
}

$stmt = $pdo->prepare("
    SELECT monthly_limit
    FROM category_limits
    WHERE user_id = ? AND category_id = ?
");
$stmt->execute([$user_id, $category_id]);
$limit = $stmt->fetchColumn();

if ($limit !== false) {
    $stmt = $pdo->prepare("
        SELECT COALESCE(SUM(amount),0)
        FROM expenses
        WHERE user_id = ?
          AND category_id = ?
          AND MONTH(date) = MONTH(CURRENT_DATE())
          AND YEAR(date) = YEAR(CURRENT_DATE())
    ");
    $stmt->execute([$user_id, $category_id]);
    $total = $stmt->fetchColumn();

    if (($total + $amount) > $limit) {
        die("Monthly limit exceeded for this category");
    }
}


$stmt = $pdo->prepare("
    INSERT INTO expenses (user_id, card_id, category_id, amount, description, date)
    VALUES (?, ?, ?, ?, ?, ?)
");
$stmt->execute([
    $user_id,
    $card_id,
    $category_id,
    $amount,
    $description,
    $date
]);

header("Location: index.php");
exit;
