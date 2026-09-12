<?php
declare(strict_types=1);
//Allows everything even domains and content type json and methods para pang testing
//For security later pwede ibahin mga allowed
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once __DIR__ . "/../../config/Database.php";

$name = trim($_GET["name"] ?? "");
$status = trim($_GET["status"] ?? "");

$transactions = searchTransactions($conn, $name, $status);

echo json_encode($transactions);

function searchTransactions(
  PDO $conn,
  string $name,
  string $status
): array
{
  $sql = "SELECT transactions.id, members.full_name, books.title, transactions.status
          FROM transactions
          INNER JOIN members
          ON transactions.member_id = members.id
          INNER JOIN books
          ON transactions.book_id = books.id
          WHERE members.full_name LIKE :name";

  $parameters = [
    ":name" => "%$name%"
  ];

  if ($status !== "") {
    $sql .= " AND transactions.status = :status";
    $parameters[":status"] = $status;
  }

  $stmt = $conn->prepare($sql);

  $stmt->execute($parameters);

  return $stmt->fetchAll();
}