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
    <?php include_once("php/header.php") ?>
    <link rel="stylesheet" href="/css/styledots.css">
    <title>ProjectNaam</title>

  </head>
  <body class="dots__body">
    <div class="div__dots">
      <canvas class='connecting-dots' id="dots-canvas"></canvas>
    </div>
    <div class="body_loginpage">
      <div class="body__loginbox" id="dom__loginBox">
      </div>
    </div>

  <?php include_once("php/footer.php") ?>
  <script src="js/login.js"></script>
  <script src="js/dots.js"></script>


  </body>
</html>
