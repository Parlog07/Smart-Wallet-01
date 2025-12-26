<?php
require_once "../Includes/auth.php";
require_once "../Includes/layout.php";
require_once "../classes/Database.php";
require_once "../classes/Expense.php";

$db = new Database();
$pdo = $db->connect();

$expenseModel = new Expense($pdo);
$userId = $_SESSION["user_id"];
$expenses = $expenseModel->getAllByUser($userId);
?>

<h2 class="text-2xl font-bold mb-6">Expenses</h2>

<button id="openAddExpense" class="bg-red-600 text-white px-4 py-2 rounded mb-4">
  + Add Expense
</button>

<div class="bg-white rounded shadow overflow-hidden">
  <table class="min-w-full divide-y">
    <thead class="bg-red-100">
      <tr>
        <th class="px-4 py-2 text-left">Amount</th>
        <th class="px-4 py-2 text-left">Description</th>
        <th class="px-4 py-2 text-left">Date</th>
        <th class="px-4 py-2 text-left">Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($expenses as $e): ?>
      <tr class="border-t">
        <td class="px-4 py-2"><?= number_format($e["amount"], 2) ?> MAD</td>
        <td class="px-4 py-2"><?= htmlspecialchars($e["description"]) ?></td>
        <td class="px-4 py-2"><?= $e["date"] ?></td>
        <td class="px-4 py-2">
          <button
            class="edit-btn text-blue-600 mr-3"
            data-id="<?= $e["id"] ?>"
            data-amount="<?= $e["amount"] ?>"
            data-description="<?= htmlspecialchars($e["description"]) ?>"
            data-date="<?= $e["date"] ?>"
          >Edit</button>

          <a href="delete.php?id=<?= $e["id"] ?>"
             class="text-red-600"
             onclick="return confirm('Delete this expense?')">
            Delete
          </a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<!-- ADD MODAL -->
<div id="addModal" class="fixed inset-0 hidden bg-black/40 flex items-center justify-center">
  <form method="POST" action="store.php" class="bg-white p-6 rounded w-96">
    <h3 class="text-lg font-bold mb-4">Add Expense</h3>

    <input name="amount" type="number" placeholder="amount" step="0.01" required class="w-full border p-2 mb-3">
    <input name="description" type="text" placeholder="description" required class="w-full border p-2 mb-3">
    <input name="date" type="date" required class="w-full border p-2 mb-4">

    <div class="flex justify-end gap-2">
      <button type="button" id="closeAdd" class="border px-4 py-2">Cancel</button>
      <button class="bg-red-600 text-white px-4 py-2 rounded">Save</button>
    </div>
  </form>
</div>

<!-- EDIT MODAL -->
<div id="editModal" class="fixed inset-0 hidden bg-black/40 flex items-center justify-center">
  <form id="editForm" method="POST" class="bg-white p-6 rounded w-96">
    <h3 class="text-lg font-bold mb-4">Edit Expense</h3>

    <input id="editAmount" name="amount" type="number" step="0.01" required class="w-full border p-2 mb-3">
    <input id="editDescription" name="description" type="text" required class="w-full border p-2 mb-3">
    <input id="editDate" name="date" type="date" required class="w-full border p-2 mb-4">

    <div class="flex justify-end gap-2">
      <button type="button" id="closeEdit" class="border px-4 py-2">Cancel</button>
      <button class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
    </div>
  </form>
</div>

<script>
const addModal = document.getElementById("addModal");
const editModal = document.getElementById("editModal");

document.getElementById("openAddExpense").onclick = () => addModal.classList.remove("hidden");
document.getElementById("closeAdd").onclick = () => addModal.classList.add("hidden");

document.querySelectorAll(".edit-btn").forEach(btn => {
  btn.onclick = () => {
    editModal.classList.remove("hidden");
    document.getElementById("editAmount").value = btn.dataset.amount;
    document.getElementById("editDescription").value = btn.dataset.description;
    document.getElementById("editDate").value = btn.dataset.date;
    document.getElementById("editForm").action = "update.php?id=" + btn.dataset.id;
  };
});
document.getElementById("closeEdit").onclick = () => editModal.classList.add("hidden");
</script>
