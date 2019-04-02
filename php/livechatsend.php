<?php


//Live Chat ophalen berichten
if(($_SERVER["REQUEST_METHOD"] == "POST") && isset($_POST[""])) {
  session_start();
  $con = mysqli_connect($host, $user, $pass, $db);
  $userID = $_SESSION["UserID"];

//Uitloggen indien niet geconnect
  if(!$con) {
    header("Location: ../home.php");
  }else{

    $userID = $_SESSION["UserID"];
    $groupID = $_SESSION["GroupID"];

    $statement = mysqli_prepare($con, "SELECT chatMessages.chatMessage,chatMessages.chatSendtime,users.Nickname from chatMessages left join users.UserID = chatMessages.userID WHERE chatMessages.groupID = ? AND chatMessages.userID = ? ORDER BY chatSendtime desc;");
    mysqli_stmt_bind_param($statement, "ii", $groupID, $userID);

    if(!mysqli_stmt_execute($statement)) {
      $_SESSION["errormsg"] = "Er ging iets fout bij het verzenden van de chat!";
      exit;
    }else{


         }

 }
}
 ?>
