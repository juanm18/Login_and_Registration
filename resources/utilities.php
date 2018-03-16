<?php
/**
  * Function checks that all fields are populated
  * takes @params $required_fields_array, an array containing name of all required fields
  * @returns array of any errors
*/
function check_empty_fields($required_fields_array){
  //initialize array to store error message
  $form_errors = array();

  //looping through required fields array and populate form error array
  foreach ($required_fields_array as $name_of_field) {
    if (!isset($_POST[$name_of_field]) || $_POST[$name_of_field]== NULL) {
      $form_errors[] = $name_of_field . " is a required field.";
    }
  }
  return $form_errors;
}



/**
  * Function checks that all fields are populated with the correct amount of characters required
  * takes @params $fields_to_check_length, an array containing name of fields for which we want to check length
  * @returns array of any errors
*/
//validating length of submited fields
function check_min_length($fields_to_check_length){
  $form_errors = array();
//looping through and making sure each field meets minimum_length_required
  foreach ($fields_to_check_length as $name_of_field => $minimum_length_required) {
    //comparing users input to min lenght required of field
    if (strlen(trim($_POST[$name_of_field])) < $minimum_length_required) {
      $form_errors[] = $name_of_field . " is too short, must be {$minimum_length_required} characters long";
    }
  }
  return $form_errors;
}



/**
  * Function checks that email is valid
  * takes @params $data, key/value pair array of name of form control
  * @returns array of any errors
*/
function check_email($data){
  $form_errors = array();
  $key = 'email';
  if (array_key_exists($key, $data)) {

    //check if the email field has a value
    if ($_POST[$key] != NULL) {
      //remove all illegal characters from email
      $key = filter_var($key, FILTER_SANITIZE_EMAIL);
      //check if input is a valid email address
      if (filter_var($_POST[$key], FILTER_VALIDATE_EMAIL) == false) {
        $form_errors[] = $key . " is not a valid email address.";
      }
    }
  }
  return $form_errors;
}


/**
  * Function checks that all fields are populated
  * takes @params $form_errors_array, an array containing all errors to loop throuh
  * @returns string containg all errors
*/
function show_errors($form_errors_array){
  $errors = "<p><ul style='color:red;'>";
  //loop through error array and display all itmes in a list
      foreach ($form_errors_array as $the_error) {
        $errors .= "<li>{$the_error}</li>";
      }
      $errors .= "</ul></p>";
      return $errors;
}

function flashMessages($message, $passOrFail = "Fail"){
  if ($passOrFail == 'Pass'){
    $data = "<p style='padding:20px; border:1px solid gray; color:green;'>{$message}</p>";
  }else {
    $data = "<p style='padding:20px; color:red'>{$message}</p>";
  }
  return $data;
}


 ?>
