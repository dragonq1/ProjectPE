<?php
require "db.php";
if(($_SERVER["REQUEST_METHOD"] == "POST") && isset($_POST["homeMenu"])) {

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


if(mysqli_num_rows($result1)>=1)
{
  while($row = mysqli_fetch_assoc($result1))
   {
    $firstname = $row['FirstName'];
    $lastname = $row['LastName'];
    $nickname = $row['NickName'];
    $email = $row['Email'];
   }
}

//account body

echo("
    <div class=\"Body__account\">
        <div class=\"Account__info\">
            <div class=\"Account__title\">
            <h2>Mijn account</h2>
            </div>

           <div class=\"Account__data\">
            <h3>Nickname</h3>
            <p> $nickname </p>
            <h3>Voornaam</h3>
            <p> $firstname </p>
            <h3>Achternaam</h3>
            <p> $lastname </p>
            <h3>Email</h3>
            <p>$email </p>

           </div>









");


}
 ?>
