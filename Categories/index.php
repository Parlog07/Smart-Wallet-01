<?php
require_once "../Includes/auth.php";
require_once "../Includes/db.php";
include "../Includes/layout.php";

$user_id = $_SESSION["user_id"];
$error = "";
$success = "";


$stmt = $pdo->query("SELECT * FROM categories ORDER BY name ASC");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);


$stmt = $pdo->prepare("
    SELECT category_id, monthly_limit
    FROM category_limits
    WHERE user_id = ?
");
$stmt->execute([$user_id]);
$limits = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $category_id = $_POST["category_id"] ?? null;
    $monthly_limit = isset($_POST["monthly_limit"]) && $_POST["monthly_limit"] !== ""
        ? (float) $_POST["monthly_limit"]
        : null;

    if (!$category_id || $monthly_limit === null || $monthly_limit <= 0) {
        $error = "Monthly limit must be greater than 0";
    } else {
        $stmt = $pdo->prepare("
            INSERT INTO category_limits (user_id, category_id, monthly_limit)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE monthly_limit = VALUES(monthly_limit)
        ");
        $stmt->execute([
            $user_id,
            $category_id,
            $monthly_limit
        ]);

        $success = "Limit saved successfully";
    }
}
?>

<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold">Category Limits</h2>
</div>

<?php if ($error): ?>
    <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
        <?= htmlspecialchars($success) ?>
    </div>
<?php endif; ?>

<div class="bg-white rounded shadow overflow-hidden">
    <table class="min-w-full divide-y">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-3 text-left text-sm">Category</th>
                <th class="px-4 py-3 text-left text-sm">Monthly Limit (MAD)</th>
                <th class="px-4 py-3 text-left text-sm">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            <?php foreach ($categories as $category): ?>
                <tr>
                    <td class="px-4 py-3 font-medium">
                        <?= htmlspecialchars($category["name"]) ?>
                    </td>
                    <td class="px-4 py-3">
                        <?= isset($limits[$category["id"]])
                            ? number_format($limits[$category["id"]], 2)
                            : "-" ?>
                    </td>
                    <td class="px-4 py-3">
                        <form method="POST" class="flex gap-2">
                            <input type="hidden" name="category_id"
                                   value="<?= $category["id"] ?>">

                            <input
                                type="number"
                                name="monthly_limit"
                                step="0.01"
                                min="0.01"
                                required
                                placeholder="Limit"
                                class="border p-2 rounded w-32"
                            >

                            <button
                                class="bg-blue-600 text-white px-3 py-2 rounded hover:bg-blue-700">
                                Save
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</main>
</body>
</html>
