<?php
include "Includes/db.php";

// TOTAL INCOME
$stmt = $pdo->prepare("SELECT SUM(amount) AS total_income FROM incomes");
$stmt->execute();
$total_income = $stmt->fetch()["total_income"] ?? 0;

// TOTAL EXPENSE
$stmt = $pdo->prepare("SELECT SUM(amount) AS total_expense FROM expenses");
$stmt->execute();
$total_expense = $stmt->fetch()["total_expense"] ?? 0;

// BALANCE
$balance = $total_income - $total_expense;

// MONTHLY INCOME
$stmt = $pdo->prepare("
    SELECT MONTH(date) AS month, SUM(amount) AS total 
    FROM incomes 
    GROUP BY MONTH(date)
    ORDER BY MONTH(date)
");
$stmt->execute();
$monthlyIncome = $stmt->fetchAll(PDO::FETCH_KEY_PAIR); // [month => total]

// MONTHLY EXPENSE
$stmt = $pdo->prepare("
    SELECT MONTH(date) AS month, SUM(amount) AS total 
    FROM expenses 
    GROUP BY MONTH(date)
    ORDER BY MONTH(date)
");
$stmt->execute();
$monthlyExpense = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

// Prepare months 1–12
$months = [];
$incomeData = [];
$expenseData = [];

for ($m = 1; $m <= 12; $m++) {
    $months[] = date("M", mktime(0, 0, 0, $m, 1)); // Jan, Feb, etc
    $incomeData[] = $monthlyIncome[$m] ?? 0;
    $expenseData[] = $monthlyExpense[$m] ?? 0;
}

include "Includes/layout.php";
?>

<h1 class="text-3xl font-bold mb-8">Dashboard Overview</h1>
<!-- MAIN CARDS -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
    <div class="bg-white rounded-xl p-6 shadow border-l-4 border-green-500">
        <div class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none"
                 viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
            </svg>
            <h2 class="text-sm text-gray-500">Total Income</h2>
        </div>
        <p class="text-3xl font-bold mt-2 text-green-600"><?= number_format($total_income, 2) ?> MAD</p>
    </div>
    <div class="bg-white rounded-xl p-6 shadow border-l-4 border-red-500">
        <div class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600" fill="none"
                 viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 12H6" />
            </svg>
            <h2 class="text-sm text-gray-500">Total Expense</h2>
        </div>
        <p class="text-3xl font-bold mt-2 text-red-600"><?= number_format($total_expense, 2) ?> MAD</p>
    </div>
    <div class="bg-white rounded-xl p-6 shadow border-l-4 <?= $balance >= 0 ? 'border-blue-500' : 'border-red-500' ?>">
        <div class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none"
                 viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M2.25 12.75V9A2.25 2.25 0 014.5 6.75h15A2.25 2.25 0 0121.75 9v6a2.25 2.25 0 01-2.25 2.25h-15A2.25 2.25 0 012.25 15V12.75z" />
            </svg>
            <h2 class="text-sm text-gray-500">Current Balance</h2>
        </div>

        <p class="text-3xl font-bold mt-2 <?= $balance >= 0 ? 'text-blue-600' : 'text-red-600' ?>">
            <?= number_format($balance, 2) ?> MAD
        </p>
    </div>

</div>

<!-- CHART SECTION -->
<div class="bg-white p-8 rounded-xl shadow mb-10">
    <h2 class="text-xl font-bold mb-4">Monthly Overview (Income vs Expense)</h2>

    <canvas id="financeChart" height="120"></canvas>
</div>

</main>
</body>
</html>

<!-- CHART.JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const ctx = document.getElementById('financeChart').getContext('2d');

const financeChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= json_encode($months) ?>,
        datasets: [
            {
                label: 'Income',
                data: <?= json_encode($incomeData) ?>,
                borderColor: 'rgb(34,197,94)', // green
                backgroundColor: 'rgba(34,197,94,0.15)',
                tension: 0.3,
                borderWidth: 2,
                fill: true
            },
            {
                label: 'Expense',
                data: <?= json_encode($expenseData) ?>,
                borderColor: 'rgb(239,68,68)', // red
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
