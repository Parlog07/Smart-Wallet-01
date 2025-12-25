<?php
session_start();
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $inputOtp = $_POST["otp"] ?? "";

    if (
        isset($_SESSION["otp"], $_SESSION["otp_expires"], $_SESSION["otp_user_id"]) &&
        time() <= $_SESSION["otp_expires"] &&
        $inputOtp == $_SESSION["otp"]
    ) {
        $_SESSION["user_id"] = $_SESSION["otp_user_id"];

        unset($_SESSION["otp"], $_SESSION["otp_expires"], $_SESSION["otp_user_id"]);

        header("Location: ../index.php");
        exit;
    } else {
        $error = "Invalid or expired OTP";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Verify OTP</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

<div class="bg-white w-full max-w-md p-8 rounded-xl shadow">
  <h1 class="text-2xl font-bold text-center text-blue-600 mb-2">Smart Wallet</h1>
  <p class="text-center text-gray-500 mb-6">Enter the OTP sent to your email</p>

  <?php if ($error): ?>
    <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
      <?= htmlspecialchars($error) ?>
    </div>
  <?php endif; ?>

  <form method="POST" class="space-y-4">
    <input type="text" name="otp" placeholder="6-digit OTP"
           class="w-full border p-2 rounded text-center tracking-widest"
           required>

    <button class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
      Verify
    </button>
  </form>
</div>

</body>
</html>
