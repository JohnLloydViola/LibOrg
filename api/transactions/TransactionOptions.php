<?php
declare(strict_types=1);

require_once __DIR__ . "/../../config/Database.php";
require_once __DIR__ . "/../../config/session.php";

header("Content-Type: application/json");

try {
    echo json_encode([
        "members" => getMembers($conn),
        "books" => getBooks($conn)
    ]);
} catch (PDOException $e) {
    http_response_code(500);

    echo json_encode([
        "message" => "Failed to load dropdown options."
    ]);
}

function getTransactions(PDO $conn): array
{
  $sql = "SELECT transactions.id, members.full_name, books.title, transactions.status
          FROM transactions
          INNER JOIN members
          ON transactions.member_id = members.id
          INNER JOIN books
          ON transactions.book_id = books.id
          ";
  $stmt = $conn->query($sql);
  
  return $stmt->fetchAll();
}

function getMembers(PDO $conn): array
{
  $sql = "SELECT id, full_name
          FROM members
          WHERE role = 'member'";

  $stmt = $conn->query($sql);

  return $stmt->fetchAll();
}

function getBooks(PDO $conn): array
{
  $sql = "SELECT id, title, available_quantity
          FROM books";

  $stmt = $conn->query($sql);

  return $stmt->fetchAll();
}