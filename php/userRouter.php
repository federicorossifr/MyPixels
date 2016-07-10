<?php
  require __DIR__ . "/userController.php";

  $action = $_GET['route'];



  switch ($action) {
    case 'createUser':
        $username = $_POST['username'];
        $password = $_POST['password'];
        $firstName = $_POST['firstName'];
        $surname = $_POST['surname'];
        createUser($username,$password,$firstName,$surname,1);
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

    /*** debug read all ***/
    case 'readAll':
        readAll(1);
        break;

    case 'authenticate':
        $username = $_POST['username'];
        $password = $_POST['password'];
        authenticate($username,$password,1);
        break;
  }










 ?>
