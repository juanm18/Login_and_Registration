<?php include_once 'resources/sessions.php';
session_destroy();
header("Location: index.php");
?>
