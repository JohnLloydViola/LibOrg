<?php 
declare(strict_types=1);

require_once __DIR__ . "/../../config/Database.php";

header("Content-Type: application/json");

//Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(["message" => "Method not allowed"]);
  exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$id = (int) ($data['id'] ?? '');
$title = trim($data['title'] ?? '');
$author = trim($data['author'] ?? '');
$category = trim($data['category'] ?? '');
$publication_year = trim($data['publication_year'] ?? '');
$quantity = trim($data['quantity'] ?? '');

if (!validateBook($title, $author, $category, $publication_year, $quantity)) {
  http_response_code(422);
  echo json_encode([
    "message" => "Edit Failed",
    "errors" => $errors ?? []
  ]);
  exit;
}

$publication_year = (int) $publication_year;
$quantity = (int) $quantity;

$updated = updateBook(
  $conn,
  $id,
  $title,
  $author,
  $category,
  $publication_year,
  $quantity
);

if ($updated) {
  echo json_encode([
    "message" => 'ID: ' . $id . ' Edited Successfully'
  ]);
}

function updateBook(
  PDO $conn,
  int $id,
  string $title,
  string $author,
  string $category,
  int $publication_year,
  int $quantity
): bool 
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

  if (!$book) {
    http_response_code(404);
    echo json_encode([
      "message" => "Failed to Edit book",
      "errors" => [
        "id" => "Book not found"
      ]
    ]);
    return false;
  }

  // Bilangin kung ilang copies ang na borrowed
  $borrowedQuantity = $book['quantity'] - $book['available_quantity'];

  // wag payagan ang bagong quantity na mas mababa sa borrowed copies
  if ($quantity < $borrowedQuantity) {
    http_response_code(422);
    echo json_encode([
      "message" => "Failed to Edit book",
      "errors" => [
        "quantity" => "New Quantity should not be lower than the borrowed quantity"
      ]
    ]);
    return false;
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

  return true;
}

function validateBook(
  string $title,
  string $author,
  string $category,
  string $publication_year,
  string $quantity
): bool
{
  global $errors;
  $errors = [];

  if ($title === "") {
    $errors["title"] = "Title is Required";
  }

  if ($author === "") {
    $errors["author"] = "Author is Required";
  } elseif (!preg_match('/[a-zA-Z]/', $author)) {
    $errors["author"] = "Author must contain letters";
  } 

  if ($category === "") {
    $errors["category"] = "Category is Required";
  }

  if ($publication_year === "") {
    $errors["publication_year"] = "Publication Year is Required";
  } elseif ((int) $publication_year <= 0) {
    $errors["publication_year"] = "Invalid Year";
  } elseif ((int) $publication_year < 1000) {
    $errors["publication_year"] = "Invalid Year";
  } elseif ((int) $publication_year > (int) date("Y")) {
    $errors["publication_year"] = "Publication Year Cannot Be In The Future";
  }

  if ($quantity === "") {
    $errors["quantity"] = "Quantity is Required";
  } elseif ((int) $quantity <= 0) {
    $errors["quantity"] = "Invalid Quantity";
  }

  return empty($errors);
}