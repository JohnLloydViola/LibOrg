<?php 
declare(strict_types=1);

require_once __DIR__ . "/../actions/transaction/TransactionRead.php";
require_once __DIR__ . "/../config/Database.php";

$transactions = getTransactions($conn);
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
      <div class="add-member">
        <h1>Transactions</h1>
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
          </tr>

          <?php foreach($transactions as $transaction): ?> 
            <tr>
              <td><?='T'. str_pad((string) $transaction['id'], 3, "0", STR_PAD_LEFT)?></td>
              <td><?= htmlspecialchars($transaction['full_name'])?></td>
              <td><?= htmlspecialchars($transaction['title'])?></td>
              <td><?=htmlspecialchars($transaction['status'])?></td>
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
  
</body>
</html>