<?php
if (($_SERVER['REQUEST_METHOD']=='POST') && isset($_POST['nickname']) && isset($_POST['email']) && isset($_POST['firstname'])
  && isset($_POST['lastname']) && isset($_POST['password1']) && isset($_POST['password2'])) {

  require 'db.php';
  require 'classes.php';
  if(!$con = mysqli_connect($host, $user, $pass, $db)) {
    $data->returnCode = 402;
    echo json_encode($data);
    exit;
   }

  //variabelen
  $data = new jsonData(0, "");
  $nickname = $con->real_escape_string($_POST['nickname']);
  $email = $con->real_escape_string($_POST['email']);
  $voornaam = $con->real_escape_string($_POST['firstname']);
  $achternaam = $con->real_escape_string($_POST['lastname']);



  //Wachtwoorden nakijken
  if ($_POST['password1'] != $_POST['password2']) {
    $data->returnCode = 301;
    echo json_encode($data);
    exit;
  }

    //Wachtwoord hashen
    $pswhashed = password_hash(($con->real_escape_string($_POST['password1'])),PASSWORD_DEFAULT);

    //email checken
    $statement = mysqli_prepare ($con,("SELECT Email FROM users where Email LIKE ?"));
    mysqli_stmt_bind_param($statement,"s",$email);
    if(!mysqli_stmt_execute($statement)) {
     $data->returnCode = 401;
     echo json_encode($data);
     exit;
    }
    $resultaat =$statement->get_result();
    if(mysqli_num_rows($resultaat) > 0) {
       $data->returnCode = 201;
       echo json_encode($data);
       exit;
    }

    //Nickname checken
    $statement = mysqli_prepare($con,("SELECT Nickname FROM users where Nickname LIKE ?"));
    mysqli_stmt_bind_param($statement,"s",$nickname);
    if(!mysqli_stmt_execute($statement)) {
       $data->returnCode = 401;
       echo json_encode($data);
       exit;
    }
     $resultaat = $statement->get_result();
     if(mysqli_num_rows($resultaat)>0) {
          $data->returnCode = 202;
          echo json_encode($data);
          exit;
      }else{
        //In database zetten en verify token doorsturen en doorsturen via mail
        $statement = mysqli_prepare ($con, ("INSERT INTO users (Email ,Password,LastName,FirstName,Nickname) VALUES (?,?,?,?,?)"));
        mysqli_stmt_bind_param($statement, "sssss", $email,$pswhashed,$achternaam,$voornaam,$nickname);
        if(!mysqli_stmt_execute($statement)) {
          $data->returnCode = 401;
          echo json_encode($data);
          exit;
        }else{
          //Token aanmaken en in db zetten
          $UserID = $con->insert_id;
          $token = bin2hex(random_bytes(100));
          $statement = mysqli_prepare ($con, ("INSERT INTO verifyKeys (VerifyKeyToken, UserID) VALUES (?, ?);"));
          mysqli_stmt_bind_param($statement, "si", $token, $UserID);
          if(!mysqli_stmt_execute($statement)) {
             $data->returnCode = 401;
             echo json_encode($data);
             exit;
          }
          //Mail versturen
          $link = "<a href='https://www.dev.dragonq1.be/index.php?token=$token'>account verifieren</a>";
          $message = '<!DOCTYPE html><html lang="nl"><body>
          <h1>Welkom bij PVTS.</h1><p>Je hebt je geregistreerd bij PVTS, heb je dit niet gedaan dan kan je deze e-mail gewoon verwijderen.</p>
          <p>Heb je dit wel gedaan dan kan je volgende link volgen om je account te verifieren zodat je kan inloggen: '."$link".'</p></body></html>';
          $message = wordwrap($message, 70, "\r\n");
          $header = 'From: noreply@pvts.be' . "\r\n";
          $header .= 'Content-type: text/html; charset=iso-8859-1';
          $subject = 'Welkom bij PVTS';

          // Send
          mail($email, $subject, $message, $header);
          $data->output = $token;
          echo json_encode($data);
          exit;
        }
      }
   }
 ?>
