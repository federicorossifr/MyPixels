<?php
  require __DIR__ . "/userController.php";
  require __DIR__ . "/picController.php";
  $action = $_GET['route'];



  switch ($action) {
    case 'createUser':
        $username = $_POST['username'];
        $password = $_POST['password'];
        $firstName = $_POST['firstName'];
        $surname = $_POST['surname'];
        createUser($username,$password,$firstName,$surname,1);
      break;

    /*case 'updateUser':
        $userId = $_POST['userId'];
        $newUsername = $_POST['newUsername'];
        $newPassword = $_POST['newPassword'];
        updateUser($userId,$newUsername,$newPassword,1);
      break;

    case 'readUser':
        $userId = $_GET['id'];
        getUserById($userId,1);
        break;

    /*** debug read all
    case 'readAll':
        readAll(1);
        break;*/

    case 'authenticate':
        $username = $_POST['username'];
        $password = $_POST['password'];
        authenticate($username,$password,1);
        break;

    case 'logout':
        deleteUserSession();
        break;

    case 'searchByUsername':
        $username = $_GET['username'];
        searchByUsername($username,1);
        break;

    case 'getNotifies':
        session_start();
        if(!isset($_SESSION['logged'])) break;
        $userId = $_SESSION['id'];
        getNotifies($userId,1);
        break;

    case 'emptyNotifies':
        session_start();
        if(!isset($_SESSION['logged'])) break;
        $userId = $_SESSION['id'];
        emptyNotifies($userId,1);
        break;

    case 'setPic':
        session_start();
        if(!isset($_SESSION['logged'])) break;
          $userId = $_SESSION['id'];
        $picId = null;
        if(isset($_FILES['pic']))
          $picId = createPic("",$_FILES['pic'],$userId,0,"pic",0);
        setPic($userId,$picId,1);
        break;

   	case 'sendMessage':
   		session_start();
        if(!isset($_SESSION['logged'])) break;
        $userId = $_SESSION['id'];
   		$dest = $_POST['dest'];
   		$message = $_POST['message'];
   		$picId = null;
   		if(isset($_FILES['attachment']))
   			$picId = createPic("",$_FILES['attachment'],$userId,0,"attachment",0);
   		sendMessage($userId,$dest,$message,$picId,1);
   		break;

    case 'getChats':
      session_start();
      if(!isset($_SESSION['logged'])) break;
        $userId = $_SESSION['id'];
      getChats($userId,1);
      break;

   	case 'getMessages':
   		session_start();
        if(!isset($_SESSION['logged'])) break;
   		$userId = $_SESSION['id'];
      $buddy = $_GET['buddy'];
   		getMessages($userId,$buddy,1);
   		break;

   	case 'getFollowed':
   		session_start();
   		if(!isset($_SESSION['logged'])) break;
   		$userId = $_GET['id'];
   		getFollow($userId,1,1);
   		break;

   	case 'getFollowers':
		session_start();
   		if(!isset($_SESSION['logged'])) break;
   		$userId = $_GET['id'];
   		getFollow($userId,0,1);
   		break;

   	case 'getSession':
   		getSession(1);
   		break;


    case 'follow':
        session_start();
        if(!isset($_SESSION['logged'])) break;
        $follower = $_SESSION['id'];
        $followed = $_GET['followed'];
        followUser($follower,$followed,1);
        break;

    case 'getProfilePic':
        session_start();
        if(!isset($_SESSION['logged'])) break;
        $userId = $_GET['id'];
        getProfilePic($userId,1);
        break;
  }










 ?>
