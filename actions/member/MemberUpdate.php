<?php
declare(strict_types=1);

require_once __DIR__ . "/../../config/Database.php";

session_start();

unset($_SESSION["editMemberErrors"]);

if($_SERVER["REQUEST_METHOD"] !== "POST") {
  header("Location: ../../pages/MembersPage.php");
  exit;
}

$id = (int) ($_POST["id"] ?? "");
$full_name = trim($_POST["full_name"] ?? "");
$email = trim($_POST["email"] ?? "");
$phone_number = trim($_POST["phone_number"] ?? "");
$address = trim($_POST["address"] ?? "");

if (!validateMember($full_name, $email, $phone_number, $address)) {
  $_SESSION["editMemberUnsuccessful"] = 'ID: ' . $id . ' Edit Failed';

  header("Location: ../../pages/MembersPage.php");
  exit();
}

updateMember($conn, $id, $full_name, $email, $phone_number, $address);

header("Location: ../../pages/MembersPage.php");
exit;

function updateMember(
  PDO $conn,
  int $id,
  string $full_name,
  string $email,
  string $phone_number,
  string $address
): void 
{
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

  $_SESSION["editMemberSuccessful"] = 'ID: ' . $id . ' Edited Successfully';
}

function validateMember(
  string $full_name,
  string $email,
  string $phone_number,
  string $address
): bool 
{
  if ($full_name === "") {
    $_SESSION["editMemberErrors"]["full_name"] = "Full Name Is Required";
  }

  if ($email === "") {
    $_SESSION["editMemberErrors"]["email"] = "Email Is Required";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION["editMemberErrors"]["email"] = "Invalid Email";
  } 

  if ($phone_number === "") {
    $_SESSION["editMemberErrors"]["phone_number"] = "Phone Number Is Required";
  } elseif (!preg_match('/^09\d{9}$/', $phone_number)) {
    $_SESSION["editMemberErrors"]["phone_number"] = "Invalid Philippine Phone Number";
  }

  if ($address === "") {
    $_SESSION["editMemberErrors"]["address"] = "Address Is Required";
  } 

  return empty($_SESSION["editMemberErrors"]);
}