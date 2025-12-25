<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=smart-walet", "root", "");
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>