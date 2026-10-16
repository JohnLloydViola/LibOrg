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
  registerMember(
    $conn,
    $member->getFullName(),
    $member->getEmail(),
    password_hash($password, PASSWORD_DEFAULT),
    $member->getPhoneNumber(),
    $member->getAddress()
  );

  http_response_code(201);
  echo json_encode([
    "message" => 'Registration successful!'
  ]);
} catch (PDOException $e) {
  if ($e->getCode() === "23000") {
    http_response_code(409);
    exit;
  }

  http_response_code(500);
  echo json_encode([
    "message" => "Registration failed."
  ]);
  exit;
}

function registerMember(
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

  if ($full_name === "") {
    $errors["full_name"] = "Full Name Is Required";
  }

  if ($email === "") {
    $errors["email"] = "Email Is Required";
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

  if ($address === "") {
    $errors["address"] = "Address Is Required";
  }

  return $errors;
}