<?php
  require __DIR__ . "/userController.php";

  $action = $_GET['route'];



  switch ($action) {
    case 'createUser':
        $username = $_POST['username'];
        $password = $_POST['password'];
        createUser($username,$password,1);
      break;

    case 'updateUser':
        $userId = $_POST['userId'];
        $newUsername = $_POST['newUsername'];
        $newPassword = $_POST['newPassword'];
        updateUser($userId,$newUsername,$newPassword,1);
      break;

    case 'readUser':
       $userId = $_GET['id'];
       getUserById($userId,1);
    break;
  }










 ?>
