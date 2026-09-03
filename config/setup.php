<?php

$host = 'localhost';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 1. Create database
    $sql = "CREATE DATABASE IF NOT EXISTS liborg_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;";
    $pdo->exec($sql);
    echo "Database 'liborg_db' created or already exists.<br>";

    $pdo->exec("USE liborg_db;");

    // 2. Create Book table
    $bookTable = "
    CREATE TABLE IF NOT EXISTS Book (
        book_id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        author VARCHAR(255) NOT NULL,
        genre VARCHAR(100),
        pages INT,
        publication_date DATE,
        price DECIMAL(10, 2),
        status VARCHAR(20) NOT NULL DEFAULT 'available'
    ) ENGINE=InnoDB;
    ";
    $pdo->exec($bookTable);

    // 3. Create Member table
    $memberTable = "
    CREATE TABLE IF NOT EXISTS Member (
        member_id INT AUTO_INCREMENT PRIMARY KEY,
        first_name VARCHAR(100) NOT NULL,
        last_name VARCHAR(100) NOT NULL,
        sex VARCHAR(10),
        birthdate DATE,
        joined TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;
    ";
    $pdo->exec($memberTable);

    // 4. Create Transaction table with Foreign Keys
    $transactionTable = "
    CREATE TABLE IF NOT EXISTS Transaction (
        transaction_id INT AUTO_INCREMENT PRIMARY KEY,
        book_id INT NOT NULL,
        member_id INT NOT NULL,
        type VARCHAR(50) NOT NULL,
        transaction_date DATETIME DEFAULT CURRENT_TIMESTAMP,
        
        CONSTRAINT fk_transaction_book 
            FOREIGN KEY (book_id) REFERENCES Book(book_id) 
            ON DELETE CASCADE 
            ON UPDATE CASCADE,
            
        CONSTRAINT fk_transaction_member 
            FOREIGN KEY (member_id) REFERENCES Member(member_id) 
            ON DELETE CASCADE 
            ON UPDATE CASCADE
    ) ENGINE=InnoDB;
    ";
    $pdo->exec($transactionTable);
    echo "Tables and Foreign Keys set up successfully.<br>";

} catch (PDOException $e) {
    die("Setup failed: " . $e->getMessage());
}