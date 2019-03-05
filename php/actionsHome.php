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

  if(mysqli_num_rows($result) > 0) {
      while($row = mysqli_fetch_assoc($result)) {
          $group = new Group($row["GroupID"], $row["GrName"], $row["GrDescription"], $row["GrOwner"]);
          array_push($groups, $group);
      }
  }
  $result->close();

  $invites = array();
  $statement = mysqli_prepare($con, "SELECT i.InviteID, i.SenderID, i.ReceiverID, concat(us.FirstName, ' ',us.LastName) 'sName', concat(ur.FirstName,' ', ur.LastName) 'rName', i.GroupID, g.GrName FROM invites AS i
    INNER JOIN users AS us ON i.SenderID = us.userID
    INNER JOIN users AS ur ON i.ReceiverID = ur.UserID
    INNER JOIN groups g ON i.GroupID = g.GroupID
    WHERE i.ReceiverID = ? AND i.Answer = '0'");
  mysqli_stmt_bind_param($statement, "i", $userID);
  if(!mysqli_stmt_execute($statement)) {
      $_SESSION["errormsg"] = "Er ging iets fout!";
      header("Location: ../home.php");
  }
  $result = $statement->get_result();

  if(mysqli_num_rows($result) > 0) {
      while($row = mysqli_fetch_assoc($result)) {
          $invite = new Invite($row["InviteID"], $row["SenderID"], $row["ReceiverID"], $row["sName"], $row["rName"], $row["GroupID"], $row["GrName"]);
          array_push($invites, $invite);
      }
  }
  $result->close();


  // Body voor groepen
  echo ("
      <div class=\"body__home--home\">
      <div class=\"body__home--groups body__home--boxes\">
      <div class=\"body__home--title\">
          <h2>Mijn groepen</h2>
      </div>
      <div class=\"item__group--row\">");
        foreach ($groups as $group) {
          echo ("
          <a onclick=\"courses($group->GrID)\" class=\"group__link\">
            <div class=\"group__link--content\">
              <div class=\"group__link--title\">
                <h3>$group->GrName</h3>
               </div>
              <div>
                <p>$group->GrDescr</p>
              </div>
            </div>
          </a>");
        }

  echo("
      <a id=\"dom__btn--newgroup\" class=\"group__link\">
        <div class=\"group__link--symbol\">
          <p>&#43;</p>
        </div>
      </a>
    </div>
    </div>

<div class=\"body__home--sidebar body__home--boxes\">
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

echo("</div></div></div><script src=\"js/modal.js\"></script>");

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


        $statement = mysqli_prepare($con, "UPDATE invites i SET i.Answer = '1' WHERE i.InviteID = ? AND i.ReceiverID = ? AND i.Answer = '0';");
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


        $statement = mysqli_prepare($con, "UPDATE invites i SET i.Answer = '2' WHERE i.InviteID = ? AND i.ReceiverID = ? AND i.Answer = '0';");
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

if(($_SERVER["REQUEST_METHOD"] == "POST") && isset($_POST["group"]) && isset($_POST["groupID"])) {

  session_start();
  if (!isset($_SESSION["UserID"])) {
    header("Location: index.php");
  }
  $userID = $_SESSION["UserID"];
  $_SESSION["GroupID"] = $_POST["groupID"];

  $con = mysqli_connect($host, $user, $pass, $db);
  $courses = array();

  $statement = mysqli_prepare($con, "SELECT c.CourseID, c.CrName, c.CrDescription, g.GrName, c.GroupID FROM courses c INNER JOIN groups g on g.GroupID = c.GroupID INNER JOIN UserGroups us ON us.GroupID = g.GroupID WHERE c.GroupID = ? AND us.UserID = ?;");
  mysqli_stmt_bind_param($statement, "ii", $_POST["groupID"], $userID);
  mysqli_stmt_execute($statement);
  $result = $statement->get_result();
  if(mysqli_num_rows($result) > 0) {
      while($row = mysqli_fetch_assoc($result)) {
          $course = new Course($row["CourseID"], $row["CrName"], $row["CrDescription"], $row["GrName"], $row["GroupID"]);
          array_push($courses, $course);
      }
  }else{
    echo ("
    <div class=\"body__home--home\">
      <div class=\"body__home--courses body__home--boxes\">
      <div class=\"body__home--title\">
          <h2>Kon geen vakken vinden voor deze groep</h2>
      </div>
    </div>
    <div class=\"body__home--sidebar body__home--boxes\">
      <div class=\"body__home--title\">
        <h2>Acties</h2>
      </div>
      <div class=\"item__group--coloum\">
        <div class=\"groups__controls\">
            <button type=\"button\" id=\"dom__btn--inviteUser\">Gebruiker toevoegen</button>
            <button type=\"button\" onclick=\"leaveGroup();\">Groep verlaten</button>
            <button type=\"button\" >Leden lijst</button>
        </div>
      </div>
      <div class=\"item__group--coloum\"></div>
    </div><script src=\"js/modalCourses.js\">");
    exit;
  }
  $result->close();
  $GroupName = $courses[0]->crGroupName;

    echo ("
    <div class=\"body__home--home\">
    <div class=\"body__home--courses body__home--boxes\">
    <div class=\"body__home--title\">
        <h2>$GroupName</h2>
    </div>
    <div class=\"item__group--row\">");
    foreach ($courses as $course) {
      echo ("
      <a onclick=\"course($course->crID)\" class=\"group__link\">
        <div class=\"group__link--content\">
          <div class=\"group__link--title\">
            <h3>$course->crName</h3>
          </div>
          <div>
            <p>$course->crDescr</p>
          </div>
        </div>
      </a>");
    };

echo("</div></div><div class=\"body__home--sidebar body__home--boxes\">
        <div class=\"body__home--title\">
            <h2>Acties</h2>
        </div>
        <div class=\"item__group--coloum\">
          <div class=\"groups__controls\">
              <button type=\"button\" id=\"dom__btn--inviteUser\">Gebruiker toevoegen</button>
              <button type=\"button\" onclick=\"leaveGroup();\">Groep verlaten</button>
              <button type=\"button\">Leden lijst</button>
          </div>
        </div>");
  echo("</div></div></div><script src=\"js/modalCourses.js\"></script>");
  exit;
}

if(($_SERVER["REQUEST_METHOD"] == "POST") && isset($_POST["grNaam"]) && isset($_POST["grDescription"])) {

  session_start();
  $con = mysqli_connect($host, $user, $pass, $db);

  $userID = $_SESSION["UserID"];
  $grName = $con->escape_string($_POST["grNaam"]);
  $grDescription = $con->escape_string($_POST["grDescription"]);

  if(!$con) {
    header("Location: ../home.php");
  }else{
    $statement = mysqli_prepare($con, "INSERT INTO groups(`GrName`,`GrDescription`,`GrOwner`) VALUES(?,?,?);");
    mysqli_stmt_bind_param($statement, "ssi", $grName, $grDescription, $userID);
    if(mysqli_stmt_execute($statement)) {
      $newGroupId = $con->insert_id;

      $statement = mysqli_prepare($con, "INSERT INTO UserGroups(GroupID, UserID) VALUES (?, ?);");
      mysqli_stmt_bind_param($statement, "ii", $newGroupId , $userID);
      if(mysqli_stmt_execute($statement)) {
        header("Location: ../home.php");
      }

    }else{
    header("Location: ../home.php");
    }
  }
}

if(($_SERVER["REQUEST_METHOD"] == "POST") && isset($_POST["userMail"])) {

  session_start();
  $con = mysqli_connect($host, $user, $pass, $db);

  if(!isset($_SESSION["GroupID"])) {
    $_SESSION["errormsg"] = "Er ging iets fout!";
    header("Location: ../home.php");
  }else{
      $userID = $_SESSION["UserID"];
      $groupID = $_SESSION["GroupID"];
      $userMail = $con->escape_string($_POST["userMail"]);

      // Kijken of gebruiker bestaat en id ophalen
      $statement = mysqli_prepare($con, "SELECT UserID FROM users WHERE Email = ?;");
      mysqli_stmt_bind_param($statement, "s", $userMail);
      mysqli_stmt_execute($statement);
      $result = $statement->get_result();
      if(mysqli_num_rows($result) == 1) {
          while($row = mysqli_fetch_assoc($result)) {
              $invitedUserID = $row["UserID"];
          }
          $result->close();

          if($invitedUserID == $userID) {
            $_SESSION["errormsg"] = "Je kan geen uitnoding naar jezelf sturen!";
            header("Location: redirect.php?home=1");
            exit;
          }
          //Kijken of gebruiker niet al in groep zit
          $statement = mysqli_prepare($con, "SELECT * FROM UserGroups WHERE GroupID = ? AND UserID = ?;");
          mysqli_stmt_bind_param($statement, "ii", $groupID, $invitedUserID);
          mysqli_stmt_execute($statement);
          $result = $statement->get_result();
          if(mysqli_num_rows($result) > 0) {
            $_SESSION["errormsg"] = "Deze gebruiker zit al in deze groep!";
            header("Location: redirect.php?home=1");
            exit;
          }

          $result->close();
          //Kijken of gebruiker al invite heeft voor de groep
          $statement = mysqli_prepare($con, "SELECT InviteID FROM invites WHERE Answer = '0' && ReceiverID = ? && GroupID = ?;");
          mysqli_stmt_bind_param($statement, "ii", $invitedUserID, $groupID);
          mysqli_stmt_execute($statement);
          $result = $statement->get_result();
          if(mysqli_num_rows($result) > 1) {
            $result->close();
            $_SESSION["errormsg"] = "Deze gebruiker heeft al een uitnoding voor deze groep!";
            header("Location: redirect.php?home=1");
            exit;
          }else{
            //Invite toevoegen
            $result->close();
            $statement = mysqli_prepare($con, "INSERT INTO invites (`SenderID`,`ReceiverID`,`GroupID`) VALUES(?,?,?);");
            mysqli_stmt_bind_param($statement, "iii", $userID, $invitedUserID, $groupID);
            if(mysqli_stmt_execute($statement)) {
              header("Location: ../home.php");
            }else{
              $_SESSION["errormsg"] = "Er ging iets fout !";
              header("Location: redirect.php?home=1");
              exit;
            }
          }
      }else{
        $_SESSION["errormsg"] = "Deze gebruiker bestaat niet!";
        header("Location: redirect.php?home=1");
        exit;
      }
  }

}

if(($_SERVER["REQUEST_METHOD"] == "POST") && isset($_POST["leaveGroup"])) {

  session_start();
  $con = mysqli_connect($host, $user, $pass, $db);

  if(!isset($_SESSION["GroupID"])) {
    $_SESSION["errormsg"] = "Er ging iets fout!";
    header("Location: redirect.php?home=1");
    exit;
  }

    $userID = $_SESSION["UserID"];
    $groupID = $_SESSION["GroupID"];

    $statement = mysqli_prepare($con, "DELETE FROM UserGroups WHERE UserID = ? AND GroupID = ?;");
    mysqli_stmt_bind_param($statement, "ii", $userID, $groupID);
    if(mysqli_stmt_execute($statement)) {
      echo "success!";
    }else{
      $_SESSION["errormsg"] = "Er ging iets fout!";
      header("Location: redirect.php?home=1");
      exit;
    }
}


?>
