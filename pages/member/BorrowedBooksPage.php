<?php 
declare(strict_types=1);

// Login check ito
require_once __DIR__ . "/../../config/session.php";

if (!isset($_SESSION["member_id"]) || ($_SESSION["role"] ?? "") !== "member") {
    header("Location: ../LoginPage.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Borrowed Books</title>
  <link rel="stylesheet" href="../../assets/css/style.css">
  <link rel="icon" href="../../assets/images/Book.svg" type="image/x-icon">
</head>
<body>

  <div class="borrowed-book-container">
    <?php require_once __DIR__ .  "/../../includes/member/Sidebar.php"; ?>

    <div class="content"> 
      <?php require_once __DIR__ . "/../../includes/MenuButton.php"; ?>

      <p class="deleted" id="borrowed-failed" hidden></p>
      
      <div class="table-section">
        <div class="filter"> 
          <div>
            <input id="book-search" name="bookName" type="text" placeholder="Search By Title...">
          </div>
         
          <div>
            <select id="book-category" name="categories"> 
              <option value="" selected>All categories</option>
              <option value="science">Science</option>
              <option value="technology">Technology</option>
              <option value="fantasy">Fantasy</option>
              <option value="romance">Romance</option>
              <option value="education">Education</option>
              <option value="business">Business</option>
              <option value="health">Health</option>
            </select>
          </div>
        </div>

        <table>
          <thead>
            <tr>
              <th style="width: 35%;">TITLE</th>
              <th style="width: 35%;">AUTHOR</th>
              <th>CATEGORY</th>
              <th>YEAR</th>
            </tr>
          </thead>

          <tbody id="books-table-body"> 

          </tbody>  
        </table>

        <div class="entries"> 
          <div> 
            <p id="book-entries">Showing 0 to 0 of 0 entries</p>
          </div>

          <nav>
            <ul class="pagination" id="book-pagination">
              <li><a href="#" class="prev">&laquo; Prev</a></li>

              <li><a href="#" class="next">Next &raquo;</a></li>
            </ul>
          </nav>
        </div>

      </div>
    </div>
    
  </div>
  
  <script src="../../assets/js/axios.min.js"></script>
  <script src="../../assets/js/api.js"></script>
  <script src="../../assets/js/member/BorrowedBooksPage.js"></script>
  <script src="../../assets/js/member/Logout.js"></script>
  <script src="../../assets/js/member/Menu.js"></script>
</body>
</html>