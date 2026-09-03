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

    $conn->exec(
        "CREATE DATABASE IF NOT EXISTS liborg_db
        CHARACTER SET utf8mb4
        COLLATE utf8mb4_unicode_ci"
    );
    echo "Database created or already exists.<br>";

    $conn->exec("USE liborg_db");

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

    $memberTable = "CREATE TABLE IF NOT EXISTS members(
        id INT AUTO_INCREMENT PRIMARY KEY,
        full_name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        phone_number VARCHAR(20) NOT NULL,
        address VARCHAR(255) NOT NULL
    ) ENGINE=InnoDB;";

    $conn->exec($memberTable);

    echo "Members table created successfully.<br>";

    $transactionTable = "CREATE TABLE IF NOT EXISTS transactions(
        id INT AUTO_INCREMENT PRIMARY KEY,
        book_id INT NOT NULL,
        member_id INT NOT NULL,
        status VARCHAR(20) NOT NULL,

        CONSTRAINT fk_transaction_book FOREIGN KEY (book_id) REFERENCES books(id),

        CONSTRAINT fk_transaction_member FOREIGN KEY (member_id) REFERENCES members(id)
    ) ENGINE=InnoDB;";

    $conn->exec($transactionTable);

    echo "Transactions table created successfully.<br>";
} catch (PDOException $e) {
    die('Setup failed: ' . $e->getMessage());
}