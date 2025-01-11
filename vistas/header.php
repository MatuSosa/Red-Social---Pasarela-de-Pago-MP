<?php 
  session_start();
  $_SESSION['previous_page'] = $_SERVER['REQUEST_URI']; // Almacena la URL actual en la sesión
  include_once "../modelo/config.php";
  if(!isset($_SESSION['unique_id'])){
    header("location: login.php");
    
  }
  
  $status_message = "";
if (isset($_SESSION["status_message"])) {
    $status_message = $_SESSION["status_message"];
    unset($_SESSION["status_message"]);
}
$error_message = "";
if (isset($_SESSION["error_message"])) {
    $error_message = $_SESSION["error_message"];
    unset($_SESSION["error_message"]);
}
?>
   
<!DOCTYPE html>
<html lang="es-AR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
 <?php
 $sql = mysqli_query($conn, "SELECT * FROM users WHERE unique_id = {$_SESSION['unique_id']}");
            if(mysqli_num_rows($sql) > 0){
              $row = mysqli_fetch_assoc($sql);
            }
            ?>
    <title><?php echo $row['fname']. " " . $row['lname'] ?> - Edu-Connect</title>
    <link rel="shortcut icon" href="../images/logo.png" type="image/x-icon">
  <link rel="stylesheet" href="../css/estiles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
</head>