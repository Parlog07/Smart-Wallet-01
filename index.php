<?php
require_once "Includes/auth.php";
require_once "Includes/db.php";

$user_id = $_SESSION["user_id"];

// TOTAL INCOME
$stmt = $pdo->prepare("
    SELECT SUM(amount) AS total_income 
    FROM incomes 
    WHERE user_id = ?
");
$stmt->execute([$user_id]);
$total_income = $stmt->fetch()["total_income"] ?? 0;

// TOTAL EXPENSE
$stmt = $pdo->prepare("
    SELECT SUM(amount) AS total_expense 
    FROM expenses 
    WHERE user_id = ?
");
$stmt->execute([$user_id]);
$total_expense = $stmt->fetch()["total_expense"] ?? 0;

// BALANCE
$balance = $total_income - $total_expense;

// MONTHLY INCOME (CURRENT MONTH)
$stmt = $pdo->prepare("
    SELECT SUM(amount) AS monthly_income 
    FROM incomes 
    WHERE user_id = ?
      AND MONTH(date) = MONTH(CURRENT_DATE())
      AND YEAR(date) = YEAR(CURRENT_DATE())
");
$stmt->execute([$user_id]);
$monthly_income = $stmt->fetch()["monthly_income"] ?? 0;

// MONTHLY EXPENSE (CURRENT MONTH)
$stmt = $pdo->prepare("
    SELECT SUM(amount) AS monthly_expense 
    FROM expenses 
    WHERE user_id = ?
      AND MONTH(date) = MONTH(CURRENT_DATE())
      AND YEAR(date) = YEAR(CURRENT_DATE())
");
$stmt->execute([$user_id]);
$monthly_expense = $stmt->fetch()["monthly_expense"] ?? 0;

/* =========================
   CHART DATA (PER MONTH)
   ========================= */

// Incomes per month
$stmt = $pdo->prepare("
    SELECT MONTH(date) AS month, SUM(amount) AS total
    FROM incomes
    WHERE user_id = ?
    GROUP BY MONTH(date)
");
$stmt->execute([$user_id]);
$incomeRows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Expenses per month
$stmt = $pdo->prepare("
    SELECT MONTH(date) AS month, SUM(amount) AS total
    FROM expenses
    WHERE user_id = ?
    GROUP BY MONTH(date)
");
$stmt->execute([$user_id]);
$expenseRows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Prepare arrays
$incomeData = array_fill(1, 12, 0);
$expenseData = array_fill(1, 12, 0);

foreach ($incomeRows as $row) {
    $incomeData[(int)$row["month"]] = (float)$row["total"];
}

foreach ($expenseRows as $row) {
    $expenseData[(int)$row["month"]] = (float)$row["total"];
}

// Month labels
$monthLabels = [];
for ($m = 1; $m <= 12; $m++) {
    $monthLabels[] = date("M", mktime(0, 0, 0, $m, 1));
}

include "Includes/layout.php";
?>

<h1 class="text-3xl font-bold mb-8">Dashboard Overview</h1>

<!-- MAIN CARDS -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">

    <div class="bg-white rounded-xl p-6 shadow border-l-4 border-green-500">
        <h2 class="text-sm text-gray-500">Total Income</h2>
        <p class="text-3xl font-bold mt-2 text-green-600">
            <?= number_format($total_income, 2) ?> MAD
        </p>
    </div>

    <div class="bg-white rounded-xl p-6 shadow border-l-4 border-red-500">
        <h2 class="text-sm text-gray-500">Total Expense</h2>
        <p class="text-3xl font-bold mt-2 text-red-600">
            <?= number_format($total_expense, 2) ?> MAD
        </p>
    </div>

    <div class="bg-white rounded-xl p-6 shadow border-l-4 <?= $balance >= 0 ? 'border-blue-500' : 'border-red-500' ?>">
        <h2 class="text-sm text-gray-500">Current Balance</h2>
        <p class="text-3xl font-bold mt-2 <?= $balance >= 0 ? 'text-blue-600' : 'text-red-600' ?>">
            <?= number_format($balance, 2) ?> MAD
        </p>
    </div>

</div>

<!-- CHART -->
<div class="bg-white p-8 rounded-xl shadow mb-10">
    <h2 class="text-xl font-bold mb-4">Monthly Overview (Income vs Expense)</h2>
    <canvas id="financeChart" height="120"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const ctx = document.getElementById('financeChart').getContext('2d');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= json_encode($monthLabels) ?>,
        datasets: [
            {
                label: 'Income',
                data: <?= json_encode(array_values($incomeData)) ?>,
                borderColor: 'rgb(34,197,94)',
                backgroundColor: 'rgba(34,197,94,0.15)',
                tension: 0.3,
                borderWidth: 2,
                fill: true
            },
            {
                label: 'Expense',
                data: <?= json_encode(array_values($expenseData)) ?>,
                borderColor: 'rgb(239,68,68)',
                backgroundColor: 'rgba(239,68,68,0.15)',
                tension: 0.3,
                borderWidth: 2,
                fill: true
            }
        ]
    },
    options: {
        responsive: true,
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>
