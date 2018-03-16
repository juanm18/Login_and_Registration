<?php
include_once 'resources/database.php';
include_once 'resources/utilities.php';

if (isset($_POST['passwordResetBtn'])) {
  echo "hello";
  $form_errors = array();
  $required_fields = array('email', 'new_password', 'confirm_password');

  //CHECK FOR EMPTY FIELDS
  $form_errors = array_merge($form_errors, check_empty_fields($required_fields));

  //VALIDATING LENGTH OF PASSWORD
  $fields_to_check_length = array('new_password' => 6, 'confirm_password' => 6);

  //CALLING FUNCTION THAT VALIDATES FORM INPUT LENGTH
  $form_errors = array_merge($form_errors, check_min_length($fields_to_check_length));

  //EMAIL VALIDATION / MERGE THE RETURN DATA INTO FORM_ERROR ARRAY
  $form_errors = array_merge($form_errors, check_email($_POST));
  //
  //   CHECKING IF FORM_ERROR ARRAY IS EMPTY
  if (empty($form_errors)) {
    //     //COLLECT DATA FROM FORM
    $email = $_POST['email'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];


    //CONFIRM NEW PASSWORD MATCHES CONFIRMATION PASSWORD
    if ($new_password != $confirm_password) {
      $result = flashMessages("Passwords Do Not match!");
    }else {

      try {
        //SQL QUERY SELECTS USER BY EMAIL
        $query = "SELECT email FROM users WHERE email = :email";
        //PREPARING AND SANITIZING SQL STATEMENT
        $statement = $connection->prepare($query);
        //EXECUTING THE STATEMENT
        $statement->execute(array(':email' => $email));

        //IF USER EXISTS
        if ($statement->rowCount() == 1) {
          //HASH PASSWORD
          $hashed_password = password_hash($confirm_password, PASSWORD_DEFAULT);
          //SQL QUERY
          $query = "UPDATE users SET password=:password WHERE email=:email";
          //PREPARE STATEMENT
          $statement = $connection->prepare($query);
          //EXECUTE SQL QUERY
          $statement->execute(array(':password' => $hashed_password, ':email' => $email));
          //SUCCESS MESSAGE IF PASSWORD IS RESET
          $result = flashMessages("Password Reset Succesfully!", "Pass");

        }else {
          //ERROR MESSAGE IF EMAIL NOT FOUND
          $result = flashMessages("The Email provided does not exist in the Database!");
        }
      } catch (PDOException $ex) {
        //EXCEPTION ERROR HANDLER
        $result = flashMessages("An error had occured: ".$ex->getMessage());

      }
    }
  }
  else {
    if (count($form_errors) == 1) {
      $result = flashMessages("There is one error in the form<br>");
    }
    else{
      $result = flashMessages("There are ".count($form_errors)." errors in the form.<br>");
    }
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Password Reset Page</title>
</head>
<body>
  <h2>User Authentication System</h2>
  <h3>Password Reset Form</h3>
  <?php if (isset($result)) echo $result; ?>
  <?php if (!empty($form_errors)) echo show_errors($form_errors); ?>
  <div class="form">
    <form class="" action="" method="post">
      <table>
        <tr><td>Email:</td> <td> <input type="text" name="email" value=""> </td></tr>
        <tr><td>New Password:</td> <td> <input type="password" name="new_password" value=""> </td></tr>
        <tr><td>Confirm Password:</td> <td> <input type="password" name="confirm_password" value=""> </td></tr>
        <tr><td></td><td><input style="float:right"type="submit" name="passwordResetBtn" value="Reset Password"></td></tr>
      </table>
    </form>
    <a href="index.php">Home</a>
  </body>
  </html>
