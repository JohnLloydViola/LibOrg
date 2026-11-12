<?php
declare(strict_types=1);

header("Content-Type: application/json");

require_once __DIR__ . "/../../config/Database.php";
require_once __DIR__ . "/../../config/session.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  http_response_code(405);
  echo json_encode(["message" => "Method Not Allowed"]);
  exit;
}

verify_csrf_token();

$data = json_decode(file_get_contents("php://input"), true);

if (!is_array($data)) {
  http_response_code(400);
  echo json_encode(["message" => "Invalid JSON data"]);
  exit;
}

// Reject non-string values (numbers, arrays, objects, booleans) before trim() sees them para hindi mag crash yung website
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

$id = filter_var($data["id"] ?? null, FILTER_VALIDATE_INT);

if ($id === false || $id === null || $id <= 0) {
  http_response_code(422);
  echo json_encode([
    "errors" => ["id" => "Invalid Member ID"]
  ]);
  exit;
}

$full_name = trim($data["full_name"] ?? "");
$email = trim($data["email"] ?? "");
$password = $data["password"] ?? "";
$phone_number = trim($data["phone_number"] ?? "");
$address = trim($data["address"] ?? "");

$errors = validateMember($full_name, $email, $phone_number, $address, $password);

if (!empty($errors)) {
  http_response_code(422);
  echo json_encode([
    "errors" => $errors
  ]);
  exit;
}

try {
  updateMember($conn, $id, $full_name, $email, $phone_number, $address, $password);

  http_response_code(200);
  echo json_encode([
    "message" => 'ID: ' . $id . ' Edited Successfully'
  ]);
} catch (PDOException $e) {
  if ($e->getCode() === "23000") {
    http_response_code(409);
    echo json_encode(["message" => "Email already exists."]);
    exit;
  }

  http_response_code(500);
  echo json_encode([
    "message" => "Failed to update member."
  ]);

  exit;
}


function updateMember(
  PDO $conn,
  int $id,
  string $full_name,
  string $email,
  string $phone_number,
  string $address,
  string $password
): void
{
  // Update password only when a new password is provided
  if ($password !== "") {
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $sql = "UPDATE members
            SET full_name = :full_name, email = :email, password_hash = :password_hash,
                phone_number = :phone_number, address = :address
            WHERE id = :id";

    $stmt = $conn->prepare($sql);

    $stmt->execute([
      ":full_name" => $full_name,
      ":email" => $email,
      ":password_hash" => $password_hash,
      ":phone_number" => $phone_number,
      ":address" => $address,
      ":id" => $id
    ]);
  } else {
    $sql = "UPDATE members
            SET full_name = :full_name, email = :email, phone_number = :phone_number, address = :address
            WHERE id = :id";

    $stmt = $conn->prepare($sql);

    $stmt->execute([
      ":full_name" => $full_name,
      ":email" => $email,
      ":phone_number" => $phone_number,
      ":address" => $address,
      ":id" => $id
    ]);
  }

  // Check if the member exists when no rows were changed. pang validate lang about sa id if it exist
  if ($stmt->rowCount() === 0) {
    $check = $conn->prepare("SELECT id FROM members WHERE id = :id");
    $check->execute([":id" => $id]);

    if (!$check->fetch()) {
      http_response_code(404);
      echo json_encode([
        "message" => "Member not found."
      ]);
      exit;
    }
  }
}

function validateMember(
  string $full_name,
  string $email,
  string $phone_number,
  string $address,
  string $password
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

  // Email
  if ($email === "") {
    $errors["email"] = "Email Is Required";
  } elseif (strlen($email) > 255) {
    $errors["email"] = "Email must not exceed 255 characters";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors["email"] = "Invalid Email";
  }

  // Password 
  if ($password !== "") {
    if (trim($password) === "") {
      $errors["password"] = "Password cannot contain only spaces";
    } elseif (strlen($password) < 8) {
      $errors["password"] = "Password must be at least 8 characters";
    } elseif (strlen($password) > 64) {
      $errors["password"] = "Password must not exceed 64 characters";
    }
  }

  // Phone
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