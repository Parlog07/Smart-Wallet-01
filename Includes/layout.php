<!-- Includes/layout.php -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Smart Wallet</title>
</head>
<body class="bg-gray-100 text-gray-800">
  <div class="min-h-screen flex">

    <!-- LEFT SIDEBAR -->
    <aside class="w-72 bg-white shadow-lg p-6">
      <a href="/php/Income-Expenses-Tracker/index.php" class="block mb-8">
        <h1 class="text-2xl font-extrabold text-blue-600">Smart Wallet</h1>
      </a>
      <nav class="space-y-2">
  <a href="/php/Income-Expenses-Tracker/index.php" 
     class="flex items-center gap-3 rounded px-3 py-2 hover:bg-blue-50">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" d="M3 9l9-6 9 6v10a2 2 0 01-2 2h-4m-6 0H5a2 2 0 01-2-2z" />
    </svg>
    Dashboard
  </a>
  <a href="/php/Income-Expenses-Tracker/Incomes/index.php" 
     class="flex items-center gap-3 rounded px-3 py-2 hover:bg-blue-50">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
    </svg>
    Incomes
  </a>
  <a href="/php/Income-Expenses-Tracker/Expenses/index.php" 
     class="flex items-center gap-3 rounded px-3 py-2 hover:bg-blue-50">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" d="M6 12h12" />
    </svg>
    Expenses
  </a>
</nav>
    </aside>
    <main id="main-content" class="flex-1 p-8">