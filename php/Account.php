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
              $psw_ori = $row["Password"];
             }
          }

//check als form gesubmit is
if(isset($_POST["pswoud"]))
{

  $pswoud=real_escape($_POST["pswoud"]);
      if(!password_verify($pswoud,$psw_ori))
       {
        $_SESSION['msg'] ='Oud paswoord is niet correct.';
        echo $_SESSION['msg'];
       }else
              {
                if($_POST["psw"] != $_POST["psw_repeat"])
                {
                  $_SESSION['msg'] = 'De twee paswoorden zijn niet identiek.';
                  echo $_SESSION['msg'];
                }else{

                      //paswoord in database
                          $statement4 = mysqli_prepare ($con, ("UPDATE users SET Password = ? WHERE users.UserID = ?"));
                          mysqli_stmt_bind_param($statement4, "ss", $psw,$_POST["UserID"]);
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

}else{
echo 'test';

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

           <div id=\"DOM_paswreset\" class=\"Account__paswreset\">
                       <form action=\"php\account.php\" name=\"psw_resetform\" class=\"psw__resetform\" method=\"post\">
                           <label for=\"pswoud\"><b>Oud paswoord</b></label>
                           <input type=\"password\" placeholder=\"wachtwoordoud\" class=\"account__input\" name=\"pswoud\" id=\"pswoud\" required>




                           <label for=\"psw\"><b>paswoord</b></label>
                           <input type=\"password\" placeholder=\"wachtwoord\" class=\"account__input\" name=\"psw\" id=\"DOM__psw\" required>


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
