<?php 
declare(strict_types=1);

require_once __DIR__ . "/../../config/Database.php";

session_start();

unset($_SESSION["editBookErrors"]);

if($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: ../../pages/BooksPage.php');
  exit;
}

$id = (int) ($_POST['id'] ?? '');
$title = trim($_POST['title'] ?? '');
$author = trim($_POST['author'] ?? '');
$category = trim($_POST['category'] ?? '');
$publication_year = trim($_POST['publication_year'] ?? '');
$quantity = trim($_POST['quantity'] ?? '');

if (!validateBook($title, $author, $category, $publication_year, $quantity)) {
  header("Location: ../../pages/BooksPage.php");
  
  exit;
}

$publication_year = (int) $publication_year;
$quantity = (int) $quantity;

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
    $_SESSION["editBookErrors"]["quantity"] = "New Quantity should not be lower than the borrowed quantity";

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

function validateBook(
  string $title,
  string $author,
  string $category,
  string $publication_year,
  string $quantity
): bool
{
  if ($title === "") {
    $_SESSION["editBookErrors"]["title"] = "Title is Required";
  }

  if ($author === "") {
    $_SESSION["editBookErrors"]["author"] = "Author is Required";
  }elseif (!preg_match('/[a-zA-Z]/', $author)) {
    $_SESSION["editBookErrors"]["author"] = "Author must contain letters";
  } 

  if ($category === "") {
    $_SESSION["editBookErrors"]["category"] = "Category is Required";
  }

  if ($publication_year === "") {
    $_SESSION["editBookErrors"]["publication_year"] = "Publication Year is Required";
  }elseif ((int) $publication_year <= 0) {
    $_SESSION["editBookErrors"]["publication_year"] = "Invalid Year";
  }

  if ($quantity === "") {
    $_SESSION["editBookErrors"]["quantity"] = "Quantity is Required";
  }elseif ((int) $quantity <= 0) {
    $_SESSION["editBookErrors"]["quantity"] = "Invalid Quantity";
  }

  return empty($_SESSION["editBookErrors"]);
}