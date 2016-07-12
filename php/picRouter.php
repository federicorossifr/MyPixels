<?php
  require __DIR__ . "/picController.php";

  $action = $_GET['route'];



  switch ($action) {
    case 'newPic':
        createPic($name,$postFile,$user,$collection,1);
      break;

    default:
      # code...
      break;
  }










 ?>
