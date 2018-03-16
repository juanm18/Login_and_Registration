<?php
include_once 'resources/database.php';
include_once 'resources/utilities.php';

//process form is button is cliked
if (isset($_POST['signupBtn'])) {
  //initializing array to store error messages from form
  $form_errors = array();

  //required form fields validation
  $required_fields = array('username', 'password', 'email' );

  //calling function to check empty field and merge return data to form_errors array
  $form_errors = array_merge($form_errors, check_empty_fields($required_fields));

  //name of field and length required
  $fields_to_check_length = array('username' => 5, 'password' => 6);

  //call function to check min length and merge data to form errors array
  $form_errors = array_merge($form_errors, check_min_length($fields_to_check_length));

  //calling function to validate email and merge data to form errors array
  $form_errors = array_merge($form_errors, check_email($_POST));

  if(empty($form_errors)){
    //collect form data and store in variables
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    //hashing the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    try {

      $query = "INSERT INTO users (username, password, email, join_date) VALUES (:username, :password, :email, now())";

      //this protects DB from sql injections
      $insert = $connection->prepare($query);
      //data inserted
      $insert->execute(array(':username' => $username , ':password' => $hashed_password, ':email' => $email));
      //checking if data Succesfully entered
      if ($insert->rowCount() == 1) {
        $result = flashMessages("Registration Succesful!", "Pass");
      }
      //if error, display error.
    }catch (PDOException $ex) {
      $result = flashMessages("An Error Occured: " . $ex->getMessage());
    }

  }
  else{
    if (count($form_errors) == 1) {
      $result = flashMessages("There was 1 error in the form <br>");
    }else {
      $result = flashMessages("There were " .count($form_errors). " errors in the form <br>");
      }
    }
  }

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Register Page</title>
</head>
<body>
  <h2>User Authentication System</h2>
  <h3>Registration Form</h3>
  <?php if (isset($result)){echo $result;} ?>
  <?php if (!empty($form_errors)) echo show_errors($form_errors) ?>
  <div class="form">
    <form class="" action="" method="post">
      <table>
        <tr><td>Email:</td> <td> <input type="text" name="email" value=""> </td></tr>
        <tr><td>Username:</td> <td> <input type="text" name="username" value=""> </td></tr>
        <tr><td>Password:</td> <td> <input type="password" name="password" value=""> </td></tr>
        <tr><td></td><td><input style="float:right"type="submit" name="signupBtn" value="Sign Up"></td></tr>
      </table>
    </form>

  </div>
  <p> <a href="index.php">Back</a> </p>
</body>
</html>
