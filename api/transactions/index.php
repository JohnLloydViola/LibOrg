<?php
declare(strict_types=1);

header("Content-Type: application/json");

require_once __DIR__ . "/../../config/Database.php";

$name = trim($_GET["name"] ?? "");
$status = trim($_GET["status"] ?? "");

// Para sa pagination
$page = max(1, (int) ($_GET["page"] ?? 1));
$limit = 6;
$offset = ($page - 1) * $limit;

$transactions = searchTransactions($conn, $name, $status, $limit, $offset);

echo json_encode($transactions);

function searchTransactions(
  PDO $conn,
  string $name,
  string $status,
  int $limit,
  int $offset
): array
{
  $sql = "SELECT transactions.id, members.full_name, books.title, transactions.status
          FROM transactions
          INNER JOIN members
          ON transactions.member_id = members.id
          INNER JOIN books
          ON transactions.book_id = books.id
          WHERE members.full_name LIKE :name
          AND members.role = 'member'";

  $parameters = [
    ":name" => "%$name%"
  ];

  if ($status !== "") {
    $sql .= " AND transactions.status = :status";
    $parameters[":status"] = $status;
  }

  // Get total matching transactions
  $countSql = "SELECT COUNT(*)
               FROM transactions
               INNER JOIN members
               ON transactions.member_id = members.id
               WHERE members.full_name LIKE :name
               AND members.role = 'member'";

  $countParameters = [
    ":name" => "%$name%"
  ];

  if ($status !== "") {
    $countSql .= " AND transactions.status = :status";
    $countParameters[":status"] = $status;
  }

  $countStmt = $conn->prepare($countSql);
  $countStmt->execute($countParameters);

  $totalTransactions = (int) $countStmt->fetchColumn();

  $sql .= " LIMIT :limit OFFSET :offset";

  $stmt = $conn->prepare($sql);

  foreach ($parameters as $key => $value) {
    $stmt->bindValue($key, $value);
  }

  $stmt->bindValue(":limit", $limit, PDO::PARAM_INT);
  $stmt->bindValue(":offset", $offset, PDO::PARAM_INT);

  $stmt->execute();

  return [
    "transactions" => $stmt->fetchAll(),
    "total" => $totalTransactions
  ];
}
