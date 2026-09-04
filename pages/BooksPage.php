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
        <button>Add Book</button>
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

          <tr>
            <td>B001</td>
            <td>The Alchemist</td>
            <td>Paulo Coelho</td>
            <td>Fiction</td>
            <td>1988</td>
            <td>5</td>
            <td>3</td>
            <td> 
              <div>
                <button><img src="../assets/images/Edit.png" alt="Edit"></button> 
                <button><img src="../assets/images/Delete.png" alt="delete"></button> 
              </div>
            </td>
          </tr>

         <tr>
            <td>B001</td>
            <td>The Alchemist</td>
            <td>Paulo Coelho</td>
            <td>Fiction</td>
            <td>1988</td>
            <td>5</td>
            <td>3</td>
            <td> 
              <div>
                <button><img src="../assets/images/Edit.png" alt="Edit"></button> 
                <button><img src="../assets/images/Delete.png" alt="delete"></button> 
              </div>
            </td>
          </tr>

           <tr>
            <td>B001</td>
            <td>The Alchemist</td>
            <td>Paulo Coelho</td>
            <td>Fiction</td>
            <td>1988</td>
            <td>5</td>
            <td>3</td>
            <td> 
              <div>
                <button><img src="../assets/images/Edit.png" alt="Edit"></button> 
                <button><img src="../assets/images/Delete.png" alt="delete"></button> 
              </div>
            </td>
          </tr>

          <tr>
            <td>B001</td>
            <td>The Alchemist</td>
            <td>Paulo Coelho</td>
            <td>Fiction</td>
            <td>1988</td>
            <td>5</td>
            <td>3</td>
            <td> 
              <div>
                <button><img src="../assets/images/Edit.png" alt="Edit"></button> 
                <button><img src="../assets/images/Delete.png" alt="delete"></button> 
              </div>
            </td>
          </tr>

           <tr>
            <td>B001</td>
            <td>The Alchemist</td>
            <td>Paulo Coelho</td>
            <td>Fiction</td>
            <td>1988</td>
            <td>5</td>
            <td>3</td>
            <td> 
              <div>
                <button><img src="../assets/images/Edit.png" alt="Edit"></button> 
                <button><img src="../assets/images/Delete.png" alt="delete"></button> 
              </div>
            </td>
          </tr>

          <tr>
            <td>B001</td>
            <td>The Alchemist</td>
            <td>Paulo Coelho</td>
            <td>Fiction</td>
            <td>1988</td>
            <td>5</td>
            <td>3</td>
            <td> 
              <div>
                <button><img src="../assets/images/Edit.png" alt="Edit"></button> 
                <button><img src="../assets/images/Delete.png" alt="delete"></button> 
              </div>
            </td>
          </tr>

           <tr>
            <td>B001</td>
            <td>The Alchemist</td>
            <td>Paulo Coelho</td>
            <td>Fiction</td>
            <td>1988</td>
            <td>5</td>
            <td>3</td>
            <td> 
              <div>
                <button><img src="../assets/images/Edit.png" alt="Edit"></button> 
                <button><img src="../assets/images/Delete.png" alt="delete"></button> 
              </div>
            </td>
          </tr>

           <tr>
            <td>B001</td>
            <td>The Alchemist</td>
            <td>Paulo Coelho</td>
            <td>Fiction</td>
            <td>1988</td>
            <td>5</td>
            <td>3</td>
            <td> 
              <div>
                <button><img src="../assets/images/Edit.png" alt="Edit"></button> 
                <button><img src="../assets/images/Delete.png" alt="delete"></button> 
              </div>
            </td>
          </tr>

           <tr>
            <td>B001</td>
            <td>The Alchemist</td>
            <td>Paulo Coelho</td>
            <td>Fiction</td>
            <td>1988</td>
            <td>5</td>
            <td>3</td>
            <td> 
              <div>
                <button><img src="../assets/images/Edit.png" alt="Edit"></button> 
                <button><img src="../assets/images/Delete.png" alt="delete"></button> 
              </div>
            </td>
          </tr>

           <tr>
            <td>B001</td>
            <td>The Alchemist</td>
            <td>Paulo Coelho</td>
            <td>Fiction</td>
            <td>1988</td>
            <td>5</td>
            <td>3</td>
            <td> 
              <div>
                <button><img src="../assets/images/Edit.png" alt="Edit"></button> 
                <button><img src="../assets/images/Delete.png" alt="delete"></button> 
              </div>
            </td>
          </tr>
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
</body>
</html>