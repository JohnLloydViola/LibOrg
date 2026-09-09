<?php
declare(strict_types=1);

require_once __DIR__ . "/../../config/Database.php";

if($_SERVER["REQUEST_METHOD"] !== "POST") {
  header("Location: ../../pages/TransactionsPage.php");
  exit;
}

$id = (int) ($_POST["id"] ?? "");

$transaction = getTransaction($conn, $id);

if ($transaction["status"] === "borrowed") {
  restoreBookAvailability($conn, $transaction["book_id"]);
}

transactionDelete($conn, $id);

header("Location: ../../pages/TransactionsPage.php");
exit;

function getTransaction(
  PDO $conn,
  int $id
): array
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

//Restore yung books kase what if yung dedeelte na transaction is naka borrowed status pa.
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