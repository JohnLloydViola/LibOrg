<?php 
declare(strict_types=1);

header("Content-Type: application/json");

require_once __DIR__ . "/../../config/Database.php"; 

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  http_response_code(405);
  echo json_encode(["message" => "Method not allowed"]);
  exit;
}

//Get submitted data from form darta
$data = json_decode(file_get_contents("php://input"), true);

if (!is_array($data)) {
  http_response_code(400);
  echo json_encode([
    "message" => "Invalid JSON data"
  ]);
  exit;
}

// Reject non-string values (numbers, arrays, objects, booleans) before they get cast para hindi amg crash website
$typeErrors = [];

foreach (["title", "author", "category", "publication_year", "quantity"] as $field) {
  if (isset($data[$field]) && !is_string($data[$field])) {
    $typeErrors[$field] = "Invalid Value";
  }
}

if (!empty($typeErrors)) {
  http_response_code(422);
  echo json_encode(["errors" => $typeErrors]);
  exit;
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
    "errors" => $errors
  ]);
  exit;
}

$publication_year = (int) $publication_year;
$quantity = (int) $quantity;

try {
  addBook(
    $conn,
    $title,
    $author,
    $category,
    $publication_year,
    $quantity
  );

  http_response_code(201);
  echo json_encode([
    "message" => 'Book "' . $title . '" Added Successfully.'
  ]);
} catch (PDOException $error) {
  http_response_code(500);
  echo json_encode(["message" => "Failed To Add Book"]);
}

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

  //babalikan ko to laterw apg mag a-add ng laravel
  $allowedCategories = [
    "science", "technology", "fantasy", "romance",
    "education", "business", "health"
  ];

  // Title
  $titleLength = mb_strlen($title);

  if ($title === "") {
    $errors["title"] = "Title is Required";
  } elseif ($titleLength < 2 || $titleLength > 150) {
    $errors["title"] = "Title Must Be 2 To 150 Characters";
  }

  // Author
  $authorLength = mb_strlen($author);

  if ($author === "") {
    $errors["author"] = "Author is Required";
  } elseif ($authorLength < 2 || $authorLength > 100) {
    $errors["author"] = "Author Must Be 2 To 100 Characters";
  } elseif (!preg_match('/\p{L}/u', $author)) {
    $errors["author"] = "Author must contain letters";
  }

  // Category
  if ($category === "") {
    $errors["category"] = "Category is Required";
  } elseif (!in_array($category, $allowedCategories, true)) {
    $errors["category"] = "Invalid Category";
  }

  // Publication year
  if ($publication_year === "") {
    $errors["publication_year"] = "Publication Year is Required";
  } elseif (!ctype_digit($publication_year)) {
    $errors["publication_year"] = "Year Must Be A Whole Number";
  } elseif ((int) $publication_year < 1000) {
    $errors["publication_year"] = "Invalid Year";
  } elseif ((int) $publication_year > (int) date("Y")) {
    $errors["publication_year"] = "Publication Year Cannot Be In The Future";
  }

  // Quantity
  if ($quantity === "") {
    $errors["quantity"] = "Quantity is Required";
  } elseif (!ctype_digit($quantity)) {
    $errors["quantity"] = "Quantity Must Be A Whole Number";
  } elseif ((int) $quantity < 1 || (int) $quantity > 999) {
    $errors["quantity"] = "Quantity Must Be 1 To 999";
  }

  return $errors;
}