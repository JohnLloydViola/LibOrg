<?php
declare(strict_types=1);

require_once __DIR__ . "/../../config/Database.php";

function getTotalBooks(PDO $conn): int
{
  $sql = "SELECT COUNT(*)
          FROM books";

  $stmt = $conn->query($sql);

  return (int) $stmt->fetchColumn();
}

function getTotalMembers(PDO $conn): int
{
  $sql = "SELECT COUNT(*)
          FROM members";

  $stmt = $conn->query($sql);

  return (int) $stmt->fetchColumn();
}

function getTotalBorrowed(PDO $conn): int
{
  $sql = "SELECT COUNT(*)
          FROM transactions
          WHERE status = :status";

  $stmt = $conn->prepare($sql);

  $stmt->execute([
    ":status" => "borrowed"
  ]);

  return (int) $stmt->fetchColumn();
}