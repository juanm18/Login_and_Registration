<?php include_once 'resources/sessions.php'; ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Registration Page</title>
  </head>
  <body>
    <h2>User Authentication System</h2>
    <?php if (!isset($_SESSION['username'])): ?>
      <p>You are currently Not Signed in <a href="login.php">Login</a> Not Yet a Member? <a href="signup.php">SignUp</a></p>
    <?php else: ?>
    <p>You are logged In as <?php if (isset($_SESSION['username'])) echo $_SESSION['username']; ?> <a href="logout.php">LogOut</a></p>
  <?php endif ?>
  </body>
</html>
