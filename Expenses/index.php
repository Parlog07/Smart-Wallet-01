<?php
require_once "../Includes/auth.php";
require_once "../Includes/db.php";
include "../Includes/layout.php";

$user_id = $_SESSION["user_id"];
$stmt = $pdo->query("SELECT id, name FROM categories");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("
    SELECT id, provider, is_main
    FROM cards
    WHERE user_id = ?
");
$stmt->execute([$user_id]);
$cards = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("
    SELECT 
        expenses.*,
        cards.provider AS card_provider,
        cards.card_last4
    FROM expenses
    JOIN cards ON expenses.card_id = cards.id
    WHERE expenses.user_id = ?
    ORDER BY expenses.date DESC, expenses.id DESC
");
$stmt->execute([$user_id]);
$expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="flex items-center justify-between mb-6">
  <h2 class="text-2xl font-bold">Expenses</h2>
  <button id="openAddExpense"
          class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
    + Add Expense
  </button>
</div>

<div class="bg-white rounded shadow overflow-hidden">
  <table class="min-w-full divide-y">
    <thead class="bg-red-50">
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
      <?php foreach ($expenses as $expense): ?>
        <tr class="hover:bg-gray-50">
          <td class="px-4 py-3"><?= $expense["id"] ?></td>
          <td class="px-4 py-3"><?= number_format($expense["amount"], 2) ?> MAD</td>
          <td class="px-4 py-3"><?= htmlspecialchars($expense["description"]) ?></td>
          <td class="px-4 py-3">
            <?= htmlspecialchars($expense["card_provider"]) ?>
            ••••<?= htmlspecialchars($expense["card_last4"]) ?>
          </td>
          <td class="px-4 py-3"><?= htmlspecialchars($expense["date"]) ?></td>
          <td class="px-4 py-3">
            <button
              class="edit-btn text-blue-600 hover:underline mr-3"
              data-id="<?= $expense['id'] ?>"
              data-amount="<?= $expense['amount'] ?>"
              data-description="<?= htmlspecialchars($expense['description']) ?>"
              data-date="<?= $expense['date'] ?>"
              data-card="<?= $expense['card_id'] ?>"
            >Edit</button>

            <a href="delete.php?id=<?= $expense['id'] ?>"
               class="text-red-600 hover:underline"
               onclick="return confirm('Delete this expense?');">
              Delete
            </a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>


<div id="addModal" class="fixed inset-0 z-40 hidden flex items-center justify-center bg-black/40">
  <div class="bg-white w-full max-w-md rounded-lg p-6 shadow-lg">
    <div class="flex justify-between items-center mb-4">
      <h3 class="text-lg font-bold">Add Expense</h3>
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
  <label class="block text-sm">Category</label>
  <select name="category_id" required class="w-full border p-2 rounded">
    <option value="">-- Select category --</option>
    <?php foreach ($categories as $cat): ?>
      <option value="<?= $cat['id'] ?>">
        <?= htmlspecialchars($cat['name']) ?>
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
        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded">Save</button>
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
      <h3 class="text-lg font-bold">Edit Expense</h3>
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
        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded">Update</button>
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
document.getElementById('openAddExpense').onclick = () => show(addModal);
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
