<?php
declare(strict_types=1);

require_once __DIR__ . "/../../config/Database.php";

session_start();

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
  $memberName = getMemberName($conn, $id);
  //check din kung merong naka reference dito na transaction then return do not delete.
  $sql = "SELECT id
          FROM transactions
          WHERE member_id = :id";

  $stmt = $conn->prepare($sql);

  $stmt->execute([
    ":id" => $id
  ]);

  if ($stmt->fetch()) {
    $_SESSION["memberDeleteUnsuccessful"] = 'Cannot Delete "' . $memberName . '" This Member Has Existing Transaction Record';
    return;
  }

  $sql = "DELETE 
          FROM members
          WHERE id = :id";

  $stmt = $conn->prepare($sql);

  $stmt->execute([
    ":id" => $id
  ]);

  $_SESSION["memberDeleteSuccess"] = 'Member "' . $memberName . '" Deleted Successfully.';
}

function getMemberName(
  PDO $conn,
  int $id
): string
{
  $sql = "SELECT *
          FROM members
          WHERE id = :id";
  
  $stmt = $conn->prepare($sql);

  $stmt->execute([
    ":id" => $id
  ]);

  $member = $stmt->fetch();

  return $member["full_name"];
}