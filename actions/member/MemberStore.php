<?php
declare(strict_types=1);

session_start();

require_once __DIR__ . "/../../vendor/autoload.php";
require_once __DIR__ . "/../../config/Database.php";

if($_SERVER["REQUEST_METHOD"] !== "POST") {
  header("Location: ../../pages/MembersPage.php");
  exit;
}

unset($_SESSION["memberErrors"]);

$full_name = trim($_POST["full_name"] ?? "");
$email = trim($_POST["email"] ?? "");
$phone_number = trim($_POST["phone_number"] ?? "");
$address = trim($_POST["address"] ?? "");

if (!validateMember($full_name, $email, $phone_number, $address)) {
  header("Location: ../../pages/MembersPage.php");
  exit;
}

//Gamit ang member class
$member = new App\Member(
  $full_name,
  $email,
  $phone_number,
  $address
);

addMember(
  $conn,
  $member->getFullName(),
  $member->getEmail(),
  $member->getPhoneNumber(),
  $member->getAddress()
);

header("Location: ../../pages/MembersPage.php");
exit();

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

  $_SESSION["memberSuccess"] = 'Member "' . $full_name . '" Added Successfully.';
}

function validateMember (
  string $full_name,
  string $email,
  string $phone_number,
  string $address
): bool
{
  if ($full_name === "") {
    $_SESSION["memberErrors"]["full_name"] = "Full Name Is Required";
  }

  if ($email === "") {
    $_SESSION["memberErrors"]["email"] = "Email Is Required";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION["memberErrors"]["email"] = "Invalid Email";
  } 

  if ($phone_number === "") {
    $_SESSION["memberErrors"]["phone_number"] = "Phone Number Is Required";
  } elseif (!preg_match('/^09\d{9}$/', $phone_number)) {
    $_SESSION["memberErrors"]["phone_number"] = "Invalid Philippine Phone Number";
  }

  if ($address === "") {
    $_SESSION["memberErrors"]["address"] = "Address Is Required";
  } 

  return empty($_SESSION["memberErrors"]);
}