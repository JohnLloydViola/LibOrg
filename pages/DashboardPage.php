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
  <title>Dashboard</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="icon" href="../assets/images/Dashboard.svg">
</head>
<body>
  <div class="dashboard-container"> 
    <?php include "../includes/Sidebar.php" ?>

    <div class="content"> 
       <div class="dashboard-title">
          <h1>Dashboard</h1>
        </div>

        <div class="dashboard-stats">
          <div class="total-item">
            <div class="total-books-icon">
              <img src="../assets/images/TotalBooks.svg" alt="Total Books Icon">
            </div>

            <div class="total-books-desc">
              <p> <span><?=$totalBooks?></span> <br> Total Books</p>
            </div>
          </div>

          <div class="total-item">
            <div class="total-members-icon">
              <img src="../assets/images/TotalMembers.svg" alt="Total Members Icon">
            </div>

            <div class="total-members-desc">
              <p> <span><?=$totalMembers?></span> <br> Total Members</p>
            </div>
          </div>

          <div class="total-item">
            <div class="total-borrowed-icon">
               <img src="../assets/images/TotalBorrowed.svg" alt="Total Borrowed Icon">
            </div>

            <div class="total-borrowed-desc">
              <p> <span><?=$totalBorrowed?></span> <br> Currently Borrowed</p>
            </div>
          </div>
        </div>

        <div class="table-section transaction">
            <h2>Recent Transactions</h2>

            <table>
              <thead>
                <tr>
                  <th>ID</th>
                  <th style="width:30%;">MEMBER</th>
                  <th style="width: 45%;">BOOK</th>
                  <th>STATUS</th>
                </tr>
              </thead>

              <tbody>
                <?php foreach($transactions as $transaction): ?> 
                  <tr>
                    <td><?='T'. str_pad((string) $transaction['id'], 3, "0", STR_PAD_LEFT)?></td>
                    <td><?= htmlspecialchars($transaction['full_name'])?></td>
                    <td><?= htmlspecialchars($transaction['title'])?></td>
                    <td>
                      <span class="<?=htmlspecialchars($transaction['status']) === "borrowed" ? "borrowed": "returned"?> status"> 
                        <?=htmlspecialchars($transaction['status'])?>
                      </span>
                    </td>
                  </tr>
                <?php endforeach ?>
              </tbody>
        </table>

        <div id="view-all" class="entries"> 
          <div> 
            <a href="./TransactionsPage.php">View All Transactions</a>
            <img src="../assets/images/ArrowRight.svg" alt="View all transactions Arrow right">
          </div>
        </div>
    </div>

  </div>
</div>
</body>
</html>