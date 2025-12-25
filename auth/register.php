<?php
session_start();

require_once "../classes/Database.php";
require_once "../classes/User.php";

$errors = [];
$success = false;

$db = new Database();
$pdo = $db->connect();
$userModel = new User($pdo);

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name     = trim($_POST["name"] ?? "");
    $email    = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirm  = $_POST["confirm_password"] ?? "";

    if (empty($name)) {
        $errors[] = "Name is required";
    }

    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    if (empty($password)) {
        $errors[] = "Password is required";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters";
    }

    if ($password !== $confirm) {
        $errors[] = "Passwords do not match";
    }

    if (empty($errors)) {
        $created = $userModel->register($name, $email, $password);

        if ($created) {
            $success = true;  
             header("Location: login.php");
             exit;
        } else {
            $errors[] = "Registration failed. Email may already exist.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register | Smart Wallet</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

<div class="bg-white w-full max-w-md p-8 rounded-xl shadow">
  <h1 class="text-2xl font-bold text-center text-blue-600 mb-2">Smart Wallet</h1>
  <p class="text-center text-gray-500 mb-6">Create a new account</p>

  <?php if (!empty($errors)): ?>
    <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
      <?php foreach ($errors as $error): ?>
        <p><?= htmlspecialchars($error) ?></p>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <form method="POST" class="space-y-4">
    <input type="text" name="full_name" placeholder="Full name"
           class="w-full border p-2 rounded" required>

    <input type="email" name="email" placeholder="Email"
           class="w-full border p-2 rounded" required>

    <input type="password" name="password" placeholder="Password"
           class="w-full border p-2 rounded" required>

    <button class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
      Register
    </button>
  </form>

  <p class="text-center text-sm text-gray-500 mt-4">
    Already have an account?
    <a href="login.php" class="text-blue-600 hover:underline">Login</a>
  </p>
</div>

</body>
</html>
