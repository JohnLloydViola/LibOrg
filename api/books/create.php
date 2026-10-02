<?php 
declare(strict_types=1);

header("Content-Type: application/json");

require_once __DIR__ . "/../../config/Database.php"; 

//Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  http_response_code(405);
  echo json_encode(["message" => "Method not allowed"]);
  exit;
}

//Get submitted data from JSON or form data
$data = json_decode(file_get_contents("php://input"), true);

if (!is_array($data)) {
  $data = $_POST;
}

$title = trim((string) ($data["title"] ?? ""));
$author = trim((string) ($data["author"] ?? ""));
$category = trim((string) ($data["category"] ?? ""));
$publication_year = trim((string) ($data["publication_year"] ?? ""));
$quantity = trim((string) ($data["quantity"] ?? ""));

//Validate book data
$errors = validateBook($title, $author, $category, $publication_year, $quantity);

if (!empty($errors)) {
  http_response_code(422);
  echo json_encode([
    "message" => "Validation failed",
    "errors" => $errors
  ]);
  exit;
}

$publication_year = (int) $publication_year;
$quantity = (int) $quantity;

try {
  $bookId = addBook(
    $conn,
    $title,
    $author,
    $category,
    $publication_year,
    $quantity
  );

  http_response_code(201);
  echo json_encode([
    "message" => "Book added successfully",
    "id" => $bookId
  ]);
} catch (PDOException $error) {
  http_response_code(500);
  echo json_encode(["message" => "Failed to add book"]);
}

function addBook(
  PDO $conn,
  string $title,
  string $author,
  string $category,
  int $publication_year,
  int $quantity
): int {
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

  return (int) $conn->lastInsertId();
}

function validateBook(
  string $title,
  string $author,
  string $category,
  string $publication_year,
  string $quantity
): array 
{
  $errors = [];

  if ($title === "") {
    $errors["title"] = "Title is Required";
  }

  if ($author === "") {
    $errors["author"] = "Author is Required";
  }elseif (!preg_match('/[a-zA-Z]/', $author)) {
    $errors["author"] = "Author must contain letters";
  } 

  if ($category === "") {
    $errors["category"] = "Category is Required";
  }

  if ($publication_year === "") {
    $errors["publication_year"] = "Publication Year is Required";
  }elseif (!ctype_digit($publication_year) || (int) $publication_year < 1000) {
    $errors["publication_year"] = "Invalid Year";
  } elseif ((int) $publication_year > (int) date("Y")) {
    $errors["publication_year"] = "Publication Year Cannot Be In The Future";
  }

  if ($quantity === "") {
    $errors["quantity"] = "Quantity is Required";
  }elseif (!ctype_digit($quantity) || (int) $quantity <= 0) {
    $errors["quantity"] = "Invalid Quantity";
  }

  return $errors;
}