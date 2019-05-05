<!DOCTYPE html>
<html lang="nl">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php include_once("php/header.php") ?>
    <title>ProjectNaam</title>

  </head>
  <body class="dots__body">
    <div class="div__dots">
      <canvas class='connecting-dots' id="dots-canvas"></canvas>
    </div>
    <div class="body_loginpage">
      <div class="body__loginbox" id="dom__loginBox">
      </div>
    </div>

  <?php include_once("php/footer.php") ?>
  <script src="js/login.js"></script>
  <script src="js/dots.js"></script>
  <?php
    // Account activeren als token is gebruikt
    if(isset($_GET['token'])) {
      require 'php/db.php';
      if(!$con = mysqli_connect($host, $user, $pass, $db)) {
        $returnCode = 402;
      }

      $token = $con->real_escape_string($_GET['token']);
      $statement = mysqli_prepare($con, "SELECT vk.UserID FROM verifyKeys vk INNER JOIN users u ON u.UserID = vk.UserID WHERE VerifyKeyToken = ? AND verified = 0;");
      mysqli_stmt_bind_param($statement, "s", $token);
      if(!mysqli_stmt_execute($statement)) {
        $returnCode = 401;
      }
      $result = $statement->get_result();
      if(mysqli_num_rows($result) == 1) {
          while($row = mysqli_fetch_assoc($result)) {
              $userID = ($row["UserID"]);
          }
          $statement = mysqli_prepare($con, "UPDATE users SET verified = 1 WHERE UserID = ?;");
          mysqli_stmt_bind_param($statement, "i", $userID);
          if(!mysqli_stmt_execute($statement)) {
            $returnCode = 401;
          }
          if($statement->affected_rows == 1) {
            $returnCode = 206;
          }else{
            $returnCode = 207;
          }
      }else{
        $returnCode = 205;
      }

    echo ("<script>$.notify($returnCode)</script>");
    }
  ?>


  </body>
</html>
