<?php
  require __DIR__ . "/picController.php";

  $action = $_GET['route'];



  switch ($action) {
    case 'newPic':
        $name = $_POST['picName'];
        $postFile = $_FILES['picFile'];
        $collection = $_POST['picCollection'];
        ///////////////////
        $user = 3; // TEST RIMUOVI RIMUOVI RIMUOVI
        /////////////////
        createPic($name,$postFile,$user,$collection,1);
      break;

    default:
      # code...
      break;
  }










 ?>
