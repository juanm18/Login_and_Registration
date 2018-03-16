<?php
include_once 'resources/sessions.php';
include_once 'resources/database.php';
include_once 'resources/utilities.php';

//when button is clicked we proceed
if (isset($_POST['loginBtn'])) {
  $form_errors = array();

  //validation
  $required_fields = array('username', 'password');
  //checking that all fields are populated
  $form_errors = array_merge($form_errors, check_empty_fields($required_fields));

  if (empty($form_errors)) {
    //collect form database
    $user = $_POST['username'];
    $password = $_POST['password'];

    //check if user exist in DB
    $query = "SELECT * FROM users WHERE username = :username";
    $statement = $connection->prepare($query);
    $statement->execute(array(':username' => $user));

    while ($user = $statement->fetch()) {
      $id = $user['id'];
      $hashed_password = $user['password'];
      $username = $user['username'];

      if (password_verify($password, $hashed_password)) {
        $_SESSION['id'] = $id;
        $_SESSION['username'] = $username;
        header('Location: index.php');
      }else {
        $result = flashMessages("Invalid Username or Password");
      }
    }
  }else {
    if (count($form_errors) == 1) {
      $result = flashMessages("There was one error in the form.");
    }else {
      $result = flashMessages("There were " .count($form_errors). " errors in the form");
    }
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Login Page</title>
</head>
<body>
  <h2>User Authentication System</h2>
  <h3>Login Form</h3>
  <?php if (isset($result)) echo $result; ?>
  <?php if (!empty($form_errors)) echo show_errors($form_errors); ?>
  <div class="form">
    <form class="" action="" method="post">
      <table>
        <tr><td>Username:</td> <td> <input type="text" name="username" value=""> </td></tr>
        <tr><td>Password:</td> <td> <input type="password" name="password" value=""> </td></tr>
        <tr><td></td><td><input style="float:right"type="submit" name="loginBtn" value="SignIn"></td></tr>
      </table>
    </form>
    <a href="forgot_password.php">Forgot Password?</a>
  </div>
  <p> <a href="index.php">Back</a> </p>
</body>
</html>
