<?php
session_start();

require_once "../classes/Database.php";
require_once "../classes/Income.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$db = new Database();
$pdo = $db->connect();

$incomeModel = new Income($pdo);
$userId = $_SESSION['user_id'];

$incomes = $incomeModel->getAllByUser($userId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Incomes</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<div class="max-w-6xl mx-auto p-6">
  <div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Incomes</h1>
    <button id="openAddIncome"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
      + Add Income
    </button>
  </div>

  <div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Amount</th>
          <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Description</th>
          <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Date</th>
          <th class="px-6 py-3 text-right text-sm font-medium text-gray-500">Actions</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200">
        <?php foreach ($incomes as $income): ?>
          <tr class="hover:bg-gray-50">
            <td class="px-6 py-4 font-semibold text-gray-700">
              <?= number_format($income['amount'], 2) ?> MAD
            </td>
            <td class="px-6 py-4 text-gray-700">
              <?= htmlspecialchars($income['description']) ?>
            </td>
            <td class="px-6 py-4 text-gray-600">
              <?= $income['date'] ?>
            </td>
            <td class="px-6 py-4 text-right">
              <button
                class="edit-btn text-blue-600 hover:underline mr-4"
                data-id="<?= $income['id'] ?>"
                data-amount="<?= $income['amount'] ?>"
                data-description="<?= htmlspecialchars($income['description']) ?>"
                data-date="<?= $income['date'] ?>"
              >
                Edit
              </button>

              <a href="delete.php?id=<?= $income['id'] ?>"
                 onclick="return confirm('Delete this income?')"
                 class="text-red-600 hover:underline">
                Delete
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<div id="addModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40">
  <div class="bg-white w-full max-w-md rounded-xl p-6 shadow-lg">
    <div class="flex justify-between items-center mb-4">
      <h3 class="text-lg font-bold">Add Income</h3>
      <button id="closeAdd" class="text-gray-500 text-xl">&times;</button>
    </div>

    <form method="POST" action="store.php" class="space-y-4">
      <input name="amount" type="number" step="0.01" required
             placeholder="Amount"
             class="w-full border p-2 rounded">

      <input name="description" type="text" required
             placeholder="Description"
             class="w-full border p-2 rounded">

      <input name="date" type="date" required
             class="w-full border p-2 rounded">

      <div class="flex justify-end gap-2">
        <button type="button" id="closeAdd2"
                class="px-4 py-2 border rounded">
          Cancel
        </button>
        <button type="submit"
                class="px-4 py-2 bg-blue-600 text-white rounded">
          Save
        </button>
      </div>
    </form>
  </div>
</div>

<div id="editModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40">
  <div class="bg-white w-full max-w-md rounded-xl p-6 shadow-lg">
    <div class="flex justify-between items-center mb-4">
      <h3 class="text-lg font-bold">Edit Income</h3>
      <button id="closeEdit" class="text-gray-500 text-xl">&times;</button>
    </div>

    <form id="editForm" method="POST" class="space-y-4">
      <input id="editAmount" name="amount" type="number" step="0.01" required
             class="w-full border p-2 rounded">

      <input id="editDescription" name="description" type="text" required
             class="w-full border p-2 rounded">

      <input id="editDate" name="date" type="date" required
             class="w-full border p-2 rounded">

      <div class="flex justify-end gap-2">
        <button type="button" id="closeEdit2"
                class="px-4 py-2 border rounded">
          Cancel
        </button>
        <button type="submit"
                class="px-4 py-2 bg-blue-600 text-white rounded">
          Update
        </button>
      </div>
    </form>
  </div>
</div>

<script>
const show = el => el.classList.remove('hidden');
const hide = el => el.classList.add('hidden');

const addModal = document.getElementById('addModal');
document.getElementById('openAddIncome').onclick = () => show(addModal);
['closeAdd','closeAdd2'].forEach(id =>
  document.getElementById(id).onclick = () => hide(addModal)
);

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

</body>
</html>
