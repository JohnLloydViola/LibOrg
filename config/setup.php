<?php
declare(strict_types=1);

$host = 'localhost';
$user = 'root';
$pass = '';

try {
    $conn = new PDO(
        "mysql:host=$host;charset=utf8mb4",
        $user,
        $pass
    );

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create yung database
    $conn->exec(
        "CREATE DATABASE IF NOT EXISTS liborg_db
        CHARACTER SET utf8mb4
        COLLATE utf8mb4_unicode_ci"
    );
    echo "Database created or already exists.<br>";

    $conn->exec("USE liborg_db");

    // Create yung books table
    $bookTable = "CREATE TABLE IF NOT EXISTS books(
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        author VARCHAR(255) NOT NULL,
        category VARCHAR(100) NOT NULL,
        publication_year INT NOT NULL,
        quantity INT NOT NULL,
        available_quantity INT NOT NULL
    ) ENGINE=InnoDB;";

    $conn->exec($bookTable);

    echo "Books table created successfully.<br>";

} catch (PDOException $e) {
    die('Setup failed: ' . $e->getMessage());
}