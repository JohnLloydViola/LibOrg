<?php
declare(strict_types=1);

session_start();

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
  <title>Register</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <style> 
    .error {
      font-size: 0.6rem;
      margin-left: 0.5rem;
    }
  </style>
</head>
<body>
  <!-- REUSED LANG YUNG LOGIN PAGE AND CHANGED SOME TEXT AND ATTRIBUTES. ALSO ADDED SOME NEW INPUTS -->
  <main class="login">
    <div class="welcome">
        <div class="login-logo">
          <img src="../assets/images/Logo.svg" alt="Logo">
        </div>

        <div class="welcome-desc"> 
          <p class="welcome-title">Library Organizer</p>
          <p class="welcome-text">Please Register to continue.</p>
        </div>
    </div>

    <div class="login-info">
      <p class="success" id="register-success" hidden></p>

      <p class="deleted" id="register-failed" hidden></p>

      <form id="register-form">
        <div class="modal-input"> 
          <div class="input-label"> 
            <label for="full_name">Full name</label>
            <p class="error" id="register-full_name-error"></p>
          </div>

          <input type="text" id="full_name" name="full_name" placeholder="Enter your full name">
        </div>

        <div class="modal-input"> 
          <div class="input-label"> 
            <label for="email">Email</label>
            <p class="error" id="register-email-error"></p>
          </div>

          <input type="email" id="email" name="email" placeholder="Enter your email address">
        </div>

        <div class="modal-input"> 
          <div class="input-label"> 
            <label for="password">Password</label>
            <p class="error" id="register-password-error"></p>
          </div>

          <input type="password" id="password" name="password" placeholder="Enter your password" minlength="8" maxlength="64">
        </div>

        <div class="modal-input"> 
          <div class="input-label"> 
            <label for="phone_number">Phone Number</label>
            <p class="error" id="register-phone_number-error"></p>
          </div>

          <input type="text" id="phone_number" name="phone_number" placeholder="eg. 09xxxxxxxxx" maxlength="11">
        </div>

        <div class="modal-input"> 
          <div class="input-label"> 
            <label for="address">Address</label>
            <p class="error" id="register-address-error"></p>
          </div>

          <input type="text" id="address" name="address" placeholder="Enter address">
        </div>

        <button class="login-submit" type="submit">Register</button>
      </form>

      <p class="register">Already have an account? <a href="./LoginPage.php">Go back</a></p>
    </div>
  </main>

  <script src="../assets/js/axios.min.js"></script>
  <script src="../assets/js/RegistrationPage.js"></script>
</body>
</html>