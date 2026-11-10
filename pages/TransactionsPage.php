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
  <title>Transactions</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="icon" href="../assets/images/Transactions.svg">
</head>
<body>
  <div class="transaction-container"> 
    <?php require_once __DIR__ . "/../includes/Sidebar.php"?>

    <div class="content">
      <?php require_once __DIR__ . "/../includes/MenuButton.php"; ?>

      <p class="success" id="transaction-success" hidden></p>

      <p class="deleted" id="transaction-failed" hidden></p>

      <div class="borrow-book">
        <h1>Transactions</h1>
        <button id="open-borrow-book-modal-btn">Borrow Book</button>
      </div> 

      <div class="table-section"> 
        <div class="filter"> 
          <div>
            <input id="transaction-search" name="transaction" type="text" placeholder="Search By Member...">
          </div>

          <div> 
            <select id="transaction-status" name="status"> 
              <option value="" selected>All Status</option>
              <option value="borrowed">Borrowed</option>
              <option value="returned">Returned</option>
            </select>
          </div>
        </div>

        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th style="width: 35%;">MEMBER</th>
              <th style="width: 35%;">BOOK</th>
              <th>STATUS</th>
              <th>ACTION</th>
            </tr>
          </thead>

          <tbody id="transaction-table-body">
           
          </tbody>
        </table>

         <div class="entries"> 
          <div> 
            <p id="transaction-entries">Showing 0 to 0 of 0 entries</p>
          </div>

          <nav>
            <ul id="transaction-pagination" class="pagination">
            </ul>
          </nav>
        </div>

      </div>

    </div>

  </div>

  <!--Modal dialogue para sa Borrow button-->
  <dialog id="borrow-book-modal"> 
    <div><h1>BORROW BOOK</h1> </div>

    <form class="modal-information" id="borrow-book-form">
      <div class="modal-input"> 
        <div class="input-label"> 
          <label for="member_id">Select Member</label>

          <p class="error" id="borrow-member-error"></p>
        </div>

        <select name="member_id" id="member_id" class="modal-category">
          <option disabled selected value="">Select Member</option> 
        </select>
      </div>
      
      <div class="modal-input"> 
        <div class="input-label"> 
          <label for="book_id">Select Book</label>

          <p class="error" id="borrow-book-error"></p>
        </div>
       
        <select name="book_id" id="book_id" class="modal-category"> 
          <option disabled selected value= "">Select Book</option> 
        </select>
      </div>

      <div> 
        <button class="modal-submit" type="submit">Borrow</button>
        <button class="modal-cancel" type="button" id="close-borrow-book-modal-btn">cancel</button>
      </div>
    </form>
  </dialog>
          
  <script src="../assets/js/axios.min.js"></script>
  <script src="../assets/js/api.js"></script>
  <script src="../assets/js/TransactionsPage.js"></script>
  <script src="../assets/js/Logout.js"></script>
  <script src="../assets/js/Menu.js"></script>
</body>
</html>