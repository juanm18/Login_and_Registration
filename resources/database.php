<?php
//Variables to hold connection parameters
$username = 'root';
$password = 'root';
$dsn = 'mysql:host=localhost; dbname=register';


try{
  //Creating an instance of the PDO Class with parameters
  $connection = new PDO($dsn, $username, $password);

  //set pdo error mode to exception
  $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  //success message
  // echo "Connection Succesful";

}catch(PDOException $ex){
  //error message
  echo "Connection failed " . $ex->getMessage();
}
