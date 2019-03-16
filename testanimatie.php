<?php
  if((isset($_POST["password"])) && (isset($_POST["email"])) && ($_SERVER["REQUEST_METHOD"] == "POST")) {
      require 'php/db.php';
      $con = mysqli_connect($host, $user, $pass, $db);
      if(!$con) {
        die(mysqli_connect_error());
        $error = "Er ging iets fout! Probeer opnieuw!";
      }

      $email = $con->escape_string($_POST["email"]);

      if(!$con) {
        throw new Exception ('Could not connect: ' . mysqli_error());
      }else{

          $statement = mysqli_prepare($con, "SELECT Password, UserID FROM dragv_dev.users where Email = ?");
          mysqli_stmt_bind_param($statement, "s", $email);
          try{
            mysqli_stmt_execute($statement);
          }catch(Exception $e) {
              $error = "Er ging iets fout! Probeer opnieuw!";
              syslog(LOG_ALERT, "mysqli error on login page: ".$e);
              exit;
          }

          $result = $statement->get_result();

          if(mysqli_num_rows($result) == 1) {
              while($row = mysqli_fetch_assoc($result)) {
                  $checkHash = $row["Password"];
                  $userID = $row["UserID"];
              }

              if(password_verify($_POST["password"], $checkHash)){
                  session_start();
                  $_SESSION["UserID"]=$userID;
                  header("Location: home.php");
              }
          }
            $error = "Foutieve inloggegevens";
      }
  }

 ?>

<!DOCTYPE html>
<html lang="nl">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.2.0/zxcvbn.js"></script>
    <title>ProjectNaam</title>
    <link rel="stylesheet" href="/css/styledots.css">
  </head>
  <body class="dots__body">
    <div class="div__dots">
      <canvas class='connecting-dots' id="dots-canvas"></canvas>
    </div>
        <div class="body_loginpage">
         <div class="body__loginbox">
          <div class="body__loginbox--title">
            <h1>Welkom bij PVSTS</h1>
            <p>Inloggen</p>
          </div>
            <form class="" action="testanimatie.php" method="post">
                  <div class="form-group body__loginbox--groups">
                    <input type="email" name="email" class="input__login" placeholder="e-mail" required>
                  </div>
                  <div class="form-group body__loginbox--groups">
                    <input type="password" name="password" class="input__login" placeholder="wachtwoord" required>
                 </div>
                 <input type="submit" name="btnLogin" value="Inloggen" class="btn__form--primary btn btn__login">
            </form>
            <a href="registratie.php"><button value="Registreren" class="btn__form--primary btn btn__register">Registeren</button></a>
            <?php if (isset($error)) {echo ("<p class=\"text__error\">$error</p>");} ?>
         </div>
       </div>

  <script src="js/dots.js"></script>

  </body>
</html>
