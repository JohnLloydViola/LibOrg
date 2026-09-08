<?php
declare(strict_Types=1);

require_once __DIR__ . "/../../config/Database.php";

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
