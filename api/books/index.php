<?php
declare(strict_types=1);

header("Content-Type: application/json");

require_once __DIR__ . "/../../config/Database.php";

//Check if the request method is GET
if ($_SERVER["REQUEST_METHOD"] !== "GET") {
  http_response_code(405);
  echo json_encode(["message" => "Method not allowed"]);
  exit;
}

//Search using search input
$search = trim($_GET["title"] ?? "");
$category = trim($_GET["category"] ?? "");
$sort = trim($_GET["sort"] ?? "");

//Validate sorting parameter
if (!in_array($sort, ["", "asc", "desc"], true)) {
  http_response_code(400);
  echo json_encode(["message" => "Invalid sort parameter"]);
  exit;
}

//Para sa pagination
$page = max(1, (int) ($_GET["page"] ?? 1));
$limit = 6;
$offset = ($page - 1) * $limit;

try {
  $books = searchBooks($conn, $search, $category, $sort, $limit, $offset);

  echo json_encode($books);
} catch (PDOException $error) {
  http_response_code(500);
  echo json_encode(["message" => "Failed to retrieve books"]);
}

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