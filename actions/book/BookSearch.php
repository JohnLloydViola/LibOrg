<?php
declare(strict_types=1);
//Allows everything even domains and content type json and methods para pang testing
//For security later pwede ibahin mga allowed
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once __DIR__ . "/../../config/Database.php";

//Search using search input
$search = trim($_GET["title"] ?? "");
$category = trim($_GET["category"] ?? "");
$sort = trim($_GET["sort"] ?? "");

//Para sa pagination
$page = (int) ($_GET["page"] ?? 1);
$limit = 6;
$offset = ($page - 1) * $limit;

$books = searchBooks($conn, $search, $category, $sort, $limit, $offset);

echo json_encode($books);

function searchBooks(
  PDO $conn,
  string $search,
  string $category,
  string $sort,
  int $limit,
  int $offset
): array
{
  $sql = "SELECT *
          FROM books
          WHERE title LIKE :search";

  $parameters = [
    ":search" => "%$search%"
  ];

  if ($category !== "") {
    $sql .= " AND category = :category";
    $parameters[":category"] = $category;
  }

  // Get total matching books
  $countSql = "SELECT COUNT(*)
               FROM books
               WHERE title LIKE :search";

  $countParameters = [
    ":search" => "%$search%"
  ];

  if ($category !== "") {
    $countSql .= " AND category = :category";
    $countParameters[":category"] = $category;
  }

  $countStmt = $conn->prepare($countSql);
  $countStmt->execute($countParameters);

  $totalBooks = (int) $countStmt->fetchColumn();

  if ($sort === "asc") {
    $sql .= " ORDER BY available_quantity ASC";
  } elseif ($sort === "desc") {
    $sql .= " ORDER BY available_quantity DESC";
  }

  $sql .= " LIMIT :limit OFFSET :offset";

  $stmt = $conn->prepare($sql);

  // Bind search and filter parameters
  foreach ($parameters as $key => $value) {
    $stmt->bindValue($key, $value);
  }

  // Bind pagination parameters as integers
  $stmt->bindValue(":limit", $limit, PDO::PARAM_INT);
  $stmt->bindValue(":offset", $offset, PDO::PARAM_INT);

  $stmt->execute();

  return [
    "books" => $stmt->fetchAll(),
    "total" => $totalBooks
  ];
}