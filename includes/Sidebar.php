<?php 
declare(strict_types=1);

$currentPage = basename($_SERVER["PHP_SELF"]);
?>

<div class="sidebar">
  <div class="container">
    <div class="system-title-sidebar">
      <div>
        <img src="../assets/images/Logo.svg" class="title-logo" alt="Logo">
      </div>

      <div>
        <h1 class="system-title">Library <br> Organizer</h1> 
      </div>
    </div>

    <div class="navigation-links">
      <a href="../pages/DashboardPage.php" class="<?= $currentPage === "DashboardPage.php" ? "active" : "" ?>"><img src="../assets/images/Dashboard.svg" alt="Dashboard">
        Dashboard
      </a>

      <a href="../pages/BooksPage.php" class="<?= $currentPage === "BooksPage.php" ? "active" : "" ?>"><img src="../assets/images/Book.svg" alt="Books">
        Books
      </a>

      <a href="../pages/MembersPage.php" class="<?= $currentPage === "MembersPage.php" ? "active" : "" ?>"><img src="../assets/images/Members.svg" alt="Members">
        Members
      </a>

      <a href="../pages/TransactionsPage.php" class="<?= $currentPage === "TransactionsPage.php" ? "active" : "" ?>"><img src="../assets/images/Transactions.svg" alt="Transactions">
        Transactions
      </a> 
    </div>
  </div>
</div>  