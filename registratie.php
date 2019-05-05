<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <?php include_once("php/header.php") ?>
    <link rel="stylesheet" type="text/css" href="css/registratiepg.css">
  </head>
  <body>
    <div class="registratie__pagina">
      <div class="body__registratie">
        <div class="registratie__title">
          <h1>Registratie</h1>
          <p>Vul deze lijst in om een account aan te maken.</p>
        </div>
          <div class="Registratie__box">
            <form action="registratie.php" method="post" id="dom__form--registratie">
               <div class="Registraite__allinput">
                  <label for="nickname"><b>Nickname</b></label>
                  <input type="text" placeholder="Nickname" class="registratie__input" name="nickname" id="dom__inputReg--nickname" required>

                  <label for="email"><b>Email</b></label>
                  <input type="email" placeholder="123@email.be" class="registratie__input" name="email"  id="dom__inputReg--email" required>

                  <label for="voornaam"><b>Voornaam</b></label>
                  <input type="text" placeholder="Voornaam" class="registratie__input" name="voornaam"  id="dom__inputReg--voornaam" required>

                  <label for="achternaam"><b>Achternaam</b></label>
                  <input type="text" placeholder="Achternaam" class="registratie__input" name="achternaam" id="dom__inputReg--achternaam" required>

                  <label for="psw"><b>Paswoord</b></label>
                  <input type="password" placeholder="Wachtwoord" class="registratie__input" name="psw"  id="dom__inputReg--password1" required>

                  <meter min="0" max="4" id="DOM_password_meter"></meter>
                  <p id="DOM_password_strength_text"> </p>
                  <p id="DOM_password_suggestions"></p>

                  <label for="psw_repeat"><b>Herhaal paswoord</b></label>
                  <input type="password" placeholder="Wachtwoord herhalen" class="registratie__input" name="psw_repeat"  id="dom__inputReg--password2" required>

                  <p>Door een account te maken gaat u akkoord met de Terms Of Service.</p> <?php // TODO: TOS pagina toevoegen/linken ?>

                  <div>
                    <input class="registratie__btn" type="submit" value="Registreren" name="registratie__btn">
                  </div>
                  <div>
                    <p>Heeft u al een account? <a href="index.php">Log in</a></p>
                  </div>
             </div>
            </form>
          </div>
        </div>
      </div>
    <?php include_once("php/footer.php") ?>
    <script src="js/regFuncties.js"></script>
  </body>
</html>
