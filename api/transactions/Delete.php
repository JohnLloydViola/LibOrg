<?php
declare(strict_types=1);

require_once __DIR__ . "/../../config/Database.php";

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  http_response_code(405);
  echo json_encode([
    "message" => "Method not allowed."
  ]);
  exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$id = (int) ($data["id"] ?? "");

$transaction = getTransaction($conn, $id);

if ($transaction["status"] === "borrowed") {
  restoreBookAvailability($conn, $transaction["book_id"]);
}

transactionDelete($conn, $id);

echo json_encode([
  "message" => "Transaction with ID: " . $id . " Has Been Deleted"
]);
exit;

function getTransaction(
  PDO $conn,
  int $id
): array|false
{
  $sql = "SELECT *
          FROM transactions
          WHERE id = :id";

  $stmt = $conn->prepare($sql);

  $stmt->execute([
    ":id" => $id
  ]);

  return $stmt->fetch();
}

//Restore yung books kase what if yung dedelete na transaction is naka borrowed status pa.
function restoreBookAvailability(
  PDO $conn,
  int $book_id
): void
{
  $sql = "UPDATE books
          SET available_quantity = available_quantity + 1
          WHERE id = :id";

  $stmt = $conn->prepare($sql);

  $stmt->execute([
    ":id" => $book_id
  ]);
}

function transactionDelete(
  PDO $conn,
  int $id
): void 
{
  $sql = "DELETE 
          FROM transactions 
          WHERE id = :id";

  $stmt = $conn->prepare($sql);

  $stmt->execute([
    ":id" => $id
  ]);
}