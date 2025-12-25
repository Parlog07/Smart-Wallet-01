<?php
include "../Includes/db.php";
include "../Includes/layout.php";

$stmt = $pdo->prepare("SELECT * FROM incomes ORDER BY date DESC, id DESC");
$stmt->execute();
$incomes = $stmt->fetchAll(PDO::FETCH_ASSOC);



?>
<div class="flex items-center justify-between mb-6">
  <h2 class="text-2xl font-bold">Incomes</h2>
  <button id="openAddIncome" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">+ Add Income</button>
</div>

<!-- Table -->
<div class="bg-white rounded shadow overflow-hidden">
  <table class="min-w-full divide-y">
    <thead class="bg-blue-50">
      <tr>
        <th class="px-4 py-3 text-left text-sm">ID</th>
        <th class="px-4 py-3 text-left text-sm">Amount</th>
        <th class="px-4 py-3 text-left text-sm">Description</th>
        <th class="px-4 py-3 text-left text-sm">Date</th>
        <th class="px-4 py-3 text-left text-sm">Actions</th>
      </tr>
    </thead>
    <tbody class="divide-y">
      <?php foreach ($incomes as $income): ?>
      <tr class="hover:bg-gray-50">
        <td class="px-4 py-3"><?= htmlspecialchars($income['id']) ?></td>
        <td class="px-4 py-3"><?= htmlspecialchars($income['amount']) ?> MAD</td>
        <td class="px-4 py-3"><?= htmlspecialchars($income['description']) ?></td>
        <td class="px-4 py-3"><?= htmlspecialchars($income['date']) ?></td>
        <td class="px-4 py-3">

          <button
            class="edit-btn text-blue-600 hover:underline mr-3"
            data-id="<?= $income['id'] ?>"
            data-amount="<?= htmlspecialchars($income['amount']) ?>"
            data-description="<?= htmlspecialchars($income['description']) ?>"
            data-date="<?= htmlspecialchars($income['date']) ?>"
          >Edit</button>

          <a href="delete.php?id=<?= $income['id'] ?>" class="text-red-600 hover:underline"
             onclick="return confirm('Delete this income?');">Delete</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<div id="addModal" class="fixed inset-0 z-40 hidden items-center justify-center bg-black/40">
  <div class="bg-white w-full max-w-md rounded-lg p-6 shadow-lg">
    <div class="flex justify-between items-center mb-4">
      <h3 class="text-lg font-bold">Add Income</h3>
      <button id="closeAdd" class="text-gray-500">&times;</button>
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
        <button type="button" id="closeAdd2" class="mr-2 px-4 py-2 border rounded">Cancel</button>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Save</button>
      </div>
    </form>
  </div>
</div>

<div id="editModal" class="fixed inset-0 z-40 hidden items-center justify-center bg-black/40">
  <div class="bg-white w-full max-w-md rounded-lg p-6 shadow-lg">
    <div class="flex justify-between items-center mb-4">
      <h3 class="text-lg font-bold">Edit Income</h3>
      <button id="closeEdit" class="text-gray-500">&times;</button>
    </div>

    <form id="editForm" method="POST" action="">
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
  const show = el => el.classList.remove('hidden'), hide = el => el.classList.add('hidden');

  // Add modal
  const addModal = document.getElementById('addModal');
  document.getElementById('openAddIncome').addEventListener('click', () => show(addModal));
  ['closeAdd','closeAdd2'].forEach(id => document.getElementById(id).addEventListener('click', () => hide(addModal)));

  // Edit modal
  const editModal = document.getElementById('editModal');
  document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const id = btn.dataset.id;
      const amount = btn.dataset.amount;
      const description = btn.dataset.description;
      const date = btn.dataset.date;

      document.getElementById('editAmount').value = amount;
      document.getElementById('editDescription').value = description;
      document.getElementById('editDate').value = date;

      document.getElementById('editForm').action = `update.php?id=${id}`;

      show(editModal);
    });
  });
  ['closeEdit','closeEdit2'].forEach(id => document.getElementById(id).addEventListener('click', () => hide(editModal)));
</script>
