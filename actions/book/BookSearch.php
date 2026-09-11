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

$books = searchBooks($conn, $search, $category, $sort);

echo json_encode($books);

function searchBooks(
  PDO $conn, 
  string $search, 
  string $category,
  string $sort
): array 
{
  $sql = "SELECT *
          FROM books
          WHERE title 
          LIKE :search";

  $parameters = [
    ":search" => "%$search%"
  ];

  if ($category !== "") {
    $sql .= " AND category = :category";

    $parameters[":category"] = $category;
  }

  if ($sort === "asc") {
    $sql .= " ORDER BY available_quantity ASC";
  } elseif ($sort === "desc") {
    $sql .= " ORDER BY available_quantity DESC";
  }

  $stmt = $conn->prepare($sql);

  $stmt->execute($parameters);

  return $stmt->fetchAll();
}