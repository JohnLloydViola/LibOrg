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
  $sql = "DELETE 
          FROM members
          WHERE id = :id";

  $stmt = $conn->prepare($sql);

  $stmt->execute([
    ":id" => $id
  ]);
}