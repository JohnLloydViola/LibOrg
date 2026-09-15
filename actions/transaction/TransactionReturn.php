<?php
declare(strict_types=1);

require_once __DIR__ . "/../../vendor/autoload.php";
require_once __DIR__ . "/../../config/Database.php";

session_start();

if($_SERVER['REQUEST_METHOD'] !== "POST") {
  header("Location: ../../pages/TransactionsPage.php");
  exit;
}

$id = (int) ($_POST["id"] ?? "");

$transaction = getTransaction($conn, $id);

$memberName = getMemberName($conn, $transaction["member_id"]);

if ($transaction["status"] === "returned") {
  $_SESSION["transactionReturnWarning"] = 'Cannot Return A Transaction With A ' . '"Returned"' . ' Status';
  
  header("Location: ../../pages/TransactionsPage.php");
  exit;
}

$bookData = getBook($conn, $transaction["book_id"]);

$book = new App\Book(
  $bookData["title"],
  $bookData["author"],
  $bookData["category"],
  $bookData["publication_year"],
  $bookData["quantity"],
  $bookData["available_quantity"],
);

$book->returnItem();

updateBookAvailability($conn, $transaction["book_id"], $book->getAvailableQuantity());

updateTransactionStatus($conn, $id, $memberName);

header("Location: ../../pages/TransactionsPage.php");
exit;

function getTransaction(PDO $conn, int $id): array
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

function getBook(PDO $conn, int $book_id): array
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

function updateTransactionStatus(PDO $conn, int $id, string $memberName): void
{
  $sql = "UPDATE transactions
          SET status = :status
          WHERE id = :id";

  $stmt = $conn->prepare($sql);

  $stmt->execute([
    ":status" => "returned",
    ":id" => $id
  ]);

  $_SESSION["transactionReturnSuccess"] = '"' . $memberName . '" Returned the Book Successfully';
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