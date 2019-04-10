<?php
require 'classes.php';
require 'db.php';
//
// Login box
//

if(($_SERVER["REQUEST_METHOD"] == "POST") && isset($_POST["loginBody"])) {

  //JSON data
  $outputString = "";
  $data = new jsonData(0, "");

  //Login output
  $outputString .= ("
    <div class=\"body__loginbox--title\">
      <h1>Welkom bij PVSTS</h1>
      <p>Inloggen</p>
    </div>
    <form action=\"#\" method=\"post\">
          <div class=\"form-group body__loginbox--groups\">
            <input type=\"email\" name=\"email\" class=\"input__login\" placeholder=\"e-mail\" required>
          </div>
          <div class=\"form-group body__loginbox--groups\">
            <input type=\"password\" name=\"password\" class=\"input__login\" placeholder=\"wachtwoord\" required>
         </div>
          <a class=\"link__form--primary\" id=\"dom__link--reset\">Wachtwoord vergeten?</a>
         <input type=\"submit\" name=\"btnLogin\" value=\"Inloggen\" class=\"btn__form--primary btn btn__login\">
    </form>
    <a href=\"registratie.php\"><button value=\"Registreren\" class=\"btn__form--primary btn btn__register\">Registeren</button></a>



  ");
  $data->output = $outputString;
  echo json_encode($data);

}

//
// Password reset box
//

if(($_SERVER["REQUEST_METHOD"] == "POST") && isset($_POST["passwordResetPage"])) {

  //JSON data
  $outputString = "";
  $data = new jsonData(0, "");

  $outputString .="
      <div class=\"body__loginbox--title\">
        <h1>Wachtwoord vergeten</h1>
        <p>Geef het e-mail adres van uw account in:</p>
      </div>
      <form id=\"dom__form--passwordReset\">
           <div class=\"form-group body__loginbox--groups\">
              <input id=\"dom__reset--email\" type=\"email\" name=\"E-mail\" class=\"input__login\" placeholder=\"E-mail\" required>
           </div>
           <input type=\"submit\" name=\"btnLogin\" value=\"Versturen\" class=\"btn__form--primary btn__login\">
      </form>
      <a class=\"link__form--primary\" id=\"dom__link--login\">Inloggen</a>";

  $data->output = $outputString;
  echo json_encode($data);


}

//
// Wachtwoord vergeten link verzenden
//

if(($_SERVER["REQUEST_METHOD"] == "POST") && isset($_POST["passwordReset"]) && isset($_POST["email"])) {

  //JSON data
  $outputString = "";
  $data = new jsonData(0, "");

  //Connectie met database
  if(!$con = mysqli_connect($host, $user, $pass, $db)) {
    $data->returnCode = 402;
    echo json_encode($data);
    exit;
  }

  //Reset token aanmaken
  $token = bin2hex(random_bytes(100));
  $email = $con->escape_string($_POST["email"]);

  //Kijken of gebruiker bestaat en ID opslaan
  $statement = mysqli_prepare($con, "SELECT UserID FROM users u WHERE u.Email = ?");
  mysqli_stmt_bind_param($statement, "s", $email);
  if(!mysqli_stmt_execute($statement)) {
    $data->returnCode = 401;
    echo json_encode($data);
    exit;
  }

  $result = $statement->get_result();
  if(mysqli_num_rows($result) == 1) {
      while($row = mysqli_fetch_assoc($result)) {
          $userID = ($row["UserID"]);
      }
  }else{
    $data->returnCode = 401;
    echo json_encode($data);
    exit;
  }

  //Token in database toevoegen
  $statement = mysqli_prepare($con, "INSERT INTO recoveryKeys (`RecoveryToken`, `Expiredate`, `UserID`) VALUES(?, DATE_ADD(NOW(), INTERVAL 7 DAY), ?);");
  mysqli_stmt_bind_param($statement, "si", $token, $userID);
  if(!mysqli_stmt_execute($statement)) {
    $data->returnCode = 401;
    echo json_encode($data);
    exit;
  }

  $link = "<a href='https://www.dev.dragonq1.be/wachtwoordvergeten.php?token=$token'>wachtwoord resetten</a>";
  $message = '<!DOCTYPE html><html lang="nl"><body>
  <p> Hallo! Je hebt bij PVTS een wachtwoord reset aangevraagd.</p><p>Heb je dit niet gedaan, dan kan je deze e-mail gewoon verwijderen.</p>
  <p>Heb je dit wel gedaan dan kan je volgende link volgen om je wachtwoord te resetten: '."$link".'</p></body></html>';
  $message = wordwrap($message, 70, "\r\n");
  $header = 'From: noreply@pvts.be' . "\r\n";
  $header .= 'Content-type: text/html; charset=iso-8859-1';
  $subject = 'Wachtwoord vergeten';

  // Send
  mail($email, $subject, $message, $header);


  $data->output = $outputString;
  echo json_encode($data);


}

//
// Wachtwoord resetten
//
if(($_SERVER["REQUEST_METHOD"] == "POST") && (isset($_POST["passwordReset"])) && (isset($_POST["password1"])) && (isset($_POST["password2"]))) {
  //JSON data
  $outputString = "";
  $data = new jsonData(0, "");
  session_start();
  if(isset($_SESSION["UserID"])) {
    $userID = $_SESSION["UserID"];
  }else{
    $data->returnCode = 702;
    echo json_encode($data);
    exit;
  }
  //Connectie met database
  if(!$con = mysqli_connect($host, $user, $pass, $db)) {
    $data->returnCode = 402;
    echo json_encode($data);
    exit;
  }
  $password1 = $con->escape_string($_POST["password1"]);
  $password2 = $con->escape_string($_POST["password2"]);
  if($password1 != $password2) {
    $data->returnCode = 301;
    echo json_encode($data);
    exit;
  }else{
    //Nieuw wachtwoord in db zetten
    $password = password_hash($password1, PASSWORD_DEFAULT);
    $statement = mysqli_prepare($con, "UPDATE users SET Password = ? WHERE UserID = ?;");
    mysqli_stmt_bind_param($statement, "si", $password, $userID);
    if(!mysqli_stmt_execute($statement)) {
      $data->returnCode = 401;
      echo json_encode($data);
      exit;
    }
    if($statement->affected_rows != 1) {
      //Wachtwoord is niet gereset
      $data->returnCode = 302;
      echo json_encode($data);
      exit;
    }

    //Token ongeldig maken
    $statement = mysqli_prepare($con, "UPDATE recoveryKeys SET Used = 1 WHERE UserID = ?;");
    mysqli_stmt_bind_param($statement, "i", $userID);
    if(!mysqli_stmt_execute($statement)) {
      $data->returnCode = 401;
      echo json_encode($data);
      exit;
    }
    if($statement->affected_rows == 1) {
      //Token is ongeldig gemaakt
      $data->returnCode = 0;
      echo json_encode($data);
      exit;
    }else{
      $data->returnCode = 303;
      echo json_encode($data);
      exit;
    }
  }
  echo json_encode($data);
  exit;
}



 ?>
