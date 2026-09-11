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

$members = searchName($conn, $name);

echo json_encode($members);

function searchName(
  PDO $conn,
  string $name
): array 
{
  $sql = "SELECT *
          FROM members
          WHERE full_Name
          LIKE :name";
  
  $stmt = $conn->prepare($sql);

  $stmt->execute([
    ":name" => "%$name%"
  ]);

  return $stmt->fetchAll();
}