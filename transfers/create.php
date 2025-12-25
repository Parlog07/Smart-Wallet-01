<?php
require_once "../Includes/auth.php";
require_once "../Includes/db.php";
include "../Includes/layout.php";

$user_id = $_SESSION["user_id"];

// User cards
$stmt = $pdo->prepare("
    SELECT id, provider, card_last4
    FROM cards
    WHERE user_id = ?
");
$stmt->execute([$user_id]);
$cards = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2 class="text-2xl font-bold mb-6">Send Money</h2>

<form method="POST" action="store.php"
      class="bg-white p-6 rounded shadow max-w-md space-y-4">

  <div>
    <label class="block text-sm">Receiver Email</label>
    <input type="email" name="email" required
           class="w-full border p-2 rounded">
  </div>

  <div>
    <label class="block text-sm">From Card</label>
    <select name="card_id" required
            class="w-full border p-2 rounded">
      <?php foreach ($cards as $card): ?>
        <option value="<?= $card['id'] ?>">
          <?= htmlspecialchars($card['provider']) ?> ••••<?= $card['card_last4'] ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>

  <div>
    <label class="block text-sm">Amount</label>
    <input type="number" step="0.01" name="amount" required
           class="w-full border p-2 rounded">
  </div>

  <button class="bg-purple-600 text-white px-4 py-2 rounded">
    Send
  </button>

</form>

</main>
</body>
</html>
