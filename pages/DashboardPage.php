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
              <p> <span>120</span> <br> Total Books</p>
            </div>
          </div>

          <div class="total-item">
            <div class="total-members-icon">
              <img src="../assets/images/TotalMembers.png" alt="Total Members Icon">
            </div>

            <div class="total-members-desc">
              <p> <span>120</span> <br> Total Members</p>
            </div>
          </div>

          <div class="total-item">
            <div class="total-borrowed-icon">
               <img src="../assets/images/TotalBorrowed.png" alt="Total Borrowed Icon">
            </div>

            <div class="total-borrowed-desc">
              <p> <span>120</span> <br> Currently Borrowed</p>
            </div>
          </div>
        </div>

        <div class="table-section">
            <h2>Recent Transactions</h2>

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
    </div>
  </div>
</div>
</body>
</html>