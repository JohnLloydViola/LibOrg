<?php
declare(strict_types=1);

require_once __DIR__ . "/../../config/Database.php";

if($_SERVER["REQUEST_METHOD"] !== "POST") {
  header("Location: ../../pages/MembersPage.php");
  exit;
}

$id = (int) ($_POST["id"] ?? "");

deleteMember($conn, $id);

header("Location: ../../pages/MembersPage.php");
exit;

function deleteMember(
  PDO $conn,
  int $id
): void
{
  //check din kung merong naka reference dito na transaction then return do not delete.
  $sql = "SELECT id
          FROM transactions
          WHERE member_id = :id";

  $stmt = $conn->prepare($sql);

  $stmt->execute([
    ":id" => $id
  ]);

  if ($stmt->fetch()) {
    return;
  }

  $sql = "DELETE 
          FROM members
          WHERE id = :id";

  $stmt = $conn->prepare($sql);

  $stmt->execute([
    ":id" => $id
  ]);
}