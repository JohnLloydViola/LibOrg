<?php
declare(strict_types=1);

// Login check ito
require_once __DIR__ . "/../../config/session.php";

//Pag logged in na do not give access
if (isset($_SESSION["member_id"])) {
    if (($_SESSION["role"] ?? "") === "admin") {
        header("Location: ./DashboardPage.php");
        exit;
    }

    if (($_SESSION["role"] ?? "") === "member") {
          header("Location: ./member/BrowseBooksPage.php");
          exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <style> 
    .error {
      font-size: 0.6rem;
      margin-left: 0.5rem;
    }
  </style>
</head>
<body>
  <main class="login">
    <div class="welcome">
      <div class="login-logo">
        <img src="../assets/images/Logo.svg" alt="Logo">
      </div>

      <div class="welcome-desc"> 
        <p class="welcome-title">Library Organizer</p>
        <p class="welcome-text">Welcome back! Please log in to continue.</p>
      </div>
    </div>

    <div class="login-info">
      <p class="success" id="login-success" hidden></p>

      <p class="deleted" id="login-failed" hidden></p>

      <form id="login-form">
        <div class="modal-input"> 
          <div class="input-label"> 
            <label for="email">Email</label>
            <p class="error" id="login-email-error"></p>
          </div>
          
          <input type="email" id="email" name="email" placeholder="Enter your email address" required autocomplete="email">
        </div>

        <div class="modal-input"> 
          <div class="input-label"> 
            <label for="password">Password</label>
            <p class="error" id="login-password-error"></p>
          </div>
          
          <input type="password" id="password" name="password" placeholder="Enter your password" required autocomplete="current-password">
        </div>

        <button class="login-submit" type="submit">Login</button>
      </form>

      <p class="register">Don't have an account? <a href="./RegisterPage.php">Register</a></p>
    </div>
  </main>

  <script src="../assets/js/axios.min.js"></script>
  <script src="../assets/js/api.js"></script>
  <script src="../assets/js/LoginPage.js"></script>
</body>
</html>