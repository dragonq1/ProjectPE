<!doctype html>
<html>
<head>

<?php include_once("php/header.php") ?>

</head>
<body>

    <div class="body_loginpage">
        <div class="body__loginbox">
          <p class="text__title--center">Inloggen</p>
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
                              session_start();
                              $_SESSION["UserID"]=$userID;
                              header("Location: home.php");
                          }
                      }
                        echo '<p class="text__error">Foutieve inloggegevens</p>';
                  }
              }

             ?>
        </div>

    </div>




</body>

</html>
