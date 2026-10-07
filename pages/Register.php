<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>
  <link rel="stylesheet" href="../assets/css/style.css">
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
      <p class="login-info-title">Register</p>
      
      <form id="register-form">
        <div> 
          <label for="full_name">Full name</label>
          <input type="text" id="full_name" name="full_name" placeholder="Enter your full name">
        </div>

        <div> 
          <label for="email">Email</label>
          <input type="email" id="email" name="email" placeholder="Enter your email address">
        </div>

        <div> 
          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="Enter your password">
        </div>

        <div> 
          <label for="phone_number">Phone Number</label>
          <input type="text" id="phone_number" name="phone_number" placeholder="eg. 09xxxxxxxxx">
        </div>

        <div> 
          <label for="address">Address</label>
          <input type="text" id="address" name="address" placeholder="Enter address">
        </div>

        <button class="login-submit" type="submit">Register</button>
      </form>

      <p class="register">Already have an account? <a href="./Login.php">Go back</a></p>
    </div>
  </main>
</body>
</html>