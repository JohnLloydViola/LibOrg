<?php
$host = 'localhost';
$dbname = 'liborg_db';
$user = 'root';
$pass = '';

try {
    // Establish the PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false, // Use real prepared statements
    ]);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}