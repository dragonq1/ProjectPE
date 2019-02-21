<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <?php include_once("php/header.php") ?>
  </head>
  <body>
    <?php
     session_start();
     $_SESSION['msg'] = '';
     require 'php/db.php';
     $con = mysqli_connect($host, $user, $pass, $db);
     if(!$con) {
       throw new Exception ('Could not connect: ' . mysqli_error());
       }else
      {

       if ($_SERVER['REQUEST_METHOD']=='POST')
       {

           if ($_POST['psw'] != $_POST['psw_repeat'])
           {
             $_SESSION['message'] = 'The two passwords do not match!';
             echo $_SESSION['message'];

           }else
               {
//variabelen inladen.
                 $nickname = $con->real_escape_string($_POST['nickname']);
                 $email = $con->real_escape_string($_POST['email']);
                 $voornaam = $con->real_escape_string($_POST['voornaam']);
                 $achternaam = $con->real_escape_string($_POST['achternaam']);
                 $pswhashed = password_hash(($con->real_escape_string($_POST['psw'])),PASSWORD_DEFAULT);
//email checken

                 $statement_email = mysqli_prepare ($con,("SELECT Email FROM users where Email LIKE ?"));
                 mysqli_stmt_bind_param($statement_email,"s",$email);

                 if(mysqli_stmt_execute($statement_email)==false)
                 {
                  echo "Emailerror";
                }else
                   {
                    $resultaat =$statement_email->get_result();
                    if(mysqli_num_rows($resultaat) > 0)
                     {
                      echo "De email $email bestaat al.";
                     }else
                         {
//Nickname checken
                           $statement_nickname = mysqli_prepare($con,("SELECT Nickname FROM users where Nickname LIKE ?"));
                           mysqli_stmt_bind_param($statement_nickname,"s",$nickname);

                           if(mysqli_stmt_execute($statement_nickname)==false)
                             {
                               echo "Nickname error.";
                            }else
                               {
                                 $resultaat2 = $statement_nickname->get_result();
                                 if(mysqli_num_rows($resultaat2)>0)
                                    {
                                      echo "De nickname $nickname bestaat al.";
                                    }else
                                      {



//In database zetten.
                                        $statement = mysqli_prepare ($con, ("INSERT INTO users (Email ,Password,LastName,FirstName,Nickname) VALUES (?,?,?,?,?)"));
                                        mysqli_stmt_bind_param($statement, "sssss", $email,$pswhashed,$achternaam,$voornaam,$nickname);

                                        if(mysqli_stmt_execute($statement) == true)
                                         {
                                           $_SESSION['message'] = "Registration is succesfull. Added $nickname to the database.";
                                           echo $_SESSION['message'];
                                           header("Location: index.php");
                                         } else
                                             {
                                               $_SESSION['message'] = 'User could not be added to the database.';
                                               echo $_SESSION['message'];
                                               echo $statement->error;
                                             }
                                     }
                             }
                   }
                 }
               }
       }
     }
     ?>

<!-- <script src = "js/RegistratiePaginaFuncties.js"></script>
-->
<div class="registratie_pagina">

     <div class="body__registratie">
        <form action="registratie.php" method="post" id="DOM__regform">

                <h1>Registratie</h1>
                <p>Vul deze lijst in om een account aan te maken.</p>
                <div class="body__black__line">
                  <hr>
                </div>

                      <label for="nickname"><b>Nickname</b></label>
                      <input type="text" placeholder="oops" name="nickname" id ="nickname" required>

                      <label for="email"><b>Email</b></label>
                      <input type="email" placeholder="oops@oopsie" name="email" id="email" required>

                      <label for="voornaam"><b>Voornaam</b></label>
                      <input type="text" placeholder="oopsiedoops" name="voornaam" id="voornaam" required>

                      <label for="achternaam"><b>Achternaam</b></label>
                      <input type="text" placeholder="Euh" name="achternaam" id="achternaam" required>

                      <label for="psw"><b>password</b></label>
                      <input type="password" placeholder="whoops" name="psw" id="psw" required>

                      <meter min="0" max="4" id="password_strength_meter"></meter>
                      <p id="password_strength_text"> </p>
                      <p id="password_cracktime"></p>


                      <label for="psw_repeat"><b>Repeat Password</b></label>
                      <input type="password" placeholder="whoops" name="psw_repeat" id="psw_repeat" required>

                      <p>Door een account te maken gaat u akkoord met de Terms Of Service.</p>

                      <div class="registratie__btn">
                        <input class="registratie__btn" type="submit" value="Registreren" name="registratie__btn" id="registratie__button" disabled>
                      </div>

                    <div class="">
                        <p>Heeft u al een account? <a href="index.php">Log in</a></p>
                         <div class="body__black__line">
                          <hr>
                         </div>
                   </div>

          </form>
       </div>

    </div>

<script src="js/RegistratiePaginaFuncties.js"></script>

  </body>
</html>
