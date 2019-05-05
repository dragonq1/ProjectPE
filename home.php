<!DOCTYPE html>
<html lang="nl">
  <head>
    <?php include_once("php/header.php") ?>
  </head>
  <body onbeforeunload="logout()">
  <?php
  session_start();
  if (!isset($_SESSION["UserID"])) {
    header("Location: index.php");
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



    <div class="body__home">
      <div class="navbar__side">
            <div class="navbar__group">
              <p class="navbar__text--groups">Start</p>
              <ol>
                <li><a onclick="home();">Home</a></li>
                <li><a onclick="account();">Account</a></li>
                <li><a onclick="forum();">Forum</a> </li>
              </ol>
            </div>
            <div class="div__line--white"></div>
            <div class="navbar__group">
              <p class="navbar__text--groups">Mijn groepen</p>
                <ol id="dom__sidebar--groups">
                </ol>
            </div>
            <div class="div__line--white"></div>
            <p class="navbar__text--groups">Overige</p>
            <a onclick="logout();">Uitloggen</a>
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
            <input type="text" name="grNaam" id="grName" placeholder="Groep naam" required>
          </div>
          <div class="item__group--row input__group">
            <input type="text" name="grDescription" id="grDescription" placeholder="Groep beschrijving" required>
          </div>
        </div>
        <div class="modal__controls">
          <button type="button" id="dom__submit--newGroup">Aanmaken</button>
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
          <h2>Gebruiker uitnodigen</h2>
        </div>
        <div class="modal__content">
          <div class="item__group--row input__group">
            <input id="dom__inviteUser--nickname" type="text" name="nickname" placeholder="Nickname gebruiker" required>
            <input type="text" name="inviteUser" hidden="true" value="1" required>
          </div>
        </div>
        <div class="modal__controls">
          <button type="button" id="dom__submit--inviteUser">Uitnodigen</button>
          <button type="button" id="dom__btn--inviteUserClose">Annuleren</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Gebruiker verwijderen modal -->
  <div class="modal-box body__home--boxes animated slideInDown faster" id="dom__modal--kickUser">
    <div class="item__group--coloum width-100">
      <form action="php/actionsHome.php" method="post">
        <div class="modal__title">
          <h2>Gebruiker verwijderen</h2>
          <p>Om dit te kunnen doen moet je moderator of eigenaar zijn!</p>
        </div>
        <div class="modal__content">
          <div class="item__group--row input__group">
            <input id="dom__kickUser--nickname" type="text" name="nickname" placeholder="Nickname gebruiker" required>
            <input type="text" name="deleteUser" hidden="true" value="1" required>
          </div>
        </div>
        <div class="modal__controls">
          <button type="button" id="dom__submit--kickUser">Verwijderen</button>
          <button type="button" id="dom__btn--kickUserClose">Annuleren</button>
        </div>
      </form>
    </div>
  </div>

  <!-- modal group verlaten -->

  <div class="modal-box body__home--boxes animated slideInDown faster" id="dom__modal--leaveGroup">
    <div class="item__group--coloum width-100">
      <form action="php/actionsHome.php" method="post">
        <div class="modal__title">
          <h2>Weet u zeker dat u de groep wilt verlaten?</h2>
        </div>
        <div class="modal__controls">
          <button type="button" onclick="leaveGroup();">Ja</button>
          <button type="button" id="dom__btn--leaveGroupClose">Annuleren</button>
        </div>
      </form>
    </div>
  </div>

  <!-- modal group verwijderen -->

  <div class="modal-box body__home--boxes animated slideInDown faster" id="dom__modal--deleteGroup">
    <div class="item__group--coloum width-100">
      <form action="php/actionsHome.php" method="post">
        <div class="modal__title">
          <h2>Weet u zeker dat u de groep wilt verwijderen?</h2>
          <p>Alle bestanden zullen permament worden verwijdered!</p>
          <p>Om dit te kunnen doen moet je eigenaar zijn!</p>
        </div>
        <div class="modal__controls">
          <button type="button" id="dom__submit--deleteGroup">Ja</button>
          <button type="button" id="dom__btn--deleteGroupClose">Annuleren</button>
        </div>
      </form>
    </div>
  </div>

<!-- Modal nieuw vak -->

  <div class="modal-box body__home--boxes animated slideInDown faster" id="dom__modal--newCourse">
    <div class="item__group--coloum width-100">
      <form action="php/actionsHome.php" method="post">
        <div class="modal__title">
          <h2>Nieuw vak aanmaken</h2>
        </div>
        <div class="modal__content">
          <div class="item__group--row input__group">
            <input type="text" name="crName" id="crName" placeholder="Vak naam" required>
          </div>
          <div class="item__group--row input__group">
            <input type="text" name="crDescription" id="crDescription" placeholder="Vak beschrijving" required>
          </div>
        </div>
        <div class="modal__controls">
          <button type="button" id="dom__submit--newCourse">Aanmaken</button>
          <button type="button" id="dom__btn--newCourseClose">Annuleren</button>
        </div>
      </form>
    </div>
  </div>

  <!-- modal leden lijst -->

  <div class="modal-box body__home--boxes animated slideInDown faster" id="dom__modal--members">
    <div class="item__group--coloum width-100">
      <form action="php/actionsHome.php" method="post">
        <div class="modal__title">
          <h2>Groeps leden</h2>
        </div>
        <div class="modal__content item__group--coloum item__member--box" id="dom__groupMembers">

        </div>
        <div class="modal__controls" >
          <button type="button" id="dom__btn--membersClose">Sluiten</button>
        </div>
      </form>
    </div>
  </div>

  <!-- modal bestand verwijderen -->

  <div class="modal-box body__home--boxes animated slideInDown faster" id="dom__modal--deleteFile">
    <div class="item__group--coloum width-100">
      <form action="php/actionsHome.php" method="post">
        <div class="modal__title">
          <h2>Weet u zeker dat u dit bestand permament wilt verwijderen?</h2>
          <p>Om dit te kunnen doen moet je eigenaar zijn!</p>
        </div>
        <div class="modal__controls">
          <button type="button" id="dom__submit--deleteFile">Ja</button>
          <button type="button" id="dom__btn--deleteFileClose">Annuleren</button>
        </div>
      </form>
    </div>
  </div>

<!--modal nieuwe post aanmaken -->
<div class="modal-box body__home--boxes animated slideInDown faster" id="DOM__modal--newpost">
  <div class="item__group--coloum width-100">
      <div class="modal__title">
        <h2>Geef een titel in en uw vraag</h2>
     </div>
     <div class="newpost__modal">
       <div class="newpost__wrapper">
        <div class="modal__content">
          <p>Titel</p>
          <input type="text" max="25" id="DOM__modal__newposttitle" class="newpost__title">
          <p>Vraag</p>
          <textarea maxlength="1020" rows="5" cols="100" id="DOM__modal__newpostmessage" class="newpost__message"></textarea>
        </div>
        <div class="modal__controls">
          <input type="button" value="Post" id="DOM__modal__submitpost" class="submit__post">
          <input type="button" name="" value="Annuleren" id="DOM__modal__annuleerpost" class="annuleer__post">
        </div>
      </div>
     </div>
  </div>
</div>

<!--modal nieuwe antwoord aanmaken -->
<div class="modal-box body__home--boxes animated slideInDown faster" id="DOM__modal--newanswer">
  <div class="item__group--coloum width-100">
      <div class="modal__title">
        <h2>Geef uw antwoord.</h2>
     </div>
     <div class="modal__controls newanswer__modal">
       <div class="newanswer__wrapper">
        <label for="DOM__modal__newanswermessage"></label>
        <textarea maxlength="1020" id="DOM__modal__newanswermessage" class="newanswer__message"></textarea>
        <input type="button" value="Post" id="DOM__modal__submitanswer" class="submit__answer">
        <input type="button" name="" value="Annuleren" id="DOM__modal__annuleeranswer" class="annuleer__answer">
     </div>
     </div>
  </div>
</div>

  <script src="js/main.js"></script>
  <script>
    window.onload=function() {
      home();
    };
  </script>
  <?php include_once("php/footer.php") ?>
  </body>
</html>
