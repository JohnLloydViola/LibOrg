<?php
declare(strict_types=1);

require_once __DIR__ . "/../../config/Database.php";

if($_SERVER["REQUEST_METHOD"] !== "POST") {
  header("Location: ../../pages/MembersPage.php");
  exit;
}

$id = (int) ($_POST["id"] ?? "");
$full_name = trim($_POST["full_name"] ?? "");
$email = trim($_POST["email"] ?? "");
$phone_number = trim($_POST["phone_number"] ?? "");
$address = trim($_POST["address"] ?? "");

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
}