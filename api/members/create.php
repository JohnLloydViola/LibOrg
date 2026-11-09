<?php
declare(strict_types=1);

header("Content-Type: application/json");

require_once __DIR__ . "/../../vendor/autoload.php";
require_once __DIR__ . "/../../config/Database.php";

if($_SERVER["REQUEST_METHOD"] !== "POST") {
  http_response_code(405);
  echo json_encode(["message" => "Method Not Allowed"]);
  exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (!is_array($data)) {
  http_response_code(400);
  echo json_encode(["message" => "Invalid JSON data"]);
  exit;
}


// Reject non-string values (numbers, arrays, objects, booleans) before trim() sees them para hindi mag crash
$typeErrors = [];

foreach (["full_name", "email", "password", "phone_number", "address"] as $field) {
  if (isset($data[$field]) && !is_string($data[$field])) {
    $typeErrors[$field] = "Invalid Value";
  }
}

if (!empty($typeErrors)) {
  http_response_code(422);
  echo json_encode(["errors" => $typeErrors]);
  exit;
}

$full_name = trim($data["full_name"] ?? "");
$email = trim($data["email"] ?? "");
$password = $data["password"] ?? "";
$phone_number = trim($data["phone_number"] ?? "");
$address = trim($data["address"] ?? "");

$errors = validateMember(
  $full_name,
  $email,
  $password,
  $phone_number,
  $address
);

if (!empty($errors)) {
  http_response_code(422);
  echo json_encode([
    "errors" => $errors
  ]);

  exit;
}

//Gamit ang member class
$member = new App\Member(
  $full_name,
  $email,
  $phone_number,
  $address
);

try {
  addMember(
    $conn,
    $member->getFullName(),
    $member->getEmail(),
    password_hash($password, PASSWORD_DEFAULT),
    $member->getPhoneNumber(),
    $member->getAddress()
  );

  http_response_code(201);
  echo json_encode([
    "message" => 'Member "' . $member->getFullName() . '" added successfully.'
  ]);
} catch (PDOException $e) {
  
  if ($e->getCode() === "23000") {
    http_response_code(409);
    echo json_encode(["message" => "Email already exists."]);
    exit;
  }

  http_response_code(500);
  echo json_encode([
    "message" => "Failed to add member."
  ]);
  exit;
}

function addMember(
  PDO $conn,
  string $full_name,
  string $email,
  string $password_hash,
  string $phone_number,
  string $address
): void 
{
  $sql = "INSERT 
          INTO members (full_name, email, password_hash, phone_number, address, role)
          VALUES (:full_name, :email, :password_hash, :phone_number, :address, 'member')";
  
  $stmt = $conn->prepare($sql);

  $stmt->execute([
    ":full_name" => $full_name,
    ":email" => $email,
    ":password_hash" => $password_hash,
    ":phone_number" => $phone_number,
    ":address" => $address
  ]);
}

function validateMember(
  string $full_name,
  string $email,
  string $password,
  string $phone_number,
  string $address
): array
{
  $errors = [];

  // Full Name
  if ($full_name === "") {
    $errors["full_name"] = "Full Name Is Required";
  } elseif (mb_strlen($full_name) < 2 || mb_strlen($full_name) > 100) {
    $errors["full_name"] = "Full Name must be 2 to 100 characters";
  } elseif (!preg_match("/^\p{L}[\p{L}\p{M} .'-]*$/u", $full_name)) {
    $errors["full_name"] = "Full Name contains invalid characters";
  }

  if ($email === "") {
    $errors["email"] = "Email Is Required";
  } elseif (strlen($email) > 255) {
    $errors["email"] = "Email must not exceed 255 characters";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors["email"] = "Invalid Email";
  }

  if (trim($password) === "") {
    $errors["password"] = "Password is required";
  } elseif (strlen($password) < 8) {
    $errors["password"] = "Password must be at least 8 characters";
  } elseif (strlen($password) > 64) {
    $errors["password"] = "Password must not exceed 64 characters";
  }

  if ($phone_number === "") {
    $errors["phone_number"] = "Phone Number Is Required";
  } elseif (!preg_match('/^09\d{9}$/', $phone_number)) {
    $errors["phone_number"] = "Invalid Philippine Phone Number";
  }

  // Address
  if ($address === "") {
    $errors["address"] = "Address Is Required";
  } elseif (mb_strlen($address) < 5 || mb_strlen($address) > 255) {
    $errors["address"] = "Address must be 5 to 255 characters";
  } elseif (preg_match('/[\x00-\x1F\x7F]/', $address)) {
    $errors["address"] = "Address contains invalid characters";
  }

  return $errors;
}