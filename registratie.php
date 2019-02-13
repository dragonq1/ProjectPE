<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <?php include_once("php/header.php") ?>
  </head>
  <body>


<div class="registratie_pagina">

     <div class="body__registratie">
        <form action="registratie.php" method="post">

        <h1>Registratie</h1>
        <p>Vul deze lijst in om een account aan te maken.</p>
        <hr>

      <label for="nickname"><b>Nickname</b></label>
      <input type="text" placeholder="nickname" name="nickname" required>

      <label for="email"><b>Email</b></label>
      <input type="email" placeholder="Email" name="email" required>

      <label for="voornaam"><b>Voornaam</b></label>
      <input type="text" placeholder="Quinten" name="Voornaam" required>


      <label for="achternaam"><b>Achternaam</b></label>
      <input type="text" placeholder="Euh" name="achternaam" required>


      <label for="psw"><b>password</b></label>
      <input type="password" placeholder="paswoord" name="psw" required>

      <label for="psw_repeat"><b>Repeat Password</b></label>
      <input type="password" placeholder="herhaal paswoord" name="psw_repeat" required>

      <p>Door een account te maken gaat u akkoord met de Terms Of Service.</p>

     <div class="registratie__btn">
      <input type="submit" value="Registreren" name="registratie__btn"> 
    </div>
    <div class="">

     <p>Heeft u al een account? <a href="index.php">Log in</a></p>
    </div>

    </form>
    </div>

    </div>

  </body>
</html>
