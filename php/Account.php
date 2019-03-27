<?php
require "db.php";
//check als form gesubmit is
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["pswoud"]) && isset($_POST["psw"]) && isset($_POST["psw_repeat"])) {
  $con = mysqli_connect($host, $user, $pass, $db);
  $pswoud  = $con->real_escape_string($_POST["pswoud"]);

  session_start();
  if (!isset($_SESSION["UserID"])) {
    header("Location: ../index.php");
  }
  $userID = $_SESSION["UserID"];
  $statement1 = mysqli_prepare($con,"SELECT Password FROM users where UserID = ?;");
  mysqli_stmt_bind_param($statement1,"i",$userID);
  mysqli_stmt_execute($statement1);
  $result1 = $statement1->get_result();

  if(mysqli_num_rows($result1)>=1)
  {
    while($row = mysqli_fetch_assoc($result1))
     {
      $psw_ori = $row["Password"];
     }
  }else{
    $_SESSION['msg'] ='Er ging iets fout bij ophalen account gegevens';
    echo $_SESSION['msg'];
    exit;
  }

  if(!password_verify($pswoud,$psw_ori)) {
      $_SESSION['msg'] ='Oud paswoord is niet correct.';
      echo $_SESSION['msg'];
      exit;
  }else{
      if($_POST["psw"] != $_POST["psw_repeat"]) {
      $_SESSION['msg'] = 'De twee paswoorden zijn niet identiek.';
      echo $_SESSION['msg'];
      exit;
      }else{
            //paswoord in database
            $pswHashed = password_hash(($con->real_escape_string($_POST['psw'])),PASSWORD_DEFAULT);
            $statement4 = mysqli_prepare ($con, ("UPDATE users SET Password = ? WHERE UserID = ?;"));
            mysqli_stmt_bind_param($statement4, "si", $pswHashed, $userID);
            if(mysqli_stmt_execute($statement4))
            {
             $_SESSION['msg'] = "Het passwoord is succesvol veranderd.";
             echo $_SESSION['msg'];
             header("Location: ../index.php");
            }else{
              $_SESSION['msg'] = 'Paswoord kon niet veranderd worden.';
              echo $_SESSION['msg'];
              echo $statement->error;
            }
        }
    }
}
 ?>
