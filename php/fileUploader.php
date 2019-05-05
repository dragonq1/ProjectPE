<?php
if(($_SERVER["REQUEST_METHOD"] == "POST")) {
  require 'classes.php';
  $data = new jsonData(0, "");
  if($_FILES['file']['error'] > 0) {
    echo $_FILES['file']['error'];
    exit;
  }

  session_start();

  if((!isset($_SESSION["GroupID"])) || (!isset($_SESSION["UserID"])) || (!isset($_SESSION["CourseID"]))) {
    header("Location: index.php");
    exit;
  }
  $userID = $_SESSION["UserID"];
  $groupID = $_SESSION["GroupID"];
  $courseID = $_SESSION["CourseID"];


  if(file_exists(("../files/$groupID/$courseID/" . $_FILES['file']['name'])) == false) {
    move_uploaded_file($_FILES['file']['tmp_name'],
      "../files/$groupID/$courseID/" . $_FILES['file']['name']);
  }else{
    $data->returnCode = 803;
    echo json_encode($data);
    exit;
  }


  $data->output = $courseID;
  echo json_encode($data);
  exit;

}
?>
