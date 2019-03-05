<?php




 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <?php include_once("php/header.php") ?>
  </head>
  <body>

    <form class="pswreset-form" action="enter_email.php" method="post">
    		<h2>Reset password</h2>

    		<div class="pswreset-group">
    			<label>Your email address</label>
    			<input type="email" name="email" required>
    		</div>

    		<div class="pswreset-group">
    			<button type="submit" name="reset-password" class="pswreset-btn">Submit</button>
    		</div>
    	</form>

  </body>
</html>
