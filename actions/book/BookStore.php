<?php 
declare(strict_types=1);

session_start();

require_once __DIR__ . '/../../config/Database.php'; 

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
 
  header('Location: ../../pages/BooksPage.php');
  exit;
}

unset($_SESSION["bookErrors"]);
unset($_SESSION["bookSuccess"]);

$title = trim($_POST['title'] ?? '');
$author = trim($_POST['author'] ?? '');
$category = trim($_POST['category'] ?? '');
$publication_year = trim($_POST['publication_year'] ?? '');
$quantity = trim($_POST['quantity'] ?? '');

if (!validateBook($title, $author, $category, $publication_year, $quantity)) {
  //since midterm phase palang, redirection muna. automatic i close kasi yung modal pag ka redirect since refresh yun.
  $_SESSION["bookUnsuccessful"] = "Failed To Add Book";

  header('Location: ../../pages/BooksPage.php');
  exit;
}

$publication_year = (int) $publication_year;
$quantity = (int) $quantity;

addBook(
  $conn,
  $title,
  $author,
  $category,
  $publication_year,
  $quantity
);

header('Location: ../../pages/BooksPage.php');
exit();

function addBook(
  PDO $conn,
  string $title,
  string $author,
  string $category,
  int $publication_year,
  int $quantity
): void {
  $availableQuantity = $quantity;

  $sql = "INSERT INTO books 
  (title, author, category, publication_year, quantity, available_quantity)
  VALUES (:title, :author, :category, :publication_year, :quantity, :available_quantity)";

  $stmt = $conn->prepare($sql);

  $stmt->execute([
    ':title' => $title,
    ':author' => $author,
    ':category' => $category,
    ':publication_year' => $publication_year,
    ':quantity' => $quantity,
    ':available_quantity' => $availableQuantity
  ]);

  $_SESSION["bookSuccess"] = 'Book "' . $title . '" Added Successfully.';
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
    $_SESSION["bookErrors"]["title"] = "Title is Required";
  }

  if ($author === "") {
    $_SESSION["bookErrors"]["author"] = "Author is Required";
  }elseif (!preg_match('/[a-zA-Z]/', $author)) {
    $_SESSION["bookErrors"]["author"] = "Author must contain letters";
  } 

  if ($category === "") {
    $_SESSION["bookErrors"]["category"] = "Category is Required";
  }

  if ($publication_year === "") {
    $_SESSION["bookErrors"]["publication_year"] = "Publication Year is Required";
  }elseif ((int) $publication_year <= 0) {
    $_SESSION["bookErrors"]["publication_year"] = "Invalid Year";
  } elseif ((int) $publication_year < 1000) {
    $_SESSION["bookErrors"]["publication_year"] = "Invalid Year";
  } elseif ((int) $publication_year > (int) date("Y")) {
    $_SESSION["bookErrors"]["publication_year"] = "Publication Year Cannot Be In The Future";
  }

  if ($quantity === "") {
    $_SESSION["bookErrors"]["quantity"] = "Quantity is Required";
  }elseif ((int) $quantity <= 0) {
    $_SESSION["bookErrors"]["quantity"] = "Invalid Quantity";
  }

  return empty($_SESSION["bookErrors"]);
}