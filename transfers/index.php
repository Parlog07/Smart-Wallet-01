<?php
require_once "../Includes/auth.php";
require_once "../Includes/db.php";
include "../Includes/layout.php";

$user_id = $_SESSION["user_id"];

$stmt = $pdo->prepare("
    SELECT 
        t.amount,
        t.created_at,
        u.email AS receiver_email,
        c.provider,
        c.card_last4
    FROM transfer t
    JOIN users u ON u.id = t.receiver_id
    JOIN cards c ON c.id = t.sender_card_id
    WHERE t.sender_id = ?
    ORDER BY t.created_at DESC
");
$stmt->execute([$user_id]);
$transfers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- HEADER + SEND BUTTON -->
<div class="flex items-center justify-between mb-6">
  <h2 class="text-2xl font-bold">My Transfers</h2>

  <a href="create.php"
     class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
     + Send Money
  </a>
</div>

<!-- TRANSFERS TABLE -->
<table class="bg-white rounded shadow min-w-full">
  <thead class="bg-purple-50">
    <tr>
      <th class="p-3 text-left">Receiver</th>
      <th class="p-3 text-left">Card</th>
      <th class="p-3 text-left">Amount</th>
      <th class="p-3 text-left">Date</th>
    </tr>
  </thead>
  <tbody>
    <?php if (empty($transfers)): ?>
      <tr>
        <td colspan="4" class="p-4 text-center text-gray-500">
          No transfers yet.
        </td>
      </tr>
    <?php endif; ?>

    <?php foreach ($transfers as $t): ?>
      <tr class="border-t">
        <td class="p-3"><?= htmlspecialchars($t['receiver_email']) ?></td>
        <td class="p-3">
          <?= htmlspecialchars($t['provider']) ?> ••••<?= $t['card_last4'] ?>
        </td>
        <td class="p-3"><?= number_format($t['amount'], 2) ?> MAD</td>
        <td class="p-3"><?= $t['created_at'] ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

</main>
</body>
</html>
