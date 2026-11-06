<?php
declare(strict_types=1);

session_start();

header("Content-Type: application/json");

require_once __DIR__ . "/../../config/Database.php";

$memberId = (int) ($_SESSION["member_id"] ?? 0);
$title = trim($_GET["title"] ?? "");
$category = trim($_GET["category"] ?? "");
$sort = trim($_GET["sort"] ?? "asc");

// Para sa pagination
$page = max(1, (int) ($_GET["page"] ?? 1));
$limit = 6;
$offset = ($page - 1) * $limit;

$transactions = searchBorrowedBooks(
  $conn,
  $memberId,
  $title,
  $category,
  $sort,
  $limit,
  $offset
);

echo json_encode($transactions);

function searchBorrowedBooks(
  PDO $conn,
  int $memberId,
  string $title,
  string $category,
  string $sort,
  int $limit,
  int $offset
): array
{
  $sql = "SELECT transactions.id, books.title, books.author,
                 books.category, books.publication_year,
                 transactions.status
          FROM transactions
          INNER JOIN books
          ON transactions.book_id = books.id
          WHERE transactions.member_id = :member_id
          AND transactions.status <> 'returned'";

  $parameters = [
    ":member_id" => $memberId
  ];

  if ($title !== "") {
    $sql .= " AND books.title LIKE :title";
    $parameters[":title"] = "%$title%";
  }

  if ($category !== "") {
    $sql .= " AND books.category = :category";
    $parameters[":category"] = $category;
  }

  // Get total matching borrowed books
  $countSql = "SELECT COUNT(*)
               FROM transactions
               INNER JOIN books
               ON transactions.book_id = books.id
               WHERE transactions.member_id = :member_id
               AND transactions.status <> 'returned'";

  $countParameters = [
    ":member_id" => $memberId
  ];

  if ($title !== "") {
    $countSql .= " AND books.title LIKE :title";
    $countParameters[":title"] = "%$title%";
  }

  if ($category !== "") {
    $countSql .= " AND books.category = :category";
    $countParameters[":category"] = $category;
  }

  $countStmt = $conn->prepare($countSql);
  $countStmt->execute($countParameters);

  $totalTransactions = (int) $countStmt->fetchColumn();

  // Sort books by title
  $sort = strtolower($sort) === "desc" ? "DESC" : "ASC";
  $sql .= " ORDER BY books.title $sort LIMIT :limit OFFSET :offset";

  $stmt = $conn->prepare($sql);

  foreach ($parameters as $key => $value) {
    $stmt->bindValue($key, $value, PDO::PARAM_INT);
  }

  if ($title !== "") {
    $stmt->bindValue(":title", "%$title%");
  }

  if ($category !== "") {
    $stmt->bindValue(":category", $category);
  }

  $stmt->bindValue(":limit", $limit, PDO::PARAM_INT);
  $stmt->bindValue(":offset", $offset, PDO::PARAM_INT);

  $stmt->execute();

  return [
    "transactions" => $stmt->fetchAll(),
    "total" => $totalTransactions
  ];
}