<?php

//Live Chat
if(($_SERVER["REQUEST_METHOD"] == "POST") && isset($_POST["message"])) {
  session_start();
  $con = mysqli_connect($host, $user, $pass, $db);
  $userID = $_SESSION["UserID"];

//Uitloggen indien niet geconnect
  if(!$con) {
    header("Location: ../home.php");
  }else{






}



}
 ?>
