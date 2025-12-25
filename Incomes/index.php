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
<body class="bg-gray-100 p-6">

<h1 class="text-2xl font-bold mb-4">Incomes</h1>

<button onclick="document.getElementById('addModal').classList.remove('hidden')"
        class="mb-4 bg-blue-600 text-white px-4 py-2 rounded">
  + Add Income
</button>

<table class="bg-white w-full rounded shadow">
  <thead class="bg-gray-200">
    <tr>
      <th class="p-2">Amount</th>
      <th class="p-2">Description</th>
      <th class="p-2">Date</th>
      <th class="p-2">Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($incomes as $income): ?>
      <tr class="border-t">
        <td class="p-2"><?= number_format($income['amount'], 2) ?> MAD</td>
        <td class="p-2"><?= htmlspecialchars($income['description']) ?></td>
        <td class="p-2"><?= $income['date'] ?></td>
        <td class="p-2">
          <a href="delete.php?id=<?= $income['id'] ?>"
             onclick="return confirm('Delete this income?')"
             class="text-red-600">
             Delete
          </a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<!-- ADD INCOME MODAL -->
<div id="addModal" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center">
  <div class="bg-white p-6 rounded w-full max-w-md">
    <h2 class="text-lg font-bold mb-4">Add Income</h2>

    <form method="POST" action="store.php" class="space-y-3">
      <input name="amount" type="number" step="0.01" required
             placeholder="Amount"
             class="w-full border p-2 rounded">

      <input name="description" type="text" required
             placeholder="Description"
             class="w-full border p-2 rounded">

      <input name="date" type="date" required
             class="w-full border p-2 rounded">

      <div class="flex justify-end gap-2">
        <button type="button"
                onclick="document.getElementById('addModal').classList.add('hidden')"
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

</body>
</html>
