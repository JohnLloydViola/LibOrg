<?php 
declare(strict_types=1);

session_start();

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../actions/book/BookRead.php';

$bookErrors = $_SESSION["bookErrors"] ?? [];

$editBookErrors = $_SESSION["editBookErrors"] ?? [];

$bookSuccess = $_SESSION["bookSuccess"] ?? "";

$bookDeleteSuccess = $_SESSION["bookDeleteSuccess"] ?? "";

$bookDeleteUnsuccessfull = $_SESSION["bookDeleteUnsuccessfull"] ?? "";

$books = getBooks($conn); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="../assets/css/Style.css">
</head>
<body>
  <div class="book-container">
    <?php include "../includes/Sidebar.php"; ?>

    <div class="content">
      <?php if ($bookSuccess !== ""): ?>
        <p class="success"><?=htmlspecialchars($bookSuccess)?></p>

        <?php unset($_SESSION['bookSuccess']) ?>
      <?php endif; ?>

      <?php if ($bookDeleteSuccess !== ""): ?>
        <p class="deleted"><?=htmlspecialchars($bookDeleteSuccess)?></p>

        <?php unset($_SESSION['bookDeleteSuccess']) ?>
      <?php endif; ?>

      <?php if ($bookDeleteUnsuccessfull !== ""): ?>
        <p class="deleted"><?=htmlspecialchars($bookDeleteUnsuccessfull)?></p>

        <?php unset($_SESSION['bookDeleteUnsuccessfull']) ?>
      <?php endif; ?>

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
            <?php foreach($books as $book): ?> 
              <tr>
                <td><?='B'. str_pad((string) $book['id'], 3, "0", STR_PAD_LEFT)?></td>
                <td><?= htmlspecialchars($book['title'])?></td>
                <td><?= htmlspecialchars($book['author'])?></td>
                <td><?=htmlspecialchars($book['category'])?></td>
                <td><?=$book['publication_year']?></td>
                <td><?=$book['quantity']?></td>
                <td><?=$book['available_quantity']?></td>
                <td> 
                  <button 
                    type="button" 
                    class="open-edit-book-modal-btn"
                    data-id="<?=$book['id'] ?>"
                    data-title="<?=htmlspecialchars($book['title'])?>"
                    data-author="<?=htmlspecialchars($book['author'])?>"
                    data-category="<?=htmlspecialchars($book['category'])?>"
                    data-publication_year="<?=$book['publication_year']?>"
                    data-quantity="<?=$book['quantity']?>">

                    <img src="../assets/images/Edit.svg" alt="Edit">
                  </button> 
                  
                  <form action="../actions/book/BookDelete.php" method="POST">
                    <input type="hidden" name="id" value="<?=$book['id']?>">

                    <button type="submit">
                      <img src="../assets/images/Delete.svg" alt="delete">
                    </button>
                  </form>
                </td>
            </tr>
            <?php endforeach ?>
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

    <form class="modal-information" action="../actions/book/BookStore.php" method="POST">
      <div class="modal-input"> 
        <div class="input-label"> 
          <label for="title">Title</label><br>

          <?php if (isset($bookErrors["title"])): ?>
            <p class="error">*<?= htmlspecialchars($bookErrors["title"]) ?></p>
          <?php endif; ?>
        </div>

        <input type="text" id="title" name="title" placeholder="Enter Book title" required><br>
      </div>
      
      <div class="modal-input"> 
        <div class="input-label"> 
           <label for="author">Author</label><br>

            <?php if (isset($bookErrors["author"])): ?>
              <p class="error">*<?= htmlspecialchars($bookErrors["author"]) ?></p>
            <?php endif; ?>
        </div>

        <input type="text" id="author" name="author" placeholder="Enter author" required><br>
      </div>
  
      <div class="modal-input">
        <div class="input-label"> 
          <label for="category">Category</label><br>

          <?php if (isset($bookErrors["category"])): ?>
            <p class="error">*<?= htmlspecialchars($bookErrors["category"]) ?></p>
          <?php endif; ?>
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
            
          <?php if (isset($bookErrors["publication_year"])): ?>
            <p class="error">*<?= htmlspecialchars($bookErrors["publication_year"]) ?></p>
          <?php endif; ?>
        </div>
    
        <input type="number" id="year" name="publication_year" placeholder="Enter publication year" min="1" required><br>
      </div>
    
      <div class="modal-input"> 
        <div class="input-label"> 
          <label for="quantity">Quantity</label><br>

          <?php if (isset($bookErrors["quantity"])): ?>
            <p class="error">*<?= htmlspecialchars($bookErrors["quantity"]) ?></p>
          <?php endif; ?>
        </div>
        
        <input type="number" id="quantity" name="quantity" placeholder="Enter quantity" min="1" required><br>
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
    <form class="modal-information" action="../actions/book/BookUpdate.php" method="POST">
      <input type="hidden" id="edit-id" name="id">

      <div class="modal-input"> 
        <div class="input-label"> 
          <label for="edit-title">Title</label><br>

          <?php if (isset($editBookErrors["title"])): ?>
            <p class="error">*<?= htmlspecialchars($editBookErrors["title"]) ?></p>
          <?php endif; ?>
        </div>
  
        <input type="text" id="edit-title" name="title" placeholder="Enter Book title" required><br>
      </div>
      
      <div class="modal-input"> 
        <div class="input-label"> 
          <label for="edit-author">Author</label><br>

          <?php if (isset($editBookErrors["author"])): ?>
            <p class="error">*<?= htmlspecialchars($editBookErrors["author"]) ?></p>
          <?php endif; ?>
        </div>
       
        <input type="text" id="edit-author" name="author" placeholder="Enter author" required><br>
      </div>
  
      <div class="modal-input"> 
        <div class="input-label"> 
          <label for="edit-category">Category</label><br>

          <?php if (isset($editBookErrors["category"])): ?>
            <p class="error">*<?= htmlspecialchars($editBookErrors["category"]) ?></p>
          <?php endif; ?>
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

          <?php if (isset($editBookErrors["publication_year"])): ?>
            <p class="error">*<?= htmlspecialchars($editBookErrors["publication_year"]) ?></p>
          <?php endif; ?>
        </div>
        
        <input type="number" id="edit-year" name="publication_year" placeholder="Enter publication year" min="1" required><br>
      </div>
    
      <div class="modal-input"> 
        <div class="input-label"> 
          <label for="edit-quantity">Quantity</label><br>

          <?php if (isset($editBookErrors["quantity"])): ?>
            <p class="error">*<?= htmlspecialchars($editBookErrors["quantity"]) ?></p>
          <?php endif; ?>
        </div>
      
        <input type="number" id="edit-quantity" name="quantity" placeholder="Enter quantity" min="1" required><br>
      </div>
      
      <div> 
        <button class="modal-submit" type="submit">Save Book</button>
        <button class="modal-cancel" type="button" id="close-edit-book-modal-btn"> cancel</button>
      </div>
    </form>
  </dialog>

  <script src="../assets/js/axios.min.js"></script>
  <script src="../assets/js/BooksPage.js"></script>
</body>
</html>