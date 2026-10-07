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
$phone_number = trim($data["phone_number"] ?? "");
$address = trim($data["address"] ?? "");

$errors = validateMember($full_name, $email, $phone_number, $address);

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
    $member->getPhoneNumber(),
    $member->getAddress()
  );

  http_response_code(201);
  echo json_encode([
    "message" => 'Member "' . $member->getFullName() . '" added successfully.'
  ]);
} catch (PDOException $e) {
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
  string $phone_number,
  string $address
): void 
{
  $sql = "INSERT 
          INTO members (full_name, email, phone_number, address)
          VALUES (:full_name, :email, :phone_number, :address)";
  
  $stmt = $conn->prepare($sql);

  $stmt->execute([
    ":full_name" => $full_name,
    ":email" => $email,
    ":phone_number" => $phone_number,
    ":address" => $address
  ]);
}

function validateMember(
  string $full_name,
  string $email,
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