<?php
require 'classes.php';
require 'db.php';
if(($_SERVER["REQUEST_METHOD"] == "POST") && isset($_POST["homeMenu"])) {

  session_start();
  if (!isset($_SESSION["UserID"])) {
    header("Location: index.php");
  }
  $userID = $_SESSION["UserID"];

  $con = mysqli_connect($host, $user, $pass, $db);
  $groups = array();

  $statement = mysqli_prepare($con, "SELECT g.GroupID, g.GrName, g.GrDescription, g.GrOwner FROM groups g INNER JOIN UserGroups ug ON g.GroupID = ug.GroupID WHERE ug.UserID = ?");
  mysqli_stmt_bind_param($statement, "i", $userID);
  mysqli_stmt_execute($statement);
  $result = $statement->get_result();

  if(mysqli_num_rows($result) >= 1) {
      while($row = mysqli_fetch_assoc($result)) {
          $group = new Group($row["GroupID"], $row["GrName"], $row["GrDescription"], $row["GrOwner"]);
          array_push($groups, $group);
      }
  }else{
    header("index.php");
  }
  $result->close();

  $invites = array();
  $statement = mysqli_prepare($con, "SELECT i.InviteID, i.SenderID, i.ReceiverID, concat(us.FirstName, ' ',us.LastName) 'sName', concat(ur.FirstName,' ', ur.LastName) 'rName', i.GroupID, g.GrName FROM invites AS i
    INNER JOIN users AS us ON i.SenderID = us.userID
    INNER JOIN users AS ur ON i.ReceiverID = ur.UserID
    INNER JOIN groups g ON i.GroupID = g.GroupID
    WHERE ur.UserID = ? AND i.Answer IS NULL");
  mysqli_stmt_bind_param($statement, "i", $userID);
  mysqli_stmt_execute($statement);
  $result = $statement->get_result();

  if(mysqli_num_rows($result) >= 1) {
      while($row = mysqli_fetch_assoc($result)) {
          $invite = new Invite($row["InviteID"], $row["SenderID"], $row["ReceiverID"], $row["sName"], $row["rName"], $row["GroupID"], $row["GrName"]);
          array_push($invites, $invite);
      }
  }else{
    header("index.php");
  }
  $result->close();


  // Body voor groepen
  echo ("
      <div class=\"body__home--home\">
      <div class=\"body__home--groups\">
      <div class=\"body__home--title\">
          <h2>Mijn groepen</h2>
      </div>
      <div class=\"item__group--row\">");
        foreach ($groups as $group) {
          echo ("
          <a onclick=\"courses()\" class=\"item__group--link\">
            <div>
              <h3>$group->GrName</h3>
              <p>$group->GrDescr</p>
            </div>
          </a>");
        }

  echo("<a onclick=\"newGroup()\" class=\"item__group--link\">
        <div>
          <h3>+Groep</h3>
          <p>Klik hier om een nieuwe groep aan te maken</p>
        </div>
      </a>
    </div>
    </div>

<div class=\"body__home--invites\">
    <div class=\"body__home--title\">
        <h2>Meldingen</h2>
    </div>
    <div class=\"item__group--coloum\">");
      foreach ($invites as $invite) {
        echo ("
          <div class=\"item__group--invite\">
            <div>
              <h3>Uitnoding voor $invite->GroupName</h3>
              <p>Je hebt een uitnoding ontvangen van $invite->SenderName voor de groep $invite->GroupName</p>
            </div>
            <div class=\"invites__btn--response\">
              <button class=\"btn__border--green\" onclick=\"acceptInvite($invite->InvID)\">&radic;</button>
              <button class=\"btn__border--red\" onclick=\"declineInvite($invite->InvID)\">x</button>
            </div>
          </div>
");
      }

echo("</div></div>");

}

if(($_SERVER["REQUEST_METHOD"] == "POST") && isset($_POST["acceptInvite"]) && isset($_POST["inviteID"])) {
    //Invite gegevens ophalen


    // Nakijken of invite van juiste gebruiker is en nog niet beantwoord
    session_start();
    if (!isset($_SESSION["UserID"])) {
      header("Location: index.php");
    }
    $userID = $_SESSION["UserID"];
    $con = mysqli_connect($host, $user, $pass, $db);

    $statement = mysqli_prepare($con, "SELECT GroupID FROM invites i WHERE i.ReceiverID = ? AND i.InviteID = ?;");
    mysqli_stmt_bind_param($statement, "ii", $userID, $_POST["inviteID"]);
    mysqli_stmt_execute($statement);
    $result = $statement->get_result();
    if(mysqli_num_rows($result) == 1) {
        while($row = mysqli_fetch_assoc($result)) {
            $groupID = $row["GroupID"];
        }


        $statement = mysqli_prepare($con, "UPDATE invites i SET i.Answer = 1 WHERE i.InviteID = ? AND i.ReceiverID = ? AND i.Answer IS NULL;");
        mysqli_stmt_bind_param($statement, "ii", $_POST["inviteID"], $userID);
        mysqli_stmt_execute($statement);
        if($statement->affected_rows == 1) {

          $statement = mysqli_prepare($con, "INSERT INTO UserGroups(GroupID, UserID) VALUES (?, ?);");
          mysqli_stmt_bind_param($statement, "ii", $groupID , $userID);
          mysqli_stmt_execute($statement);

          if($statement->affected_rows == 1) {
              echo "Succes!";
          }

        }else{
          echo "Something went wrong!";
          exit;
        }

    }else{
      echo "Something went wrong!";
      exit;
    }





}

if(($_SERVER["REQUEST_METHOD"] == "POST") && isset($_POST["declineInvite"]) && isset($_POST["inviteID"])) {
    //Invite gegevens ophalen


    // Nakijken of invite van juiste gebruiker is en nog niet beantwoord
    session_start();
    if (!isset($_SESSION["UserID"])) {
      header("Location: index.php");
    }
    $userID = $_SESSION["UserID"];
    $con = mysqli_connect($host, $user, $pass, $db);

    $statement = mysqli_prepare($con, "SELECT GroupID FROM invites i WHERE i.ReceiverID = ? AND i.InviteID = ?;");
    mysqli_stmt_bind_param($statement, "ii", $userID, $_POST["inviteID"]);
    mysqli_stmt_execute($statement);
    $result = $statement->get_result();
    if(mysqli_num_rows($result) == 1) {
        while($row = mysqli_fetch_assoc($result)) {
            $groupID = $row["GroupID"];
        }


        $statement = mysqli_prepare($con, "UPDATE invites i SET i.Answer = 0 WHERE i.InviteID = ? AND i.ReceiverID = ? AND i.Answer IS NULL;");
        mysqli_stmt_bind_param($statement, "ii", $_POST["inviteID"], $userID);
        mysqli_stmt_execute($statement);
        if($statement->affected_rows == 1) {

        }else{
          echo "Something went wrong!";
          exit;
        }

    }else{
      echo "Something went wrong!";
      exit;
    }





}


?>
