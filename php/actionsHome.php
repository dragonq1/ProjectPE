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
  if(!isset($_POST["homeSidebar"])) {
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
  echo("</div></div></div><script src=\"js/modalsHome.js\"></script>");
  }else{
    foreach ($groups as $group) {
      echo("<li><a onclick=\"courses($group->GrID);\">$group->GrName</a></li>");
    }
  }
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
          $statement = mysqli_prepare($con, "INSERT INTO UserGroups(GroupID, UserID, UserRank) VALUES (?, ?, 3);");
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
//
// Group inladen
//
if(($_SERVER["REQUEST_METHOD"] == "POST") && isset($_POST["group"]) && isset($_POST["groupID"])) {
  session_start();
  if (!isset($_SESSION["UserID"])) {
    header("Location: index.php");
  }
  $userID = $_SESSION["UserID"];
  $_SESSION["GroupID"] = $_POST["groupID"];
  $con = mysqli_connect($host, $user, $pass, $db);
  $courses = array();
  //Nakijken of gebruiker tot groep behoort
  $statement = mysqli_prepare($con, "SELECT * FROM UserGroups WHERE UserID = ? AND GroupID = ?;");
  mysqli_stmt_bind_param($statement, "ii",  $userID,  $_POST["groupID"]);
  mysqli_stmt_execute($statement);
  $result = $statement->get_result();
  if(mysqli_num_rows($result) != 1) {
    echo "403";
    exit;
  }
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
      <div class=\"body__home--courses body__home--boxes\" id=\"groups-mainbox\">
        <div class=\"body__home--title\">
            <h2>Kon geen vakken vinden voor deze groep</h2>
        </div>
      <div class=\"item__group--row\">
        <a id=\"dom__btn--newCourse\" class=\"group__link\">
          <div class=\"group__link--symbol\">
              <p>&#43;</p>
          </div>
        </a>
      </div>
    </div>
    <div class=\"body__home--sidebar body__home--boxes\">
      <div class=\"body__home--title\">
        <h2>Acties</h2>
      </div>
        <div class=\"item__group--coloum\">
          <div class=\"groups__controls\">
          <button type=\"button\" id=\"dom__btn--members\">Leden lijst</button>
          <button type=\"button\" id=\"dom__btn--inviteUser\">Gebruiker toevoegen</button>
          <button type=\"button\" id=\"dom__btn--kickUser\">Gebruiker verwijderen</button>
          <button type=\"button\" id=\"dom__btn--leaveGroup\">Groep verlaten</button>
          <button type=\"button\" id=\"dom__btn--deleteGroup\">Groep verwijderen</button>
          </div>
        </div>
      <div class=\"item__group--coloum\"></div>
    </div><script src=\"js/modalCourses.js\"></script>");
    exit;
  }
  $result->close();
  $GroupName = $courses[0]->crGroupName;
    echo ("
    <div class=\"body__home--home\">
      <div class=\"body__home--courses body__home--boxes\" id=\"groups-mainbox\">
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
echo(" <a id=\"dom__btn--newCourse\" class=\"group__link\">
        <div class=\"group__link--symbol\">
          <p>&#43;</p>
        </div>
      </a>
</div></div><div class=\"body__home--sidebar body__home--boxes\">
        <div class=\"body__home--title\">
            <h2>Acties</h2>
        </div>
        <div class=\"item__group--coloum\">
          <div class=\"groups__controls\">
              <button type=\"button\" id=\"dom__btn--members\">Leden lijst</button>
              <button type=\"button\" id=\"dom__btn--inviteUser\">Gebruiker toevoegen</button>
              <button type=\"button\" id=\"dom__btn--kickUser\">Gebruiker verwijderen</button>
              <button type=\"button\" id=\"dom__btn--leaveGroup\">Groep verlaten</button>
              <button type=\"button\" id=\"dom__btn--deleteGroup\">Groep verwijderen</button>
          </div>
        </div>");
  echo("</div>

  <div id=\"DOM__livechat__body--main\" class=\"body__home--sidebar body__home--boxes livechat__body--main\">

    <div id=\"DOM__livechat__title\" class=\"body__home--title livechat__title\">
      <a id=\"DOM__livechat__title--\" class=\"livechat__title--anchor\" onclick=\"openchat();\" >Live-Chat</a>
    </div>

    <div id=\"DOM__livechat__body\" class=\"livechat__body\">
      <div class=\"livechat__body--messages\">

      </div>
      <form id=\"DOM__livechat__form\">
          <div  class=\"livechat__body--input\">
          <div class=\"livechat__body--input\">
               <textarea id=\"DOM__livechat__text\" maxlength=\"256\" class=\"livechat__textinput\" required></textarea>
          </div>
          <div class=\"livechat__body--sendbutton\">
            <input type=\"submit\" id=\"DOM__livechat__button\" name=\"livechat_btn\" value=\"Verzenden\" class=\"livechat__submitbtn\">
          </div>
         </form>
      <div class=\"livechat__body--closechat\">
        <input type=\"button\" id=\"DOM__livechat__close\" name=\"livechat_closebtn\" value=\"Sluit livechat\" onclick=\"closechat()\" class=\"livechat__submitbtn\">
      </div>
    </div>
  </div>
  <script src=\"js/livechatscripts.js\"></script>
 <script> $(document).ready(function(){ $.getScript(\"js/modalCourses.js\")}); </script>
</div></div>");
  exit;
}
if(($_SERVER["REQUEST_METHOD"] == "POST") && isset($_POST["grName"]) && isset($_POST["grDescription"])) {
  session_start();
  $con = mysqli_connect($host, $user, $pass, $db);
  $userID = $_SESSION["UserID"];
  $grName = $con->reaL_escape_string($_POST["grName"]);
  $grDescription = $con->real_escape_string($_POST["grDescription"]);
  if(!$con) {
    header("Location: ../home.php");
  }else{
    $statement = mysqli_prepare($con, "INSERT INTO groups(`GrName`,`GrDescription`,`GrOwner`) VALUES(?,?,?);");
    mysqli_stmt_bind_param($statement, "ssi", $grName, $grDescription, $userID);
    if(mysqli_stmt_execute($statement)) {
      $newGroupId = $con->insert_id;
      $statement = mysqli_prepare($con, "INSERT INTO UserGroups(GroupID, UserID, UserRank) VALUES (?, ?, 1);");
      mysqli_stmt_bind_param($statement, "ii", $newGroupId , $userID);
      if(mysqli_stmt_execute($statement)) {
        if (!file_exists("../files/$newGroupId")) {
            mkdir("../bestanden/$newGroupId", 0755, true);
        }else{
          $_SESSION["errormsg"] = "Er ging iets fout!";
          header("Location: ../home.php");
        }
        header("Location: ../home.php");
      }
    }else{
    header("Location: ../home.php");
    }
  }
}
//
// Gebruiker inviten
//
if(($_SERVER["REQUEST_METHOD"] == "POST") && isset($_POST["nickname"]) && isset($_POST["inviteUser"])) {
  session_start();
  $con = mysqli_connect($host, $user, $pass, $db);
  if(!isset($_SESSION["GroupID"])) {
    $_SESSION["errormsg"] = "Er ging iets fout!";
    exit;
  }else{
      $userID = $_SESSION["UserID"];
      $groupID = $_SESSION["GroupID"];
      $nickname = $con->reaL_escape_string($_POST["nickname"]);
      // Kijken of gebruiker bestaat en id ophalen
      $statement = mysqli_prepare($con, "SELECT UserID FROM users WHERE Nickname LIKE ?;");
      mysqli_stmt_bind_param($statement, "s", $nickname);
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
            echo "test";
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

              exit;
            }
          }
      }else{
        $_SESSION["errormsg"] = "Deze gebruiker bestaat niet!";
              echo "test";
        exit;
      }
  }
}
//
// Gebruiker kicken
//
if(($_SERVER["REQUEST_METHOD"] == "POST") && isset($_POST["nickname"]) && isset($_POST["deleteUser"])) {
  session_start();
  $con = mysqli_connect($host, $user, $pass, $db);
  if(!isset($_SESSION["GroupID"])) {
    $_SESSION["errormsg"] = "Er ging iets fout!";
    header("Location: ../home.php");
  }else{
      $userID = $_SESSION["UserID"];
      $groupID = $_SESSION["GroupID"];
      $nickName = $con->reaL_escape_string($_POST["nickname"]);
      //Kijken of gebruiker bestaat en id ophalen
      $statement = mysqli_prepare($con, "SELECT UserID FROM users WHERE Nickname LIKE ?;");
      mysqli_stmt_bind_param($statement, "s", $nickName);
      mysqli_stmt_execute($statement);
      $result = $statement->get_result();
      if(mysqli_num_rows($result) == 1) {
          while($row = mysqli_fetch_assoc($result)) {
              $deletedUserID = $row["UserID"];
          }
      }else{
        $_SESSION["errormsg"] = "Er ging iets fout bij het ophalen van de gebruiker!";
        header("Location: redirect.php?home=1");
        exit;
      }
          $result->close();
          if($deletedUserID == $userID) {
            $_SESSION["errormsg"] = "Je kan jezelf niet verwijderen uit de groep";
            header("Location: redirect.php?home=1");
            exit;
          }
          //Kijken of gebruiker juiste rank heeft
          $statement = mysqli_prepare($con, "SELECT UserRank FROM UserGroups WHERE UserID = ? AND GroupID = ?;");
          mysqli_stmt_bind_param($statement, "ii", $userID, $groupID);
          mysqli_stmt_execute($statement);
          $result = $statement->get_result();
          if(mysqli_num_rows($result) == 1) {
            while($row = mysqli_fetch_assoc($result)) {
                $userRank = $row["UserRank"];
            }
            $result->close();
            if($userRank > 2) {
              $_SESSION["errormsg"] = "Je hebt niet de juiste rank om gebruiker te verwijderen!";
              header("Location: redirect.php?home=1");
              exit;
            }else{
            //Gebruiker verwijderen
              $statement = mysqli_prepare($con, "DELETE FROM UserGroups WHERE UserID = ? AND GroupID = ?;");
              mysqli_stmt_bind_param($statement, "ii", $deletedUserID, $groupID);
              if(!mysqli_stmt_execute($statement)) {
                $_SESSION["errormsg"] = "Er ging iets fout!";
                exit;
              }else{
                $_SESSION["errormsg"] = "Gebruiker verwijderd!";
                exit;
              }
            }
          }else{
            $_SESSION["errormsg"] = "Er ging iets fout bij het ophalen van je rank!";
            header("Location: redirect.php?home=1");
            exit;
          }
      }
}
//
// Group verwijderen
//
if(($_SERVER["REQUEST_METHOD"] == "POST") && isset($_POST["deleteGroup"])) {
  session_start();
  $con = mysqli_connect($host, $user, $pass, $db);
  if(!isset($_SESSION["UserID"]) || !isset($_SESSION["GroupID"])) {
    $_SESSION["errormsg"] = "Er ging iets fout!";
    header("Location: ../home.php");
  }else{
      $userID = $_SESSION["UserID"];
      $groupID = $_SESSION["GroupID"];
      //Kijken of gebruiker in group zit en juiste rank heeft
      $statement = mysqli_prepare($con, "SELECT * FROM UserGroups WHERE GroupID = ? AND UserID = ? AND UserRank = 1;");
      mysqli_stmt_bind_param($statement, "ii", $groupID, $userID);
      mysqli_stmt_execute($statement);
      $result = $statement->get_result();
      if(mysqli_num_rows($result) == 1) {
        //Group verwijderen en map
        $result->close();
        $statement = mysqli_prepare($con, "DELETE FROM groups WHERE groupID = ?");
        mysqli_stmt_bind_param($statement, "i", $groupID);
        if(!mysqli_stmt_execute($statement)) {
          echo "202";
          exit;
        }else{
          $dir = "../files/$groupID";
          rrmdir($dir);
          echo "200";
          exit;
        }
      }else{
        echo "201";
        exit;
      }
      $result->close();
      }
}

//Functie mappen Verwijderen
function rrmdir($dir) {
if (is_dir($dir)) {
  $objects = scandir($dir);
  foreach ($objects as $object) {
    if ($object != "." && $object != "..") {
      if (filetype($dir."/".$object) == "dir")
         rrmdir($dir."/".$object);
      else unlink   ($dir."/".$object);
    }
  }
  reset($objects);
  rmdir($dir);
}
}

//
// Groep verlaten
//
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
    //Controleren of gebruiker eigenaar is
    $statement = mysqli_prepare($con, "SELECT * FROM UserGroups WHERE UserID = ? AND GroupID = ? AND UserRank = 1");
    mysqli_stmt_bind_param($statement, "ii", $userID, $groupID);
    if(!mysqli_stmt_execute($statement)) {
      $_SESSION["errormsg"] = "Er ging iets fout!";
      header("Location: redirect.php?home=1");
      exit;
    }
    $result = $statement->get_result();
    if(mysqli_num_rows($result) == 1) {
      $_SESSION["errormsg"] = "Je kan de groep niet verlaten als eigenaar!";
      header("Location: redirect.php?home=1");
      $result->close();
      exit;
    }
    $result->close();
    $statement = mysqli_prepare($con, "DELETE FROM UserGroups WHERE UserID = ? AND GroupID = ?;");
    mysqli_stmt_bind_param($statement, "ii", $userID, $groupID);
    if(mysqli_stmt_execute($statement)) {
      echo "success!";
      $result->close();
    }else{
      $_SESSION["errormsg"] = "Er ging iets fout!";
      header("Location: redirect.php?home=1");
      $result->close();
      exit;
    }
}
//
// Leden opvragen van groep
//
if(($_SERVER["REQUEST_METHOD"] == "POST") && isset($_POST["getGroupMembers"])) {
  session_start();
  $con = mysqli_connect($host, $user, $pass, $db);
  if((!isset($_SESSION["GroupID"])) || (!isset($_SESSION["UserID"]))) {
    $_SESSION["errormsg"] = "Er ging iets fout!";
    header("Location: redirect.php?home=1");
    exit;
  }
  $userID = $_SESSION["UserID"];
  $groupID = $_SESSION["GroupID"];
  $members = array();
  $statement = mysqli_prepare($con, "SELECT u.UserID, u.FirstName, u.LastName, u.Nickname, ur.rankName FROM UserGroups ug INNER JOIN users u ON ug.UserID = u.UserID INNER JOIN userRanks ur ON ur.rankID = ug.UserRank WHERE GroupID = ?;");
  mysqli_stmt_bind_param($statement, "i", $groupID);
  if(!mysqli_stmt_execute($statement)) {
    $_SESSION["errormsg"] = "Er ging iets fout!";
    header("Location: redirect.php?home=1");
    exit;
  }
  $result = $statement->get_result();
  if(mysqli_num_rows($result) > 0) {
      while($row = mysqli_fetch_assoc($result)) {
          $member = new Member($row["UserID"], $row["FirstName"], $row["LastName"], $row["Nickname"], $row["rankName"]);
          array_push($members, $member);
      }
      foreach ($members as $member) {
        echo ("<div class=\"item__member--items\">
                <div class=\"item__member--name\"><p>$member->lastName $member->firstName</p></div>
                <div class=\"item__member--nickname\"><p>$member->nickName</p></div>
                <div class=\"item__member--rank\"><p>$member->userRank</p></div>
              </div>");
      }
  }else{
    $_SESSION["errormsg"] = "Er ging iets fout!";
    header("Location: redirect.php?home=1");
    exit;
  }
}
//
// Vak aanmaken
//
if(($_SERVER["REQUEST_METHOD"] == "POST") && isset($_POST["crName"])  && isset($_POST["crDescription"])) {
  session_start();
  $con = mysqli_connect($host, $user, $pass, $db);
  if((!isset($_SESSION["GroupID"])) || (!isset($_SESSION["UserID"]))) {
    $_SESSION["errormsg"] = "Er ging iets fout!";
    header("Location: redirect.php?home=1");
    exit;
  }
  $crName = $con->reaL_escape_string($_POST["crName"]);
  $crDescription = $con->real_escape_string($_POST["crDescription"]);
  $userID = $_SESSION["UserID"];
  $groupID = $_SESSION["GroupID"];
  $statement = mysqli_prepare($con, "INSERT INTO courses(`GroupID`,`CrName`,`CrDescription`) VALUES (?,?,?);");
  mysqli_stmt_bind_param($statement, "iss", $groupID, $crName, $crDescription);
  if(!mysqli_stmt_execute($statement)) {
    $_SESSION["errormsg"] = "Er ging iets fout!";
    header("Location: redirect.php?home=1");
    exit;
  }else{
    $newCrID = $con->insert_id;
    if (!file_exists("../files/$groupID")) {
        mkdir("../files/$groupID", 0755, true);
        mkdir("../files/$groupID/$newCrID", 0755, true);
        echo $groupID;
        exit;
    }else{
        mkdir("../files/$groupID/$newCrID", 0755, true);
        echo $groupID;
        exit;
    }
    $_SESSION["errormsg"] = "Er ging iets fout!";
    header("Location: redirect.php?home=1");
    exit;
  }
}

//
// Files vak inladen
//

if(($_SERVER["REQUEST_METHOD"] == "POST") && isset($_POST["course"])  && isset($_POST["courseID"])) {

  session_start();
  $con = mysqli_connect($host, $user, $pass, $db);

  if((!isset($_SESSION["GroupID"])) || (!isset($_SESSION["UserID"]))) {
    $_SESSION["errormsg"] = "Er ging iets fout!";
    header("Location: redirect.php?home=1");
    exit;
  }
  $crName = $con->reaL_escape_string($_POST["courseID"]);
  $userID = $_SESSION["UserID"];
  $groupID = $_SESSION["GroupID"];
  $courseID = $_POST["courseID"];
  $_SESSION["CourseID"] = $courseID;


  //Kijken of gebruiker toegang heeft tot group
  $statement = mysqli_prepare($con, "SELECT us.UserID, us.GroupID, c.CourseID, c.CrName FROM UserGroups us INNER JOIN groups g ON us.GroupID = g.GroupID INNER JOIN courses c ON c.GroupID = g.GroupID WHERE us.UserID = ? AND us.GroupID = ? AND c.CourseID = ?;");
  mysqli_stmt_bind_param($statement, "iii", $userID, $groupID, $courseID);
  if(!mysqli_stmt_execute($statement)) {
    $_SESSION["errormsg"] = "Er ging iets fout!";
    header("Location: redirect.php?home=1");
    exit;
  }
  $result = $statement->get_result();
    if(mysqli_num_rows($result) != 1) {
      $_SESSION["errormsg"] = "Je hebt geen toegang tot deze cursus!";
      header("Location: redirect.php?home=1");
      exit;
  }else{
    while($row = mysqli_fetch_assoc($result)) {
        $courseName = $row["CrName"];
    }
  }

  //Bestanden in directory ophalen
  $path = "../files/$groupID/$courseID";
  $files = array_diff(scandir($path), array('..', '.'));

  echo ("
  <div id=\"dom__fileManager\">
  <div class=\"body__home--title\">
    <h2>$courseName</h2>
  </div>
  <div class=\"\">");

  if(count($files) != 0 && $files != false) {
    foreach ($files as $file) {
      $pathFile = $path."/".$file;
      echo ("
      <div class=\"group__file\">
        <a href=\"$pathFile\" target=\"_blank\">
          $file
        </a>
        <button class=\"dom__fileManager--deleteButton\">Verwijderen</button>
      </div>");
    }
  }else{
    echo("<p> Kon geen bestanden vinden in dit vak!</p>");
  }
  echo("<form id=\"DOM__courses--fileUploader\" class=\"group__file\" action=\"actionsHome.php\" method=\"POST\"\">
    <input id=\"fileInputCourses\" type=\"file\" name=\"file\">
    <input id=\"fileSubmitCourses\" type=\"button\" value=\"uploaden\" name=\"upload\" onclick=\"uploadFileCourse();\">
  </form></div></div>");

}

//Bestand verwijderen
if (($_SERVER["REQUEST_METHOD"] == "POST") && isset($_POST["deleteFile"])  && isset($_POST["file"])) {

  session_start();
  $con = mysqli_connect($host, $user, $pass, $db);

  if((!isset($_SESSION["GroupID"])) || (!isset($_SESSION["UserID"])) || (!isset($_SESSION["CourseID"])) ) {
    echo "501";
    exit;
  }
  $file = $con->reaL_escape_string($_POST["file"]);
  $userID = $_SESSION["UserID"];
  $groupID = $_SESSION["GroupID"];
  $courseID = $_SESSION["CourseID"];


  //Kijken of gebruiker toegang heeft tot group
  $statement = mysqli_prepare($con, "SELECT us.UserID, us.GroupID, c.CourseID, c.CrName FROM UserGroups us INNER JOIN groups g ON us.GroupID = g.GroupID INNER JOIN courses c ON c.GroupID = g.GroupID WHERE us.UserID = ? AND us.GroupID = ? AND c.CourseID = ? AND us.userRank = 1;");
  mysqli_stmt_bind_param($statement, "iii", $userID, $groupID, $courseID);
  if(!mysqli_stmt_execute($statement)) {
    echo "501";
    exit;
  }
  $result = $statement->get_result();
    if(mysqli_num_rows($result) != 1) {
      echo "403";
      exit;
  }

  //Bestanden verwijderen als het bestaat
  $filePath = "../files/$groupID/$courseID/$file";
  if(file_exists($filePath)) {
    if(unlink($filePath)) {
      echo $courseID;
    }else{
      echo "701";
    }

  }


}



//Live Chat
if(($_SERVER["REQUEST_METHOD"] == "POST") && isset($_POST["livechat__text"])) {
  session_start();
  $con = mysqli_connect($host, $user, $pass, $db);
  $userID = $_SESSION["UserID"];
echo $_POST["livechat__text"];

//Uitloggen indien niet geconnect
  if(!$con) {
    header("Location: ../home.php");
  }else{
          echo $_POST["livechat__text"];

          $livechatmessage = $con->reaL_escape_string($_POST["livechat_text"]);
          $userID = $_SESSION["UserID"];
          $groupID = $_SESSION["GroupID"];

          $statement = mysqli_prepare($con, "INSERT INTO chatMessages(`GroupID`,`userID`,`chatMessage`) VALUES (?,?,?);");
          mysqli_stmt_bind_param($statement, "iis", $groupID, $userID, $livechatmessage);

          if(!mysqli_stmt_execute($statement)) {
            $_SESSION["errormsg"] = "Er ging iets fout bij het verzenden van de chat!";
            exit;
          }else{


               }

       }
}
?>
