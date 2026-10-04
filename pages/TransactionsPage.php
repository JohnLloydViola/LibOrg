<?php 
declare(strict_types=1);

session_start();

require_once __DIR__ . "/../actions/transaction/TransactionRead.php";
require_once __DIR__ . "/../config/Database.php";

$transactionErrors = $_SESSION["transactionErrors"] ?? [];

$transactionSuccess = $_SESSION["transactionSuccess"] ?? "";

$transactionUnsuccessful = $_SESSION["transactionUnsuccessful"] ?? "";

$transactionReturnSuccess = $_SESSION["transactionReturnSuccess"] ?? "";

$transactionReturnWarning = $_SESSION["transactionReturnWarning"] ?? "";

$transactionDeleteSuccess = $_SESSION["transactionDeleteSuccess"] ?? "";

$transactionNotAvailable = $_SESSION["transactionNotAvailable"] ?? "";

$transactions = getTransactions($conn);
$members = getMembers($conn);
$books = getBooks($conn);
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
    <?php include "../includes/Sidebar.php"?>

    <div class="content">
      <p class="success" id="transaction-success" hidden></p>

      <p class="deleted" id="transaction-failed" hidden></p>

      <?php if ($transactionSuccess !== ""): ?>
        <p class="success"><?=htmlspecialchars($transactionSuccess)?></p>

        <?php unset($_SESSION['transactionSuccess']) ?>
      <?php endif; ?>

      <?php if ($transactionReturnSuccess !== ""): ?>
        <p class="success"><?=htmlspecialchars($transactionReturnSuccess)?></p>

        <?php unset($_SESSION['transactionReturnSuccess']) ?>
      <?php endif; ?>

      <?php if ($transactionReturnWarning !== ""): ?>
        <p class="deleted"><?=htmlspecialchars($transactionReturnWarning)?></p>

        <?php unset($_SESSION['transactionReturnWarning']) ?>
      <?php endif; ?>

      <?php if ($transactionDeleteSuccess !== ""): ?>
        <p class="deleted"><?=htmlspecialchars($transactionDeleteSuccess)?></p>

        <?php unset($_SESSION['transactionDeleteSuccess']) ?>
      <?php endif; ?>

      <?php if ($transactionNotAvailable !== ""): ?>
        <p class="deleted"><?=htmlspecialchars($transactionNotAvailable)?></p>

        <?php unset($_SESSION['transactionNotAvailable']) ?>
      <?php endif; ?>

      <?php if ($transactionUnsuccessful !== ""): ?>
        <p class="deleted"><?=htmlspecialchars($transactionUnsuccessful)?></p>

        <?php unset($_SESSION['transactionUnsuccessful']) ?>
      <?php endif; ?>

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
  <script src="../assets/js/TransactionsPage.js"></script>
</body>
</html>