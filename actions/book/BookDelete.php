<?php
declare(strict_types=1);

require_once __DIR__ . "/../../config/Database.php";

session_start();

if($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: ../../pages/BooksPage.php');
  exit;
}

$id = (int) ($_POST['id'] ?? '');

deleteBook($conn, $id); 

header('Location: ../../pages/BooksPage.php');
exit;

function deleteBook(
  PDO $conn,
  int $id
): void 
{
  $bookName = getBookName($conn, $id);
  //check muna kung merong transactions before deleting this book
  $sql = "SELECT id
          FROM transactions
          WHERE book_id = :id";

  $stmt = $conn->prepare($sql);

  $stmt->execute([
    ':id' => $id
  ]);

  if ($stmt->fetch()) {
    $_SESSION["bookDeleteUnsuccessfull"] = 'Cannot Delete "' . $bookName . '" This Book Has Existing Transaction Record';
    
    return;
  }

  $sql = "DELETE 
          FROM books
          WHERE id = :id";

  $stmt = $conn->prepare($sql);

  $stmt->execute([
    ':id' => $id
  ]);

  $_SESSION["bookDeleteSuccess"] = 'Book "' . $bookName . '" Deleted Successfully.';
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
