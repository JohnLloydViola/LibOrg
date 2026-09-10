<?php
declare(strict_types=1);

//Allows everything even domains and content type json and methods para pang testing
//For security later pwede ibahin mga allowed
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");


require_once __DIR__ . "/../../config/Database.php";

$search = trim($_GET["title"] ?? "");

$books = searchBooks($conn, $search);

echo json_encode($books);

function searchBooks(PDO $conn, string $search): array 
{
  $sql = "SELECT *
          FROM books
          WHERE title LIKE :search";
  
  $stmt = $conn->prepare($sql);

  $stmt->execute([
    ":search" => "%$search%"
  ]);

  return $stmt->fetchAll();
}