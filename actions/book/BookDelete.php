<?php
declare(strict_types=1);

require_once __DIR__ . "/../../config/Database.php";

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
  //check muna kung merong transactions before deleting this book
  $sql = "SELECT id
          FROM transactions
          WHERE book_id = :id";

  $stmt = $conn->prepare($sql);

  $stmt->execute([
    ':id' => $id
  ]);

  if ($stmt->fetch()) {
    return;
  }

  $sql = "DELETE 
          FROM books
          WHERE id = :id";

  $stmt = $conn->prepare($sql);

  $stmt->execute([
    ':id' => $id
  ]);
}
