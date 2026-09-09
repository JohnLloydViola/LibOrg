<?php
declare(strict_types=1);

require_once __DIR__ . "/../../vendor/autoload.php";
require_once __DIR__ . "/../../config/Database.php";

if($_SERVER["REQUEST_METHOD"] !== "POST") {
  header("Location: ../../pages/MembersPage.php");
  exit;
}

$full_name = trim($_POST["full_name"] ?? "");
$email = trim($_POST["email"] ?? "");
$phone_number = trim($_POST["phone_number"] ?? "");
$address = trim($_POST["address"] ?? "");


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
}