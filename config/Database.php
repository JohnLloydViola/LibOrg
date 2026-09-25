<?php
declare(strict_types=1);

$host = 'sql111.infinityfree.com';
$dbname = 'if0_42995694_liborg_db';
$user = 'if0_42995694';
$pass = 'zeVkd6hR0Gfr';

try {
    $conn = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $user,
        $pass
    );

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}