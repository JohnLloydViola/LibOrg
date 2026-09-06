<?php 
declare(strict_types=1);

require_once __DIR__ . '/../../config/Database.php';

function getBooks(PDO $conn): array 
{
  $sql = "SELECT * FROM books";

  $stmt = $conn->query($sql);

  return $stmt->fetchAll();
}