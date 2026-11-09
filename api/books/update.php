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

$title = trim((string) ($data['title'] ?? ''));
$author = trim((string) ($data['author'] ?? ''));
$category = trim((string) ($data['category'] ?? ''));
$publication_year = trim((string) ($data['publication_year'] ?? ''));
$quantity = trim((string) ($data['quantity'] ?? ''));

$id = filter_var($data['id'] ?? null, FILTER_VALIDATE_INT);

if ($id === false || $id === null || $id <= 0) {
  http_response_code(422);
  echo json_encode([
    "message" => "Edit Failed",
    "errors" => [
      "id" => "Invalid Book ID"
    ]
  ]);
  exit;
}

if (!validateBook($title, $author, $category, $publication_year, $quantity)) {
  http_response_code(422);
  echo json_encode([
    "errors" => $errors ?? []
  ]);
  exit;
}

$publication_year = (int) $publication_year;
$quantity = (int) $quantity;

try {
  $updated = updateBook($conn, $id, $title, $author, $category, $publication_year, $quantity);
} catch (PDOException $e) {
  http_response_code(500);
  echo json_encode(["message" => "Failed to edit book"]);
  exit;
}

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
  $conn->beginTransaction();

  try {
    // Lock the row so a borrow can't change the counts mid-edit
    $stmt = $conn->prepare("SELECT quantity, available_quantity FROM books WHERE id = :id FOR UPDATE");
    $stmt->execute([':id' => $id]);
    $book = $stmt->fetch();

    if (!$book) {
      $conn->rollBack();
      http_response_code(404);
      echo json_encode([
        "message" => "Failed to Edit book",
        "errors" => ["id" => "Book not found"]
      ]);
      return false;
    }

    $borrowedQuantity = $book['quantity'] - $book['available_quantity'];

    if ($quantity < $borrowedQuantity) {
      $conn->rollBack();
      http_response_code(422);
      echo json_encode([
        "message" => "Failed to Edit book",
        "errors" => [
          "quantity" => "New Quantity should not be lower than the borrowed quantity"
        ]
      ]);
      return false;
    }

    $stmt = $conn->prepare(
      "UPDATE books 
       SET title = :title, author = :author, category = :category,
           publication_year = :publication_year, quantity = :quantity,
           available_quantity = :available_quantity
       WHERE id = :id"
    );

    $stmt->execute([
      ':title' => $title,
      ':author' => $author,
      ':category' => $category,
      ':publication_year' => $publication_year,
      ':quantity' => $quantity,
      ':available_quantity' => $quantity - $borrowedQuantity,
      ':id' => $id
    ]);

    $conn->commit();
    return true;
  } catch (PDOException $e) {
    if ($conn->inTransaction()) {
      $conn->rollBack();
    }
    throw $e;
  }
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

  $allowedCategories = [
    "science",
    "technology",
    "fantasy",
    "romance",
    "education",
    "business",
    "health"
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

  // Publication Year
  if ($publication_year === "") {
    $errors["publication_year"] = "Publication Year is Required";
  } elseif (!ctype_digit($publication_year)) {
    $errors["publication_year"] = "Invalid Year";
  } elseif ((int) $publication_year < 1000) {
    $errors["publication_year"] = "Invalid Year";
  } elseif ((int) $publication_year > (int) date("Y")) {
    $errors["publication_year"] = "Publication Year Cannot Be In The Future";
  }

  // Quantity
  if ($quantity === "") {
    $errors["quantity"] = "Quantity is Required";
  } elseif (!ctype_digit($quantity)) {
    $errors["quantity"] = "Invalid Quantity";
  } elseif ((int) $quantity < 1 || (int) $quantity > 999) {
    $errors["quantity"] = "Quantity Must Be Between 1 And 999";
  }

  return empty($errors);
}