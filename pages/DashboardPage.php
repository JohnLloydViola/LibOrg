<?php 
declare(strict_types=1);

// Login check ito
require_once __DIR__ . "/../config/session.php";

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
  <title>Dashboard</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="icon" href="../assets/images/Dashboard.svg">
</head>
<body>
  <div class="dashboard-container"> 
    <?php require_once __DIR__ . "/../includes/Sidebar.php" ?>

    <div class="content"> 
      <?php require_once __DIR__ . "/../includes/MenuButton.php"; ?>

      <p class="deleted" id="recent-transactions-failed" hidden></p>

       <div class="dashboard-title">
          <h1>Dashboard</h1>
        </div>

        <div class="dashboard-stats">
          <div class="total-item">
            <div class="total-books-icon">
              <img src="../assets/images/TotalBooks.svg" alt="Total Books Icon">
            </div>

            <div class="total-books-desc">
              <p> <span id="total-books">0</span> <br> Total Books</p>
            </div>
          </div>

          <div class="total-item">
            <div class="total-members-icon">
              <img src="../assets/images/TotalMembers.svg" alt="Total Members Icon">
            </div>

            <div class="total-members-desc">
              <p> <span id="total-members">0</span> <br> Total Members</p>
            </div>
          </div>

          <div class="total-item">
            <div class="total-borrowed-icon">
               <img src="../assets/images/TotalBorrowed.svg" alt="Total Borrowed Icon">
            </div>

            <div class="total-borrowed-desc">
              <p> <span id="total-borrowed">0</span> <br> Currently Borrowed</p>
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

              <tbody id="recent-transactions-table-body">
               
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

<script src="../assets/js/axios.min.js"></script>
<script src="../assets/js/api.js"></script>
<script src="../assets/js/DashboardPage.js"></script>
<script src="../assets/js/Logout.js"></script>
<script src="../assets/js/Menu.js"></script>
</body>
</html>