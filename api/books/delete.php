<?php
declare(strict_types=1);

require_once __DIR__ . "/../../config/Database.php";
require_once __DIR__ . "/../../config/session.php";

header("Content-Type: application/json");

if($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(["message" => "Method not allowed"]);
  exit;
}

verify_csrf_token();

$data = json_decode(file_get_contents("php://input"), true);

if (!is_array($data) || !isset($data["id"])) {
  http_response_code(400);
  echo json_encode(["message" => "Invalid request data"]);
  exit;
}

$id = filter_var($data["id"], FILTER_VALIDATE_INT);

if ($id === false || $id <= 0) {
  http_response_code(422);
  echo json_encode(["message" => "Invalid Book ID"]);
  exit;
}

try {
  $bookName = getBookName($conn, $id);

  //check muna for existing book
  if (!$bookName) {
    http_response_code(404);
    echo json_encode(["message" => "Book not found"]);
    exit;
  }

  //validation kung merong existing transaction record
  if (!deleteBook($conn, $id)) {
    http_response_code(409);
    echo json_encode(["message" => 'Cannot Delete "' . $bookName . '" This Book Has Existing Transaction Record']);
    exit;
  }
} catch (PDOException $error) {
  http_response_code(500);
  echo json_encode(["message" => "Failed to delete book"]);
  exit;
}

http_response_code(200);
echo json_encode(["message" => 'Book "' . $bookName . '" Deleted Successfully.']);
exit;

function deleteBook(
  PDO $conn,
  int $id
): bool 
{
  //check muna kung merong transactions before deleting this book
  $sql = "SELECT id
          FROM transactions
          WHERE book_id = :id";

  $stmt = $conn->prepare($sql);

  $stmt->execute([
    ':id' => $id
  ]);

  if ($stmt->fetch()) {
    return false;
  }

  $sql = "DELETE 
          FROM books
          WHERE id = :id";

  $stmt = $conn->prepare($sql);

  $stmt->execute([
    ':id' => $id
  ]);

  return $stmt->rowCount() === 1;
}

function getBookName(
  PDO $conn,
  int $id
): ?string
{
  $sql = "SELECT * 
          FROM books
          WHERE id = :id";

  $stmt = $conn->prepare($sql);

  $stmt->execute([
    ":id" => $id
  ]);

  $book = $stmt->fetch();

  return $book ? $book["title"] : null;
}
