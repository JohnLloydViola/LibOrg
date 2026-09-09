<?php 
declare(strict_types=1);

require_once __DIR__ . "/../../config/Database.php";

if($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: ../../pages/BooksPage.php');
  exit;
}

$id = (int) ($_POST['id'] ?? '');
$title = trim($_POST['title'] ?? '');
$author = trim($_POST['author'] ?? '');
$category = trim($_POST['category'] ?? '');
$publication_year = (int) ($_POST['publication_year'] ?? '');
$quantity = (int) ($_POST['quantity'] ?? '');

updateBook(
  $conn,
  $id,
  $title,
  $author,
  $category,
  $publication_year,
  $quantity
);

header('Location: ../../pages/BooksPage.php');
exit;

function updateBook(
  PDO $conn,
  int $id,
  string $title,
  string $author,
  string $category,
  int $publication_year,
  int $quantity
): void 
{
  // Kunin ang current quantity at available quantity
  $sql = "SELECT quantity, available_quantity
          FROM books
          WHERE id = :id";

  $stmt = $conn->prepare($sql);

  $stmt->execute([
    ':id' => $id
  ]);

  $book = $stmt->fetch();

  // Bilangin kung ilang copies ang na borrowed
  $borrowedQuantity = $book['quantity'] - $book['available_quantity'];

  // wag payagan ang bagong quantity na mas mababa sa borrowed copies
  if ($quantity < $borrowedQuantity) {
    return;
  }

  // Calculate ang bagong available quantity
  $availableQuantity = $quantity - $borrowedQuantity;

  $sql = "UPDATE books 
          SET title = :title,
              author = :author,
              category = :category,
              publication_year = :publication_year,
              quantity = :quantity,
              available_quantity = :available_quantity
          WHERE id = :id";

  $stmt = $conn->prepare($sql);

  $stmt->execute([
    ':title' => $title,
    ':author' => $author,
    ':category' => $category,
    ':publication_year' => $publication_year,
    ':quantity' => $quantity,
    ':available_quantity' => $availableQuantity,
    ':id' => $id
  ]);
}