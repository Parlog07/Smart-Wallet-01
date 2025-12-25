<?php

include "../Includes/db.php";
include "../Includes/layout.php";

$stmt = $pdo->prepare("SELECT * FROM expenses ORDER BY date DESC, id DESC");
$stmt->execute();
$expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>

<div class="flex items-center justify-between mb-6">
  <h2 class="text-2xl font-bold">Expenses</h2>
  <button id="openAddExpense" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">+ Add Expense</button>
</div>

<div class="bg-white rounded shadow overflow-hidden">
  <table class="min-w-full divide-y">
    <thead class="bg-red-50">
      <tr>
        <th class="px-4 py-3 text-left text-sm">ID</th>
        <th class="px-4 py-3 text-left text-sm">Amount</th>
        <th class="px-4 py-3 text-left text-sm">Description</th>
        <th class="px-4 py-3 text-left text-sm">Date</th>
        <th class="px-4 py-3 text-left text-sm">Actions</th>
      </tr>
    </thead>
    <tbody class="divide-y">
      <?php foreach ($expenses as $expense): ?>
      <tr class="hover:bg-gray-50">
        <td class="px-4 py-3"><?= htmlspecialchars($expense['id']) ?></td>
        <td class="px-4 py-3"><?= htmlspecialchars($expense['amount']) ?> MAD</td>
        <td class="px-4 py-3"><?= htmlspecialchars($expense['description']) ?></td>
        <td class="px-4 py-3"><?= htmlspecialchars($expense['date']) ?></td>
        <td class="px-4 py-3">
          <button
            class="edit-exp-btn text-blue-600 hover:underline mr-3"
            data-id="<?= $expense['id'] ?>"
            data-amount="<?= htmlspecialchars($expense['amount']) ?>"
            data-description="<?= htmlspecialchars($expense['description']) ?>"
            data-date="<?= htmlspecialchars($expense['date']) ?>"
          >Edit</button>

          <a href="delete.php?id=<?= $expense['id'] ?>" class="text-red-600 hover:underline" onclick="return confirm('Delete this expense?');">Delete</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<div id="addExpenseModal" class="fixed inset-0 z-40 hidden items-center justify-center bg-black/40">
  <div class="bg-white w-full max-w-md rounded-lg p-6 shadow-lg">
    <div class="flex justify-between items-center mb-4">
      <h3 class="text-lg font-bold">Add Expense</h3>
      <button id="closeAddExpense" class="text-gray-500">&times;</button>
    </div>

    <form method="POST" action="store.php" class="space-y-4">
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
        <button type="button" id="closeAddExpense2" class="mr-2 px-4 py-2 border rounded">Cancel</button>
        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded">Save</button>
      </div>
    </form>
  </div>
</div>

<div id="editExpenseModal" class="fixed inset-0 z-40 hidden items-center justify-center bg-black/40">
  <div class="bg-white w-full max-w-md rounded-lg p-6 shadow-lg">
    <div class="flex justify-between items-center mb-4">
      <h3 class="text-lg font-bold">Edit Expense</h3>
      <button id="closeEditExpense" class="text-gray-500">&times;</button>
    </div>

    <form id="editExpenseForm" method="POST" action="">
      <div>
        <label class="block text-sm">Amount</label>
        <input id="editExpenseAmount" name="amount" type="number" step="0.01" required class="w-full border p-2 rounded">
      </div>

      <div>
        <label class="block text-sm">Description</label>
        <input id="editExpenseDescription" name="description" type="text" required class="w-full border p-2 rounded">
      </div>

      <div>
        <label class="block text-sm">Date</label>
        <input id="editExpenseDate" name="date" type="date" required class="w-full border p-2 rounded">
      </div>

      <div class="flex justify-end mt-4">
        <button type="button" id="closeEditExpense2" class="mr-2 px-4 py-2 border rounded">Cancel</button>
        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded">Update</button>
      </div>
    </form>
  </div>
</div>

</main>
</body>
</html>

<script>
  // Add Expense modal controls
  const addExpenseModal = document.getElementById('addExpenseModal');
  document.getElementById('openAddExpense').addEventListener('click', () => addExpenseModal.classList.remove('hidden'));
  ['closeAddExpense','closeAddExpense2'].forEach(id => document.getElementById(id).addEventListener('click', () => addExpenseModal.classList.add('hidden')));

  // Edit Expense
  const editExpenseModal = document.getElementById('editExpenseModal');
  document.querySelectorAll('.edit-exp-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const id = btn.dataset.id;
      document.getElementById('editExpenseAmount').value = btn.dataset.amount;
      document.getElementById('editExpenseDescription').value = btn.dataset.description;
      document.getElementById('editExpenseDate').value = btn.dataset.date;
      document.getElementById('editExpenseForm').action = `update.php?id=${id}`;
      editExpenseModal.classList.remove('hidden');
    });
  });
  ['closeEditExpense','closeEditExpense2'].forEach(id => document.getElementById(id).addEventListener('click', () => editExpenseModal.classList.add('hidden')));
</script>
