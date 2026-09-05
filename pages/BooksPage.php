<?php 
declare(strict_types=1);

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../actions/BookRead.php';

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
      <div class="add-book">
        <h1>Books</h1>
        <button id="open-add-book-modal-btn">Add Book</button>
      </div>
      
      <div class="table-section">
        <div class="filter"> 
          <div>
            <input name="bookName" type="text" placeholder="Search books...">
          </div>
         
          <div>
            <select name="categories"> 
              <option value="" disabled selected hidden>All categories</option>
              <option value="science">Science</option>
              <option value="technology">Technology</option>
              <option value="fantasy">Fantasy</option>
              <option value="romance">Romance</option>
              <option value="education">Education</option>
              <option value="business">Business</option>
              <option value="health">Health</option>
            </select>

            <select name="availability"> 
              <option value="" disabled selected hidden>All availability</option>
              <option value="science">Ascending</option>
              <option value="technology">Descending</option>
            </select>
          </div>
        </div>
        
        <table>
          <tr>
            <th>ID</th>
            <th class="table-title">Title</th>
            <th>Author</th>
            <th>Category</th>
            <th>Year</th>
            <th>Quantity</th>
            <th>Available</th>
            <th class="table-action">Action</th>
          </tr>

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
                <div>
                  <button><img src="../assets/images/Edit.png" alt="Edit"></button> 
                  <button><img src="../assets/images/Delete.png" alt="delete"></button> 
                </div>
              </td>
          </tr>
          <?php endforeach ?>
        </table>

        <div class="entries"> 
          <div> 
            <p>Showing 1 to 10 of 1 entries</p>
          </div>

          <nav>
            <ul class="pagination">
              <li><a href="#" class="prev">&laquo; Prev</a></li>
              <li><a href="#" class="active">1</a></li>
              <li><a href="#">2</a></li>
              <li><a href="#">3</a></li>
              <li><a href="#">4</a></li>
              <li><a href="#">5</a></li>
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
    <form class="modal-information" action="../actions/BookStore.php" method="POST">
      <div class="modal-input"> 
        <label for="title">Title</label><br>
        <input type="text" id="title" name="title" placeholder="Enter Book title"><br>
      </div>
      
      <div class="modal-input"> 
        <label for="author">Author</label><br>
        <input type="text" id="author" name="author" placeholder="Enter author"><br>
      </div>
  
      <div class="modal-input"> 
        <label for="category">Category</label><br>
          <select id="category" name="category" class="modal-category"> 
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
        <label for="year">Year</label><br>
        <input type="number" id="year" name="publication_year" placeholder="Enter publication year"><br>
      </div>
    
      <div class="modal-input"> 
        <label for="quantity">Quantity</label><br>
        <input type="number" id="quantity" name="quantity" placeholder="Enter quantity"><br>
      </div>
      
      <div> 
        <button type="submit">Save Book</button>
        <button type="button" id="close-add-book-modal-btn"> cancel</button>
      </div>
    </form>
  </dialog>

  <script src="../assets/js/script.js"></script>
</body>
</html>