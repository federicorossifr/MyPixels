<?php
  require __DIR__ . "/picController.php";

  $action = $_GET['route'];



  switch ($action) {
    case 'createPic':
        session_start();
        if(!isset($_SESSION['logged'])) break;
        $userId = $_SESSION['id'];
        $description = $_POST['description'];
        $postFile = $_FILES['pic'];
        $mime = $_POST['mime'];
        createPic($description,$postFile,$userId,$mime,1,'pic',1);
      break;

    case 'readPic':
        $picId = $_GET['picId'];
        readPic($picId,1);
        break;

    case 'readAll':
        readAll2(1);
        break;

    default:
      # code...
      break;
  }










 ?>
