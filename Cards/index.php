<?php
require_once "../Includes/auth.php";
require_once "../Includes/db.php";
include "../Includes/layout.php";

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("
  SELECT * FROM cards
  WHERE user_id = ?
  ORDER BY is_main DESC, created_at ASC
");
$stmt->execute([$user_id]);
$cards = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="flex items-center justify-between mb-6">
  <h2 class="text-2xl font-bold">My Cards</h2>
  <button id="openAddCard"
          class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
    + Add Card
  </button>
</div>

<?php if (empty($cards)): ?>
  <p class="text-gray-500">No cards yet.</p>
<?php endif; ?>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
<?php foreach ($cards as $card): ?>

<?php
// balance per card
$stmt = $pdo->prepare("SELECT COALESCE(SUM(amount),0) FROM incomes WHERE card_id=? AND user_id=?");
$stmt->execute([$card['id'], $user_id]);
$income = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COALESCE(SUM(amount),0) FROM expenses WHERE card_id=? AND user_id=?");
$stmt->execute([$card['id'], $user_id]);
$expense = $stmt->fetchColumn();

$balance = $income - $expense;
?>

<div class="rounded-xl p-5 text-white shadow-lg
            <?= $card['is_main'] ? 'bg-gradient-to-r from-purple-600 to-indigo-600'
                                 : 'bg-gradient-to-r from-gray-700 to-gray-900' ?>">
  <div class="flex justify-between items-center mb-6">
    <span class="text-lg font-semibold"><?= htmlspecialchars($card['provider']) ?></span>
    <?php if ($card['is_main']): ?>
      <span class="bg-green-500 text-xs px-2 py-1 rounded">MAIN</span>
    <?php endif; ?>
  </div>

  <p class="text-xl tracking-widest mb-4">
    •••• •••• •••• <?= htmlspecialchars($card['card_last4']) ?>
  </p>

  <div class="flex justify-between text-sm">
    <div>
      <p class="opacity-70">Balance</p>
      <p class="font-bold"><?= number_format($balance, 2) ?> MAD</p>
    </div>
    <div>
      <p class="opacity-70">Expiry</p>
      <p class="font-bold"><?= date('m/y', strtotime($card['expiry_date'])) ?></p>
    </div>
  </div>
</div>

<?php endforeach; ?>
</div>

<!-- ADD CARD MODAL -->
<div id="addCardModal"
     class="fixed inset-0 hidden bg-black/40 flex items-center justify-center z-50">
  <div class="bg-white rounded-xl w-full max-w-md p-6">
    <div class="flex justify-between mb-4">
      <h3 class="text-lg font-bold">Add New Card</h3>
      <button id="closeAddCard">&times;</button>
    </div>

    <form method="POST" action="store.php" class="space-y-4">

      <input name="provider" placeholder="Provider (Visa, CIH...)"
             required class="w-full border p-2 rounded">

      <input name="card_number" placeholder="Card Number"
             required class="w-full border p-2 rounded">

      <input name="limit_amount" type="number" step="0.01"
             placeholder="Limit"
             required class="w-full border p-2 rounded">

      <input name="expiry_date" type="date"
             required class="w-full border p-2 rounded">

      <select name="is_main" class="w-full border p-2 rounded">
        <option value="0">Secondary</option>
        <option value="1">Primary</option>
      </select>

      <div class="flex justify-end gap-2">
        <button type="button" id="closeAddCard2"
                class="px-4 py-2 border rounded">Cancel</button>
        <button class="px-4 py-2 bg-purple-600 text-white rounded">
          Add Card
        </button>
      </div>
    </form>
  </div>
</div>

</main>
</body>
</html>

<script>
const show = el => el.classList.remove('hidden');
const hide = el => el.classList.add('hidden');

const modal = document.getElementById('addCardModal');
document.getElementById('openAddCard').onclick = () => show(modal);
['closeAddCard','closeAddCard2'].forEach(id =>
  document.getElementById(id).onclick = () => hide(modal)
);
</script>