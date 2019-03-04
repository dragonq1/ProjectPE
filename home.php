<!DOCTYPE html>
<html>
  <head>
    <?php include_once("php/header.php") ?>
  </head>
  <body>
  <?php
  session_start();
  if (!isset($_SESSION["UserID"])) {
    header("Location: index.php");	die;
  }
  $userID = $_SESSION["UserID"];

  require 'php/classes.php';
  require 'php/db.php';
  $con = mysqli_connect($host, $user, $pass, $db);


  $statement = mysqli_prepare($con, "SELECT FirstName, LastName FROM dragv_dev.users where UserID = ?");
  mysqli_stmt_bind_param($statement, "i", $userID);
  mysqli_stmt_execute($statement);
  $result = $statement->get_result();

  if(mysqli_num_rows($result) == 1) {
      while($row = mysqli_fetch_assoc($result)) {
          $firstName = $row["FirstName"];
          $lastName = $row["LastName"];
      }
  }else{
    header("index.php");
  }


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

  ?>

  <?php if(isset($_SESSION["errormsg"])) {
          $error = $_SESSION["errormsg"];
          unset($_SESSION["errormsg"]);
          echo ("<p> $error </p>");
        }
  ?>

    <div class="body__home">
      <div class="navbar__side">
            <div class="navbar__group">
              <p class="navbar__text--groups">Start</p>
              <ol>
                <li><a onclick="home();">Home</a></li>
                <li><a onclick="account();">Account</a></li>
              </ol>
            </div>
            <div class="div__line--white"></div>
            <div class="navbar__group">
              <p class="navbar__text--groups">Mijn groepen</p>
              <ol>
                <?php
                  foreach ($groups as $group) {
                    echo "<li><a onclick=\"courses($group->GrID);\">$group->GrName</a></li>";
                  }
                ?>
              </ol>
            </div>
            <div class="div__line--white"></div>
            <p class="navbar__text--groups">Overige</p>
            <a href="index.php">Uitloggen</a>
      </div>
      <div class="body__home--containers">
        <div class="home__title">
          <?php
            echo "<h1>Welkom $firstName!</h1>";
          ?>
        </div>
        <div class="body__home--interactive" id="dom__interactive">
          <!-- Interactive, leeg laten! -->
        </div>
      </div>
    </div>
  <!-- Nieuwe group modal -->
  <div class="modal-box body__home--boxes animated slideInDown faster" id="dom__modal--newgroup">
    <div class="item__group--coloum width-100">
      <form action="php/actionsHome.php" method="post">
        <div class="modal__title">
          <h2>Nieuwe groep aanmaken</h2>
        </div>
        <div class="modal__content">
          <div class="item__group--row input__group">
            <input type="text" name="grNaam" id="grNaam" placeholder="Groep naam">
          </div>
          <div class="item__group--row input__group">
            <input type="text" name="grDescription" id="GrDescription" placeholder="Groep beschrijving">
          </div>
        </div>
        <div class="modal__controls">
          <input type="submit" value="Aanmaken">
          <button type="button" id="dom__btn--newGroupClose">Annuleren</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Gebruiker toevoegen modal -->
  <div class="modal-box body__home--boxes animated slideInDown faster" id="dom__modal--inviteUser">
    <div class="item__group--coloum width-100">
      <form action="php/actionsHome.php" method="post">
        <div class="modal__title">
          <h2>Gebruiker toevoegen</h2>
        </div>
        <div class="modal__content">
          <div class="item__group--row input__group">
            <input type="email" name="userMail" placeholder="E-mail gebruiker">
          </div>
        </div>
        <div class="modal__controls">
          <input type="submit" value="Aanmaken">
          <button type="button" id="dom__btn--inviteUserClose">Annuleren</button>
        </div>
      </form>
    </div>
  </div>
  <script src="js/main.js"></script>
  <script type="text/javascript">
    window.onload=function() {
      home();
    };
  </script>
  <?php include_once("php/footer.php") ?>
  </body>
</html>
