<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="../assets/css/style.css">
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
      <p class="login-info-title">Login</p>
      
      <form id="login-form">
        <div> 
          <label for="email">Email</label>
          <input type="email" id="email" name="email" placeholder="Enter your email address">
        </div>

        <div> 
          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="Enter your password">
        </div>

        <button class="login-submit" type="submit">Login</button>
      </form>

      <p class="register">Don't have an account? <a href="./Register.php">Register</a></p>
    </div>
  </main>
</body>
</html>