<?php
require 'classes.php';
require 'db.php';
if(($_SERVER["REQUEST_METHOD"] == "POST") && isset($_POST["homeMenu"]) && isset($_POST["userID"])) {

  $userID = $_POST["userID"];
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



  echo ("<div id=\"dom__interactive--groups\" class=\"body__home--70\">
      <div class=\"body__home--title\">
          <h2>Mijn groepen</h2>
      </div>
      <div class=\"body__home--items\">");
        foreach ($groups as $group) {
          echo ("
          <a onclick=\"newGroup()\" class=\"item__group--link\">
            <div>
              <h3>$group->GrName</h3>
              <p>$group->GrDescr</p>
            </div>
          </a>");
        }

echo("
  <a onclick=\"newGroup()\" class=\"item__group--link\">
    <div>
      <h3>+Groep</h3>
      <p>Klik hier om een nieuwe groep aan te maken</p>
    </div>
  </a>
</div>");

}


?>
