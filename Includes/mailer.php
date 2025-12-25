<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// ✅ CORRECT PATHS
require_once __DIR__ . '/../PHPMailer/src/Exception.php';
require_once __DIR__ . '/../PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../PHPMailer/src/SMTP.php';

function sendOTPEmail(string $toEmail, string $otp): bool
{
    $mail = new PHPMailer(true);

    try {
        // SMTP CONFIG
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'ayoubmogador2014@gmail.com';
        $mail->Password = 'uxxzggzgmktbovig'; // Gmail App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // SSL FIX FOR LARAGON / WINDOWS
        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ],
        ];

        // EMAIL CONTENT
        $mail->setFrom('ayoubmogador2014@gmail.com', 'Smart Wallet');
        $mail->addAddress($toEmail);

        $mail->isHTML(true);
        $mail->Subject = 'Your Smart Wallet OTP Code';
        $mail->Body = "
            <h2>Your OTP Code</h2>
            <p>Your verification code is:</p>
            <h1 style='letter-spacing:4px;'>$otp</h1>
            <p>This code expires in 5 minutes.</p>
        ";
        $mail->AltBody = "Your OTP code is: $otp (expires in 5 minutes)";

        $mail->send();
        return true;

    } catch (Exception $e) {
        error_log('MAIL ERROR: ' . $mail->ErrorInfo);
        return false;
    }
}
