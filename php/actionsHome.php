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
    WHERE ur.UserID = ?");
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
        <a onclick=\"invite()\" class=\"item__group--invite\">
          <div>
            <h3>Uitnoding voor $invite->GroupName</h3>
            <p>Je hebt een uitnoding ontvangen van $invite->SenderName voor de groep $invite->GroupName</p>
          </div>
        </a>
");
      }

echo("</div></div>");

}


?>
