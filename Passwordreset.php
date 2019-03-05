<?php
require 'php/db.php';
$con = mysqli_connect($host, $user, $pass, $db);
if(!$con) {
  throw new Exception ('Could not connect: ' . mysqli_error());
  }

//aanmaken vd token
$selector = bin2hex(random_bytes(8));
$token = random_bytes(32);

$url = sprintf('%reset.php?%', ABS_URL, http_build_query([
     'selector' => $selector,
     'validator' => bin2hex($token)
]));


//token expiratie
$expires = new DateTime('NOW');
$expires->add(new DateInterval('PT01H')); //max 1 uur

//Deleten van al bestaande tokens
$statement_tokendel = mysqli_prepare ($con,("DELETE Token FROM users where Email LIKE ?"));
mysqli_stmt_bind_param($statement_tokendel,"s",$_POST['email']);
if(mysqli_stmt_execute($statement_tokendel)==false)
{
 echo "couldn't delete token";
}

//nieuw token ingeven
$statement_tokennew = mysqli_prepare ($con,("UPDATE users SET Token = ? WHERE Email LIKE ?"));
hash('sha256',$token);
mysqli_stmt_bind_param($statement_tokennew,"ss",$token,$_POST['email']);
if(mysqli_stmt_execute($statement_tokennew)==false)
{
 echo "couldn't insert new token";
}
//selector ingeven.

$statement_selectornew = mysqli_prepare ($con,("UPDATE users SET Selector = ? WHERE Email LIKE ?"));
mysqli_stmt_bind_param($statement_selectornew,"ss",$selector,$_POST['email']);
if(mysqli_stmt_execute($statement_selectornew)==false)
{
 echo "couldn't insert new Selector";
}
//expiredate ingeven
$statement_expire = mysqli_prepare ($con,("UPDATE users SET Selector = ? WHERE Email LIKE ?"));
mysqli_stmt_bind_param($statement_expire,"ss",$expires,$_POST['email']);
if(mysqli_stmt_execute($statement_expire)==false)
{
 echo "couldn't insert new expiredate";
}


//zenden vd email
//ontvanger
$to = $_POST['email'];
//onderwerp
$subject = 'Your password reset link';

//Bericht
$message = '<p>We hebben een paswoord reset vraag gekregen.De link om uw paswoord te resetten is beneden. ';
$message .= 'Als u deze vraag niet gestuurd heeft, kan u deze email negeren.';
$message .='<p>Hier is uw paswoord reset link:</br>';
$message .=sprintf('<a href="%s">%s</a>',$url,$url);

//headers
$headers = "From:".TEST."<".TESTTEST.com .">\r\n";
$headers .="Reply-To".TESTEST.com ."\r\n";
$headers .= "Content-type: text/html\r\n";

$sent = mail($to,$subject,$message,$headers);



 ?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <?php include_once("php/header.php") ?>
  </head>
  <body>
    <form action="Passwordreset.php" method="post">
    <input type="text" class="text" name="email" placeholder="Geef uw email adres in." required>
    <input type="submit" class="submit" value="Submit">
</form>
  </body>
</html>
