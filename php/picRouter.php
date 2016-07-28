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
        createPic($description,$postFile,$userId,1,'pic',1);
      break;

    case 'readPic':
        $picId = $_GET['picId'];
        readPic($picId,1);
        break;

    case 'getFeed':
        session_start();
        if(!isset($_SESSION['logged'])) break;
        $userId = $_SESSION['id'];
        getFeed($userId,1);
        break;

    case 'getRelatedFeed':
        session_start();
        if(!isset($_SESSION['logged'])) break;
        $userId = $_SESSION['id'];
        getRelatedFeed($userId,1);
        break;

    case 'getUserFeed':
        $userId = $_GET['id'];
        getUserFeed($userId,1);
        break;

    case 'likePic':
        session_start();
        if(!isset($_SESSION['logged'])) break;
        $userId = $_SESSION['id'];
        $picId = $_GET['picId'];
        $vote = $_GET['vote'];
        likePic($userId,$picId,$vote,1);
        break;

    case 'commentPic':
        session_start();
        if(!isset($_SESSION['logged'])) break;
        $userId = $_SESSION['id'];
        $picId = $_POST['picId'];
        $comment = $_POST['comment'];
        commentPic($userId,$picId,$comment,1);
        break;

    case 'getPicComments':
        $picId = $_GET['picId'];
        getPicComments($picId,1);
        break;

    case 'getTags':
        $picId = $_GET['picId'];
        getPicTags($picId,1);
        break;

    case 'readAll':
        readAll2(1);
        break;

    default:
      # code...
      break;
  }










 ?>
