<?php

if(($_SERVER["REQUEST_METHOD"] == "POST")) {

  if($_FILES['file']['error'] > 0) {
    echo $_FILES['file']['error'];
    exit;
  }

  session_start();

  if((!isset($_SESSION["GroupID"])) || (!isset($_SESSION["UserID"])) || (!isset($_SESSION["CourseID"]))) {
    $_SESSION["errormsg"] = "Er ging iets fout!";
    echo "700";
    exit;
  }
  $userID = $_SESSION["UserID"];
  $groupID = $_SESSION["GroupID"];
  $courseID = $_SESSION["CourseID"];


  if(file_exists(("../files/$groupID/$courseID/" . $_FILES['file']['name'])) == false) {
    move_uploaded_file($_FILES['file']['tmp_name'],
      "../files/$groupID/$courseID/" . $_FILES['file']['name']);
  }else{
    $_SESSION["errormsg"] = "Er ging iets fout!";
    echo "705";
    exit;
  }


  echo "$courseID";
}

?>
