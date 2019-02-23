<?php
require "db.php";
if(($_SERVER["REQUEST_METHOD"] == "POST") && isset($_POST["homeMenu:2"])) {

  session_start();
  if (!isset($_SESSION["UserID"])) {
    header("Location: index.php");
  }
  $userID = $_SESSION["UserID"];
  $con = mysqli_connect($host, $user, $pass, $db);
//statement maken en uitvoeren
  $statement1 = mysqli_prepare($con,"SELECT LastName,FirstName,NickName,Email FROM users where UserID = ?" );
  mysqli_stmt_bind_param($statement1,"s",$userID);
  mysqli_stmt_execute($statement1);
  $result1 = $statement1->get_result();

//account body

echo("
    <div class=\"Body__account\">
    <div class=\"Account__info\">
    <div class=\"Account__title\">
    <h2>Mijn account</h2>
    </div>
    <p class=\"Account__info__text\">










");


}
 ?>
