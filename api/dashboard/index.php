<?php
declare(strict_types=1);

require_once __DIR__ . "/../../config/Database.php";

header("Content-Type: application/json");

echo json_encode([
  "totalBooks" => getTotalBooks($conn),
  "totalMembers" => getTotalMembers($conn),
  "totalBorrowed" => getTotalBorrowed($conn),
  "transactions" => getRecentTransactions($conn)
]);

exit;

function getTotalBooks(PDO $conn): int
{
  $sql = "SELECT COUNT(*)
          FROM books";

  $stmt = $conn->query($sql);

  return (int) $stmt->fetchColumn();
}

function getTotalMembers(PDO $conn): int
{
  $sql = "SELECT COUNT(*)
          FROM members
          WHERE role = 'member'";

  $stmt = $conn->query($sql);

  return (int) $stmt->fetchColumn();
}

function getTotalBorrowed(PDO $conn): int
{
  $sql = "SELECT COUNT(*)
          FROM transactions
          WHERE status = :status";

  $stmt = $conn->prepare($sql);

  $stmt->execute([
    ":status" => "borrowed"
  ]);

  return (int) $stmt->fetchColumn();
}

function getRecentTransactions(PDO $conn): array
{
  $sql = "SELECT transactions.id, members.full_name, books.title, transactions.status
          FROM transactions
          INNER JOIN members
          ON transactions.member_id = members.id
          INNER JOIN books
          ON transactions.book_id = books.id
          ORDER BY transactions.id DESC
          LIMIT 5";

  $stmt = $conn->query($sql);

  return $stmt->fetchAll();
}