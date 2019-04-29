<?php
  require 'classes.php';
  $data = new jsonData(0, "");
  session_start();
  session_unset();
  session_destroy();
  echo json_encode($data);




 ?>
