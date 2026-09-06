<?php
declare(strict_types=1);

require_once __DIR__ . "/../../config/Database.php";

function getMembers(PDO $conn):array 
{
  $sql = "SELECT * FROM members";

  $stmt = $conn->query($sql);

  return $stmt->fetchAll();
}
