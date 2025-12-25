<!-- Includes/layout.php -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Smart Wallet</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 text-gray-800">
<div class="min-h-screen flex">

  <!-- SIDEBAR -->
  <aside class="w-72 bg-white shadow-lg p-6">
    <a href="/php/Income-Expenses-Tracker/index.php" class="block mb-8">
      <h1 class="text-2xl font-extrabold text-blue-600">Smart Wallet</h1>
    </a>

    <nav class="space-y-2">

      <!-- Dashboard -->
      <a href="/php/Income-Expenses-Tracker/index.php"
         class="flex items-center gap-3 px-3 py-2 rounded hover:bg-blue-50">
        🏠 Dashboard
      </a>

      <!-- Cards -->
      <a href="/php/Income-Expenses-Tracker/Cards/index.php"
         class="flex items-center gap-3 px-3 py-2 rounded hover:bg-purple-50">
        💳 Cards
      </a>

      <!-- Incomes -->
      <a href="/php/Income-Expenses-Tracker/Incomes/index.php"
         class="flex items-center gap-3 px-3 py-2 rounded hover:bg-green-50">
        ➕ Incomes
      </a>

      <!-- Expenses -->
      <a href="/php/Income-Expenses-Tracker/Expenses/index.php"
         class="flex items-center gap-3 px-3 py-2 rounded hover:bg-red-50">
        ➖ Expenses
      </a>
      <a href="/php/Income-Expenses-Tracker/Transfers/index.php"
          class="flex items-center gap-3 rounded px-3 py-2 hover:bg-purple-50">
          🔁 Transfers
      </a>
      <a href="/php/Income-Expenses-Tracker/Categories/index.php"
        class="flex items-center gap-3 rounded px-3 py-2 hover:bg-blue-50">
        📂 Categories
      </a>


      <!-- Logout -->
      <a href="/php/Income-Expenses-Tracker/auth/logout.php"
         class="flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-200 text-red-600">
        🚪 Logout
      </a>

    </nav>
  </aside>

  <!-- MAIN CONTENT -->
  <main class="flex-1 p-8">
