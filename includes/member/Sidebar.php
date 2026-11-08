<?php 
declare(strict_types=1);

$currentPage = basename($_SERVER["PHP_SELF"]);
?>

<div class="sidebar" id="sidebar">
  <div class="container">
    <div class="system-title-sidebar">
      <div>
        <img src="../../assets/images/Logo.svg" class="title-logo" alt="Logo">
      </div>

      <div>
        <h1 class="system-title">Library <br> Organizer</h1> 
      </div>
    </div>

    <div class="navigation-links">
      <a href="../../pages/member/BrowseBooksPage.php" class="<?= $currentPage === "BrowseBooksPage.php" ? "active" : "" ?>"><img src="../../assets/images/Dashboard.svg" alt="Browse books">
        Browse Books
      </a>

      <a href="../../pages/member/BorrowedBooksPage.php" class="<?= $currentPage === "BorrowedBooksPage.php" ? "active" : "" ?>"><img src="../../assets/images/Book.svg" alt="Books">
        Borrowed Books
      </a>
    </div>
  </div>

  <div class="logout">
      <form id="logout-form-member">
          <button type="submit" class="logout-button">
              Logout
          </button>
      </form>
  </div>
</div>  