<?php
session_start();

require_once "../classes/Database.php";
require_once "../classes/User.php";
require_once "../Includes/mailer.php";

$errors = [];

// $db = new Database();
// $pdo = $db->connect();
$userModel = new User($pdo);

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    if (empty($password)) {
        $errors[] = "Password is required";
    }

    if (empty($errors)) {

        $user = $userModel->login($email, $password);

        if (!$user) {
            $errors[] = "Invalid email or password";
        }
    }
    if (empty($errors)) {

        $otp = random_int(100000, 999999);

        $_SESSION["otp"] = $otp;
        $_SESSION["otp_expires"] = time() + 300; // 5 minutes
        $_SESSION["otp_user_id"] = $user["id"];

        $sent = sendOTPEmail($user["email"], $otp);

        if (!$sent) {
            $errors[] = "Failed to send OTP email. Please try again.";
        } else {
            header("Location: verify_otp.php");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login | Smart Wallet</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

<div class="bg-white w-full max-w-md p-8 rounded-xl shadow">
  <h1 class="text-2xl font-bold text-center text-blue-600 mb-2">Smart Wallet</h1>
  <p class="text-center text-gray-500 mb-6">Login to your account</p>

  <?php if (!empty($errors)): ?>
    <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
      <?php foreach ($errors as $error): ?>
        <p><?= htmlspecialchars($error) ?></p>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <form method="POST" class="space-y-4">
    <input type="email" name="email" placeholder="Email"
           class="w-full border p-2 rounded" required>

    <input type="password" name="password" placeholder="Password"
           class="w-full border p-2 rounded" required>

    <button class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
      Login
    </button>
  </form>

  <p class="text-center text-sm text-gray-500 mt-4">
    No account?
    <a href="register.php" class="text-blue-600 hover:underline">Register</a>
  </p>
</div>

</body>
</html>
