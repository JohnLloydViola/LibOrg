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

// Read JSON data sent by Axios
$data = json_decode(file_get_contents("php://input"), true);

$member_id = (int) ($data["member_id"] ?? "");
$book_id = (int) ($data["book_id"] ?? "");

if ($member_id <= 0) {
  http_response_code(422);
  echo json_encode([
    "message" => "Member Is Required",
    "errors" => [
      "memberSelect" => "Member Is Required"
    ]
  ]);
  exit;
} elseif ($book_id <= 0) {
  http_response_code(422);
  echo json_encode([
    "message" => "Book Is Required",
    "errors" => [
      "bookSelect" => "Book Is Required"
    ]
  ]);
  exit;
}

$memberData = getMember($conn, $member_id);

//Dito ako guamit ng src class na member
$member = new App\Member(
  $memberData["full_name"],
  $memberData["email"],
  $memberData["phone_number"],
  $memberData["address"]
);

$bookData = getBook($conn, $book_id);

//Dito ako gumamit ng src class na book.
$book = new App\Book(
  $bookData["title"],
  $bookData["author"],
  $bookData["category"],
  $bookData["publication_year"],
  $bookData["quantity"],
  $bookData["available_quantity"],
);

//Hulihin yung exception na ItemNotAvailable
try {
  $book->borrow();
} catch (App\ItemNotAvailableException $e) {
  http_response_code(409);
  echo json_encode([
    "message" => '"' . $book->getTitle() . '" ' . $e->getMessage()
  ]);
  exit;
}

$transaction = new App\Transaction(
  $member,
  $book,
  "borrowed"
);

updateBookAvailability($conn, $book_id, $book->getAvailableQuantity());

addTransaction($conn, $member_id, $book_id);

http_response_code(201);
echo json_encode([
  "message" => '"' . getMemberName($conn, $member_id) .
               '" Borrowed "' . getBookName($conn, $book_id) .
               '" Successfully'
]);
exit;

function getMember(
  PDO $conn,
  int $member_id
): array
{
  $sql = "SELECT *
          FROM members
          WHERE id = :id";

  $stmt = $conn->prepare($sql);

  $stmt->execute([
    ":id" => $member_id
  ]);

  return $stmt->fetch();
}

function getBook(
  PDO $conn,
  int $book_id
): array
{
  $sql = "SELECT *
          FROM books
          WHERE id = :id";

  $stmt = $conn->prepare($sql);

  $stmt->execute([
    ":id" => $book_id
  ]);

  return $stmt->fetch();
}

function updateBookAvailability(
  PDO $conn,
  int $book_id,
  int $available_quantity
): void
{
  $sql = "UPDATE books
          SET available_quantity = :available_quantity
          WHERE id = :id";

  $stmt = $conn->prepare($sql);

  $stmt->execute([
    ":available_quantity" => $available_quantity,
    ":id" => $book_id
  ]);
}

function addTransaction(
  PDO $conn,
  int $member_id,
  int $book_id
): void
{
  $memberName = getMemberName($conn, $member_id);
  $bookName = getBookName($conn, $book_id);

  $sql = "INSERT INTO transactions (member_id, book_id, status)
          VALUES (:member_id, :book_id, :status)";

  $stmt = $conn->prepare($sql);

  $stmt->execute([
    ":member_id" => $member_id,
    ":book_id" => $book_id,
    ":status" => "borrowed"
  ]);
}

function getMemberName(
  PDO $conn,
  int $id
): string
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

function getBookName(
  PDO $conn,
  int $id
): string
{
  $sql = "SELECT *
          FROM books
          WHERE id = :id";

  $stmt = $conn->prepare($sql);

  $stmt->execute([
    ":id" => $id
  ]);

  $book = $stmt->fetch();

  return $book["title"];
}