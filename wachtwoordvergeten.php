<?php
//Nakijken of token nog geldig is
if(isset($_GET["token"])) {

  session_start();
  require 'php/classes.php';
  require 'php/db.php';
  //JSON data
  $outputString = "";
  $data = new jsonData(0, "");

  if(!$con = mysqli_connect($host, $user, $pass, $db)) {
    $data->returnCode = 402;
    echo json_encode($data);
    exit;
  }

  $token = $con->escape_string($_GET["token"]);

  //Kijken of gebruiker bestaat en id opslaan
  $statement = mysqli_prepare($con, "SELECT UserID FROM recoveryKeys rk WHERE Expiredate >= now() AND RecoveryToken = ? AND Used = 0");
  mysqli_stmt_bind_param($statement, "s", $token);
  if(!mysqli_stmt_execute($statement)) {
    $data->returnCode = 401;
    echo json_encode($data);
    exit;
  }

  $result = $statement->get_result();
  if(mysqli_num_rows($result) == 1) {
      while($row = mysqli_fetch_assoc($result)) {
          $userID = ($row["UserID"]);
          $_SESSION["UserID"] = $userID;
      }
  }else{
    header("Location: index.php");
    exit;
  }

}else{
  header("Location: index.php");
  exit;
}


?>

<!DOCTYPE html>
<html lang="nl">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php include_once("php/header.php") ?>
    <title>ProjectNaam</title>
  </head>
  <body class="dots__body">
    <div class="div__dots">
      <canvas class='connecting-dots' id="dots-canvas"></canvas>
    </div>
    <div class="body_loginpage">
      <div class="body__loginbox" id="dom__loginBox">
        <div class="body__loginbox--title">
          <h1>Wachtwoord resetten</h1>
        </div>
        <form action="#" method="post" id="dom__form--resetPassword">
          <div class="form-group body__loginbox--groups">
            <input type="password" name="password" class="input__login" placeholder="Nieuw wachtwoord" id="dom__input--password1">
          </div>
          <div class="text__psReset">
            <meter min="0" max="4" id="dom__meter--sterkte"></meter>
            <p id="dom__text--sterkte"> </p>
            <p id="dom__suggesties"> </p>
          </div>
          <div class="form-group body__loginbox--groups">
            <input type="password" name="password" class="input__login" placeholder="Nieuw wachtwoord herhalen" id="dom__input--password2">
         </div>
         <input type="submit" name="btnLogin" value="Resetten" class="btn__form--primary">
        </form>
      </div>
    </div>
    <?php include_once("php/footer.php") ?>
    <script src="js/dots.js"></script>
    <script src="js/psReset.js"></script>
  </body>
</html>
