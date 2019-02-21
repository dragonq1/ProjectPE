<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat|Open+Sans" rel="stylesheet">
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
            <form class="" action="index.php" method="post">
                  <div class="form-group body__loginbox--groups">
                    <!-- <label for="email">E-mail</label> -->
                    <input type="email" name="email" class="input__login" placeholder="e-mail" required>
                  </div>
                  <div class="form-group body__loginbox--groups">
                    <!-- <label for="password">Wachtwoord</label> -->
                    <input type="password" name="password" class="input__login" placeholder="wachtwoord" required>
                 </div>
                 <input type="submit" name="btnLogin" value="Inloggen" class="btn__form--primary btn btn__login">
            </form>
            <button value="Registreren" class="btn__form--primary btn btn__register"><a href="register.php"></a>Registeren</button>

            <?php

              if((isset($_POST["password"])) && (isset($_POST["email"])) && ($_SERVER["REQUEST_METHOD"] == "POST")) {

                  session_start();
                  require 'php/db.php';
                  $con = mysqli_connect($host, $user, $pass, $db);

                  $email = $con->escape_string($_POST["email"]);

                  if(!$con) {
                    throw new Exception ('Could not connect: ' . mysqli_error());
                  }else{

                      $statement = mysqli_prepare($con, "SELECT Password, UserID FROM dragv_dev.users where Email = ?");
                      mysqli_stmt_bind_param($statement, "s", $email);
                      mysqli_stmt_execute($statement);
                      $result = $statement->get_result();

                      if(mysqli_num_rows($result) == 1) {
                          while($row = mysqli_fetch_assoc($result)) {
                              $checkHash = $row["Password"];
                              $userID = $row["UserID"];
                          }

                          if(password_verify($_POST["password"], $checkHash)){
                              $_SESSION["UserID"]=$userID;
                              header("Location: testanimatie.php");
                          }
                      }
                        echo '<p class="text__error">Foutieve inloggegevens</p>';
                  }
              }

             ?>

         </div>
       </div>

  <script src="js/dots.js"></script>

  </body>
</html>
