<?php
declare(strict_types=1);

header("Content-Type: application/json");

require_once __DIR__ . "/../../config/Database.php";
require_once __DIR__ . "/../../config/session.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);

    echo json_encode([
        "message" => "Method not allowed."
    ]);

    exit;
}

verify_csrf_token();

// Get JSON data from Axios
$data = json_decode(file_get_contents("php://input"), true);

if (!is_array($data)) {
  http_response_code(400);
  echo json_encode(["message" => "Invalid JSON data."]);
  exit;
}

$id = filter_var($data["id"] ?? null, FILTER_VALIDATE_INT);

if ($id === false || $id === null || $id <= 0) {
  http_response_code(400);
  echo json_encode(["message" => "Invalid member ID."]);
  exit;
}

if (!$id || $id <= 0) {
  http_response_code(400);

  echo json_encode([
    "message" => "Invalid member ID."
  ]);

  exit;
}

try {
    deleteMember($conn, $id);
} catch (PDOException $error) {
  http_response_code(500);

  echo json_encode([
    "message" => "Failed to delete member"
  ]);

  exit;
}

function deleteMember(
  PDO $conn,
  int $id
): void
{
  $memberName = getMemberName($conn, $id);

  // Check if the member exists
  if ($memberName === null) {
    http_response_code(404);

    echo json_encode([
      "message" => "Member not found."
    ]);

    return;
  }

  //check din kung merong naka reference dito na transaction then return do not delete.
  $sql = "SELECT id
          FROM transactions
          WHERE member_id = :id
          LIMIT 1";

  $stmt = $conn->prepare($sql);

  $stmt->execute([
    ":id" => $id
  ]);

  if ($stmt->fetch()) {
    http_response_code(409);

    echo json_encode([
      "message" => 'Cannot Delete "' . $memberName . '" This Member Has Existing Transaction Record'
    ]);

    return;
  }

  $sql = "DELETE
          FROM members
          WHERE id = :id";

  $stmt = $conn->prepare($sql);

  $stmt->execute([
    ":id" => $id
  ]);

  echo json_encode([
    "message" => 'Member "' . $memberName . '" Deleted Successfully.'
  ]);
}

function getMemberName(
  PDO $conn,
  int $id
): ?string
{
  $sql = "SELECT *
          FROM members
          WHERE id = :id";

  $stmt = $conn->prepare($sql);

  $stmt->execute([
    ":id" => $id
  ]);

  $member = $stmt->fetch();

  return $member ? $member["full_name"] : null;
}