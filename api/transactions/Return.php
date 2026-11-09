<?php
declare(strict_types=1);

require_once __DIR__ . "/../../vendor/autoload.php";
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

if (!is_array($data)) {
  http_response_code(400);
  echo json_encode([
    "message" => "Invalid JSON data."
  ]);
  exit;
}

$id = filter_var($data["id"] ?? null, FILTER_VALIDATE_INT);

if ($id === false || $id === null || $id <= 0) {
  http_response_code(422);
  echo json_encode([
    "message" => "Invalid transaction ID."
  ]);
  exit;
}

if ($id <= 0) {
  http_response_code(422);
  echo json_encode([
    "message" => "Transaction ID is required."
  ]);
  exit;
}

$transaction = getTransaction($conn, $id);

if (!$transaction) {
  http_response_code(404);
  echo json_encode([
    "message" => "Transaction not found."
  ]);
  exit;
}

if ($transaction["status"] === "returned") {
  http_response_code(409);
  echo json_encode([
    "message" => 'Cannot return a transaction with a "Returned" status.'
  ]);
  exit;
}

$bookData = getBook($conn, (int) $transaction["book_id"]);

if (!$bookData) {
  http_response_code(404);
  echo json_encode([
    "message" => "Book not found."
  ]);
  exit;
}

$book = new App\Book(
  $bookData["title"],
  $bookData["author"],
  $bookData["category"],
  $bookData["publication_year"],
  $bookData["quantity"],
  $bookData["available_quantity"]
);

$book->returnItem();

updateBookAvailability($conn, (int) $transaction["book_id"], $book->getAvailableQuantity());

updateTransactionStatus($conn, $id);

$memberName = getMemberName($conn, (int) $transaction["member_id"]);

echo json_encode([
  "message" => '"' . $memberName . '" Returned "' . $book->getTitle() . '" Successfully'
]);

exit;

function getTransaction(PDO $conn, int $id): array|false
{
  $sql = "SELECT *
          FROM transactions
          WHERE id = :id";

  $stmt = $conn->prepare($sql);
  $stmt->execute([":id" => $id]);

  return $stmt->fetch();
}

function getBook(PDO $conn, int $book_id): array|false
{
  $sql = "SELECT *
          FROM books
          WHERE id = :id";

  $stmt = $conn->prepare($sql);
  $stmt->execute([":id" => $book_id]);

  return $stmt->fetch();
}

function updateBookAvailability(
  PDO $conn,
  int $book_id,
  int $available_quantity
): void {
  $sql = "UPDATE books
          SET available_quantity = :available_quantity
          WHERE id = :id";

  $stmt = $conn->prepare($sql);
  $stmt->execute([
    ":available_quantity" => $available_quantity,
    ":id" => $book_id
  ]);
}

function updateTransactionStatus(PDO $conn, int $id): void
{
  $sql = "UPDATE transactions
          SET status = :status
          WHERE id = :id";

  $stmt = $conn->prepare($sql);
  $stmt->execute([
    ":status" => "returned",
    ":id" => $id
  ]);
}

function getMemberName(
  PDO $conn,
  int $id
) 
{
  $sql = "SELECT * 
          FROM members
          WHERE id = :id";

  $stmt = $conn->prepare($sql);

  $stmt->execute([
    ":id" => $id
  ]);

  $member = $stmt->fetch();

  return $member["full_name"];
}