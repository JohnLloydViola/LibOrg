<?php 
declare(strict_types=1);

require_once __DIR__ . "/../actions/transaction/TransactionRead.php";
require_once __DIR__ . "/../config/Database.php";

$transactions = getTransactions($conn);
$members = getMembers($conn);
$books = getBooks($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
  <div class="transaction-container"> 
    <?php include "../includes/SideBar.php"?>

    <div class="content">
      <div class="borrow-book">
        <h1>Transactions</h1>
        <button id="open-borrow-book-modal-btn">Borrow Book</button>
      </div> 

      <div class="table-section"> 
        <div class="filter"> 
          <div>
            <input name="transaction" type="text" placeholder="Search Transactions...">
          </div>

          <div> 
            <select name="status"> 
              <option selected disabled>All Status</option>
              <option value="borrowed">Borrowed</option>
              <option value="returned">Returned</option>
            </select>
          </div>
        </div>

        <table>
          <tr>
            <th>ID</th>
            <th>MEMBER</th>
            <th>BOOK</th>
            <th>STATUS</th>
            <th>ACTION</th>
          </tr>

          <?php foreach($transactions as $transaction): ?> 
            <tr>
              <td><?='T'. str_pad((string) $transaction['id'], 3, "0", STR_PAD_LEFT)?></td>
              <td><?= htmlspecialchars($transaction['full_name'])?></td>
              <td><?= htmlspecialchars($transaction['title'])?></td>
              <td><?=htmlspecialchars($transaction['status'])?></td>
              <td>
                <form action="../actions/transaction/TransactionReturn.php" method="POST"> 
                  <input type="hidden" name="id" value="<?=$transaction['id']?>">
                  <button type="submit">Return Book</button> 
                </form>
                
                <form action="../actions/transaction/TransactionDelete.php" method="POST">
                   <input type="hidden" name="id" value="<?=$transaction['id']?>">

                  <button type="submit">
                    <img src="../assets/images/Delete.png" alt="delete">
                  </button>
                </form>
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

  <!--Modal dialogue para sa Borrow button-->
  <dialog id="borrow-book-modal"> 
    <div><h1>BORROW BOOK</h1> </div>

    <form class="modal-information" action="../actions/transaction/TransactionStore.php" method="POST">
      <div class="modal-input"> 
        <label for="member_id">Select Member</label><br>

        <select name="member_id" class="modal-category">
          <option disabled selected value="">Select Member</option> 
          <?php foreach ($members as $member): ?>
            <option value="<?= $member['id'] ?>">
              <?= htmlspecialchars($member['full_name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      
      <div class="modal-input"> 
        <label for="book_id">Select Book</label><br>
        
        <select name="book_id" class="modal-category"> 
          <option disabled selected value= "">Select Book</option> 
          <?php foreach ($books as $book): ?>
            <option value="<?= $book['id'] ?>">
              <?= htmlspecialchars($book['title']) ?>
              (Available: <?= $book['available_quantity'] ?>)
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div> 
        <button type="submit">Borrow</button>
        <button type="button" id="close-borrow-book-modal-btn">cancel</button>
      </div>
    </form>
  </dialog>
  
  <script src="../assets/js/TransactionsPage.js"></script>
</body>
</html>