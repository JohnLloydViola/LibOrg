<?php
declare(strict_types=1);

header("Content-Type: application/json");

require_once __DIR__ . "/../../config/Database.php";
require_once __DIR__ . "/../../config/session.php";

$name = $_GET["name"] ?? "";

if (!is_string($name)) {
  http_response_code(400);
  echo json_encode(["message" => "Invalid search parameter"]);
  exit;
}

$name = trim($name);

// Para sa pagination
$page = (int) ($_GET["page"] ?? 1);
$limit = 6;
$offset = ($page - 1) * $limit;

try {
  $result = searchName($conn, $name, $limit, $offset);

  http_response_code(200);
  echo json_encode($result);
} catch (PDOException $e) {
  http_response_code(500);
  echo json_encode(["message" => "Failed to retrieve members"]);
  exit;
}

function searchName(
  PDO $conn,
  string $name,
  int $limit,
  int $offset
): array
{
  $sql = "SELECT *
          FROM members
          WHERE full_name LIKE :name
          AND role = 'member'";

  // Get total matching members
  $countSql = "SELECT COUNT(*)
               FROM members
               WHERE full_name LIKE :name
               AND role = 'member'";

  $countStmt = $conn->prepare($countSql);
  $countStmt->execute([
    ":name" => "%$name%"
  ]);

  $totalMembers = (int) $countStmt->fetchColumn();

  $sql .= " LIMIT :limit OFFSET :offset";

  $stmt = $conn->prepare($sql);

  $stmt->bindValue(":name", "%$name%");
  $stmt->bindValue(":limit", $limit, PDO::PARAM_INT);
  $stmt->bindValue(":offset", $offset, PDO::PARAM_INT);

  $stmt->execute();

  return [
    "members" => $stmt->fetchAll(),
    "total" => $totalMembers
  ];
}