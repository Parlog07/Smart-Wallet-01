<?php
require_once "../Includes/auth.php";
require_once "../Includes/db.php";
include "../Includes/layout.php";

$user_id = $_SESSION["user_id"];

/* =========================
   FETCH USER CARDS
   ========================= */
$stmt = $pdo->prepare("
    SELECT id, provider, is_main
    FROM cards
    WHERE user_id = ?
");
$stmt->execute([$user_id]);
$cards = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* =========================
   FETCH INCOMES + CARD INFO
   ========================= */
$stmt = $pdo->prepare("
    SELECT 
        incomes.*,
        cards.provider AS card_provider,
        cards.card_last4
    FROM incomes
    JOIN cards ON incomes.card_id = cards.id
    WHERE incomes.user_id = ?
    ORDER BY incomes.date DESC, incomes.id DESC
");
$stmt->execute([$user_id]);
$incomes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="flex items-center justify-between mb-6">
  <h2 class="text-2xl font-bold">Incomes</h2>
  <button id="openAddIncome"
          class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
    + Add Income
  </button>
</div>

<div class="bg-white rounded shadow overflow-hidden">
  <table class="min-w-full divide-y">
    <thead class="bg-blue-50">
      <tr>
        <th class="px-4 py-3 text-left text-sm">ID</th>
        <th class="px-4 py-3 text-left text-sm">Amount</th>
        <th class="px-4 py-3 text-left text-sm">Description</th>
        <th class="px-4 py-3 text-left text-sm">Card</th>
        <th class="px-4 py-3 text-left text-sm">Date</th>
        <th class="px-4 py-3 text-left text-sm">Actions</th>
      </tr>
    </thead>
    <tbody class="divide-y">
      <?php foreach ($incomes as $income): ?>
        <tr class="hover:bg-gray-50">
          <td class="px-4 py-3"><?= $income["id"] ?></td>
          <td class="px-4 py-3"><?= number_format($income["amount"], 2) ?> MAD</td>
          <td class="px-4 py-3"><?= htmlspecialchars($income["description"]) ?></td>
          <td class="px-4 py-3">
            <?= htmlspecialchars($income["card_provider"]) ?>
            ••••<?= htmlspecialchars($income["card_last4"]) ?>
          </td>
          <td class="px-4 py-3"><?= htmlspecialchars($income["date"]) ?></td>
          <td class="px-4 py-3">
            <button
              class="edit-btn text-blue-600 hover:underline mr-3"
              data-id="<?= $income['id'] ?>"
              data-amount="<?= $income['amount'] ?>"
              data-description="<?= htmlspecialchars($income['description']) ?>"
              data-date="<?= $income['date'] ?>"
              data-card="<?= $income['card_id'] ?>"
            >Edit</button>

            <a href="delete.php?id=<?= $income['id'] ?>"
               class="text-red-600 hover:underline"
               onclick="return confirm('Delete this income?');">
              Delete
            </a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<!-- =========================
     ADD MODAL
     ========================= -->
<div id="addModal" class="fixed inset-0 z-40 hidden flex items-center justify-center bg-black/40">
  <div class="bg-white w-full max-w-md rounded-lg p-6 shadow-lg">
    <div class="flex justify-between items-center mb-4">
      <h3 class="text-lg font-bold">Add Income</h3>
      <button id="closeAdd" class="text-gray-500">&times;</button>
    </div>

    <form method="POST" action="store.php" class="space-y-4">
      <div>
        <label class="block text-sm">Card</label>
        <select name="card_id" required class="w-full border p-2 rounded">
          <option value="">-- Select card --</option>
          <?php foreach ($cards as $card): ?>
            <option value="<?= $card['id'] ?>">
              <?= htmlspecialchars($card['provider']) ?>
              <?= $card['is_main'] ? '(Main)' : '' ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div>
        <label class="block text-sm">Amount</label>
        <input name="amount" type="number" step="0.01" required class="w-full border p-2 rounded">
      </div>

      <div>
        <label class="block text-sm">Description</label>
        <input name="description" type="text" required class="w-full border p-2 rounded">
      </div>

      <div>
        <label class="block text-sm">Date</label>
        <input name="date" type="date" required class="w-full border p-2 rounded">
      </div>

      <div class="flex justify-end">
        <button type="button" id="closeAdd2" class="mr-2 px-4 py-2 border rounded">Cancel</button>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Save</button>
      </div>
    </form>
  </div>
</div>

<!-- =========================
     EDIT MODAL
     ========================= -->
<div id="editModal" class="fixed inset-0 z-40 hidden flex items-center justify-center bg-black/40">
  <div class="bg-white w-full max-w-md rounded-lg p-6 shadow-lg">
    <div class="flex justify-between items-center mb-4">
      <h3 class="text-lg font-bold">Edit Income</h3>
      <button id="closeEdit" class="text-gray-500">&times;</button>
    </div>

    <form id="editForm" method="POST">
      <input type="hidden" name="card_id" id="editCard">

      <div>
        <label class="block text-sm">Amount</label>
        <input id="editAmount" name="amount" type="number" step="0.01" required class="w-full border p-2 rounded">
      </div>

      <div>
        <label class="block text-sm">Description</label>
        <input id="editDescription" name="description" type="text" required class="w-full border p-2 rounded">
      </div>

      <div>
        <label class="block text-sm">Date</label>
        <input id="editDate" name="date" type="date" required class="w-full border p-2 rounded">
      </div>

      <div class="flex justify-end mt-4">
        <button type="button" id="closeEdit2" class="mr-2 px-4 py-2 border rounded">Cancel</button>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Update</button>
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

// Add modal
const addModal = document.getElementById('addModal');
document.getElementById('openAddIncome').onclick = () => show(addModal);
['closeAdd','closeAdd2'].forEach(id =>
  document.getElementById(id).onclick = () => hide(addModal)
);

// Edit modal
const editModal = document.getElementById('editModal');
document.querySelectorAll('.edit-btn').forEach(btn => {
  btn.onclick = () => {
    document.getElementById('editAmount').value = btn.dataset.amount;
    document.getElementById('editDescription').value = btn.dataset.description;
    document.getElementById('editDate').value = btn.dataset.date;
    document.getElementById('editForm').action = `update.php?id=${btn.dataset.id}`;
    show(editModal);
  };
});
['closeEdit','closeEdit2'].forEach(id =>
  document.getElementById(id).onclick = () => hide(editModal)
);
</script>
