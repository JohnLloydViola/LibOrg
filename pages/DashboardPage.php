<?php 
declare(strict_types=1);

require_once __DIR__ . "/../config/Database.php";
require_once __DIR__ . "/../actions/dashboard/DashboardRead.php";

$totalBooks = getTotalBooks($conn);
$totalMembers = getTotalMembers($conn);
$totalBorrowed = getTotalBorrowed($conn);
$transactions = getRecentTransactions($conn);
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
  <div class="dashboard-container"> 
    <?php include "../includes/SideBar.php" ?>

    <div class="content"> 
       <div class="dashboard-title">
          <h1>Dashboard</h1>
        </div>

        <div class="dashboard-stats">
          <div class="total-item">
            <div class="total-books-icon">
              <img src="../assets/images/TotalBooks.png" alt="Total Books Icon">
            </div>

            <div class="total-books-desc">
              <p> <span><?=$totalBooks?></span> <br> Total Books</p>
            </div>
          </div>

          <div class="total-item">
            <div class="total-members-icon">
              <img src="../assets/images/TotalMembers.png" alt="Total Members Icon">
            </div>

            <div class="total-members-desc">
              <p> <span><?=$totalMembers?></span> <br> Total Members</p>
            </div>
          </div>

          <div class="total-item">
            <div class="total-borrowed-icon">
               <img src="../assets/images/TotalBorrowed.png" alt="Total Borrowed Icon">
            </div>

            <div class="total-borrowed-desc">
              <p> <span><?=$totalBorrowed?></span> <br> Currently Borrowed</p>
            </div>
          </div>
        </div>

        <div class="table-section transaction">
            <h2>Recent Transactions</h2>

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

        <div id="view-all" class="entries"> 
          <div> 
            <a href="./TransactionsPage.php">View All Transactions</a>
            <img src="../assets/images/ArrowRight.png" alt="View all transactions Arrow right">
          </div>
        </div>
    </div>

  </div>
</div>
</body>
</html>