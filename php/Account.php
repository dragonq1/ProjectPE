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
  $statement1 = mysqli_prepare($con,"SELECT LastName,FirstName,NickName,Email,Password FROM users where UserID = ?" );
  mysqli_stmt_bind_param($statement1,"s",$userID);
  mysqli_stmt_execute($statement1);
  $result1 = $statement1->get_result();


if(mysqli_num_rows($result1)>=1)
{
  while($row = mysqli_fetch_assoc($result1))
   {
    $firstname = $row["FirstName"];
    $lastname = $row["LastName"];
    $nickname = $row["NickName"];
    $email = $row["Email"];
    $pswori = $row["Password"];
   }
}

//check als oud paswoord is ingegeven
if(!isset($_POST["pswoud"]))
{
$_SESSION['msg'] ='Oud paswoord is niet ingegeven.';
echo $_SESSION['msg'];
}else{
  $pswoud= $_POST["pswoud"];
      if(!password_verify($pswoud,$pswori)){
        $_SESSION['msg'] ='Oud paswoord is niet correct.';
        echo $_SESSION['msg'];
      }else
      {
        if($_POST["psw"] != $_POST["psw_repeat"])
        {
          $_SESSION['msg'] = 'De twee paswoorden zijn niet identiek.';
          echo $_SESSION['msg'];

        }else{
        $statement4 = mysqli_prepare ($con, ("UPDATE dragv_dev.users SET (Password) = (?) WHERE users.UserID = (?)"));
        mysqli_stmt_bind_param($statement4, "ss", $pswhashed,$_POST["UserID"]);
        if(mysqli_stmt_execute($statement4) == true)
         {
           $_SESSION['msg'] = "Het passwoord is succesvol veranderd.";
           echo $_SESSION['msg'];
           header("Location: index.php");
         } else
             {
               $_SESSION['msg'] = 'Paswoord kon niet veranderd worden.';
               echo $_SESSION['msg'];
               echo $statement->error;
             }

      }

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
           <div class=\"Account__paswreset\">
                       <form name=\"psw_resetform\" class=\"psw__resetform\" method=\"post\">
                           <label for=\"pswoud\"><b>Oud paswoord</b></label>
                           <input type=\"password\" placeholder=\"wachtwoordoud\" class=\"account__input\" name=\"pswoud\" id=\"pswoud\" required>




                           <label for=\"psw\"><b>paswoord</b></label>
                           <input type=\"password\" placeholder=\"wachtwoord\" class=\"account__input\" name=\"psw\" id=\"psw\" required>


                           <meter min=\"0\" max=\"4\" id=\"password_strength_meter\"></meter>
                           <p id=\"password_strength_text\"> </p>
                           <p id=\"password_cracktime\"></p>


                           <label for=\"psw_repeat\"><b>Herhaal paswoord</b></label>
                           <input type=\"password\" placeholder=\"wachtwoord herhalen\" class=\"account__input\" name=\"psw_repeat\" id=\"psw_repeat\" required>

                           <div class=\"account__btn\">
                             <input class=\"account__btn\" type=\"submit\" value=\"Paswoord veranderen\" name=\"Account__btn\" id=\"account__button\" disabled>
                           </div>

                      </form>
                </div>
            <script src=\"js/account.js\"></script>
");


}
 ?>
