<?php 
declare(strict_types=1);

// Login check ito
require_once __DIR__ . "/../../config/session.php";


if (!isset($_SESSION["member_id"]) || ($_SESSION["role"] ?? "") !== "admin") {
    header("Location: ./LoginPage.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Books</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="icon" href="../assets/images/Book.svg">
</head>
<body>
  <div class="book-container">
    <?php require_once __DIR__ . "/../includes/Sidebar.php"; ?>

    <div class="content">
      <?php require_once __DIR__ . "/../includes/MenuButton.php"; ?>

      <p class="success" id="book-success" hidden></p>

      <p class="deleted" id="book-failed" hidden></p>
      
      <div class="add-book">
        <h1>Books</h1>
        <button id="open-add-book-modal-btn">+ Add Book</button>
      </div>

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

            <select id="book-sort" name="sort"> 
              <option value="" selected>Sort</option>
              <option value="asc">Ascending</option>
              <option value="desc">Descending</option>
            </select>
          </div>
        </div>

        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th style="width: 30%;">TITLE</th>
              <th style="width: 25%;">AUTHOR</th>
              <th>CATEGORY</th>
              <th>YEAR</th>
              <th>QUANTITY</th>
              <th>AVAILABLE</th>
              <th class="table-action">ACTION</th>
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

  <!--Modal dialogue para sa add book button-->
  <dialog id="add-book-modal"> 
    <div><h1>ADD BOOK</h1> </div>

    <form class="modal-information" id="add-book-form">
      <div class="modal-input"> 
        <div class="input-label"> 
          <label for="title">Title</label><br>
          <p class="error" id="title-error"></p>
        </div>

        <input type="text" id="title" name="title" placeholder="Enter Book title" minlength="2" maxlength="150" required><br>
      </div>
      
      <div class="modal-input"> 
        <div class="input-label"> 
           <label for="author">Author</label><br>
           <p class="error" id="author-error"></p>
        </div>

        <input type="text" id="author" name="author" placeholder="Enter author" minlength="2" maxlength="100" required><br>
      </div>
  
      <div class="modal-input">
        <div class="input-label"> 
          <label for="category">Category</label><br>
          <p class="error" id="category-error"></p>
        </div> 

          <select id="category" name="category" class="modal-category" required> 
            <option selected value="" disabled>Select Category</option>
            <option value="science">Science</option>
            <option value="technology">Technology</option>
            <option value="fantasy">Fantasy</option>
            <option value="romance">Romance</option>
            <option value="education">Education</option>
            <option value="business">Business</option>
            <option value="health">Health</option>
        </select><br>
      </div>

      <div class="modal-input"> 
        <div class="input-label"> 
          <label for="year">Year</label><br>
          <p class="error" id="year-error"></p>
        </div>
    
        <input type="number" id="year" name="publication_year" placeholder="Enter publication year" min="1000" max="<?= date('Y') ?>" step="1" required><br>
      </div>
    
      <div class="modal-input"> 
        <div class="input-label"> 
          <label for="quantity">Quantity</label><br>
          <p class="error" id="quantity-error"></p>
        </div>
        
        <input type="number" id="quantity" name="quantity" placeholder="Enter quantity" min="1" max="999" step="1" required><br>
      </div>
      
      <div> 
        <button class="modal-submit" type="submit">Add Book</button>
        <button class="modal-cancel" type="button" id="close-add-book-modal-btn">Cancel</button>
      </div>
    </form>
  </dialog>

  <!--Modal dialogue para sa Edit book button-->
<dialog id="edit-book-modal"> 
  <div><h1>EDIT BOOK</h1> </div>
  <form class="modal-information" id="edit-book-form">
    <input type="hidden" id="edit-id" name="id">

    <div class="modal-input"> 
      <div class="input-label"> 
        <label for="edit-title">Title</label><br>

        <p class="error" id="edit-title-error"></p>
      </div>

      <input type="text" id="edit-title" name="title" placeholder="Enter Book title" minlength="2" maxlength="150" required><br>
    </div>

    <div class="modal-input"> 
      <div class="input-label"> 
        <label for="edit-author">Author</label><br>

        <p class="error" id="edit-author-error"></p>
      </div>

      <input type="text" id="edit-author" name="author" placeholder="Enter author" minlength="2" maxlength="100" required><br>
    </div>

    <div class="modal-input">
      <div class="input-label"> 
        <label for="edit-category">Category</label><br>

        <p class="error" id="edit-category-error"></p>
      </div>

      <select id="edit-category" name="category" class="modal-category" required> 
        <option value="science">Science</option>
        <option value="technology">Technology</option>
        <option value="fantasy">Fantasy</option>
        <option value="romance">Romance</option>
        <option value="education">Education</option>
        <option value="business">Business</option>
        <option value="health">Health</option>
      </select><br>
    </div>

    <div class="modal-input"> 
      <div class="input-label"> 
        <label for="edit-year">Year</label><br>

        <p class="error" id="edit-year-error"></p>
      </div>

      <input type="number" id="edit-year" name="publication_year" placeholder="Enter publication year" min="1000" max="<?= date('Y') ?>" step="1" required><br>
    </div>

    <div class="modal-input"> 
      <div class="input-label"> 
        <label for="edit-quantity">Quantity</label><br>

        <p class="error" id="edit-quantity-error"></p>
      </div>

      <input type="number" id="edit-quantity" name="quantity" placeholder="Enter quantity" min="1" max="999" step="1" required><br>
    </div>

    <div> 
      <button class="modal-submit" type="submit">Save Book</button>
      <button class="modal-cancel" type="button" id="close-edit-book-modal-btn"> cancel</button>
    </div>
  </form>
</dialog>

  <script src="../assets/js/axios.min.js"></script>
  <script src="../assets/js/api.js"></script>
  <script src="../assets/js/BooksPage.js"></script>
  <script src="../assets/js/Logout.js"></script>
  <script src="../assets/js/Menu.js"></script>
</body>
</html>
