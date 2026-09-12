<?php
declare(strict_types=1);

// Allows everything even domains and content type json and methods para pang testing
// For security later pwede ibahin mga allowed
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once __DIR__ . "/../../config/Database.php";

$name = trim($_GET["name"] ?? "");

// Para sa pagination
$page = (int) ($_GET["page"] ?? 1);
$limit = 6;
$offset = ($page - 1) * $limit;

$members = searchName($conn, $name, $limit, $offset);

echo json_encode($members);

function searchName(
  PDO $conn,
  string $name,
  int $limit,
  int $offset
): array 
{
  $sql = "SELECT *
          FROM members
          WHERE full_name LIKE :name";

  $parameters = [
    ":name" => "%$name%"
  ];

  // Get total matching members
  $countSql = "SELECT COUNT(*)
               FROM members
               WHERE full_name LIKE :name";

  $countStmt = $conn->prepare($countSql);
  $countStmt->execute([
    ":name" => "%$name%"
  ]);

  $totalMembers = (int) $countStmt->fetchColumn();

  $sql .= " LIMIT :limit OFFSET :offset";

  $stmt = $conn->prepare($sql);

  $stmt->bindValue(":name", $parameters[":name"]);
  $stmt->bindValue(":limit", $limit, PDO::PARAM_INT);
  $stmt->bindValue(":offset", $offset, PDO::PARAM_INT);

  $stmt->execute();

  return [
    "members" => $stmt->fetchAll(),
    "total" => $totalMembers
  ];
}