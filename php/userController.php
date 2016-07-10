<?php

  require __DIR__ . "/db.php";
  $data = new db();

  //create
  function createUser($username,$password,$firstName,$surname,$ajax = 0) {
    global $data;
    $data->utilityFilter($username);
    $data->utilityFilter($password);
    $data->utilityFilter($firstName);
    $data->utilityFilter($surname);


    $query = "INSERT INTO users(username,passwd,firstName,surname) VALUES('$username','$password','$firstName','$surname')";
    $result = $data->query($query);
    if($ajax)
    	echo $data->insertedId;
    else
    	return $data->insertedId;
  }

  //read
  function getUserById($id,$ajax = 0) {
    global $data;
    $query = "SELECT * FROM users WHERE id = ";
    $data->utilityFilter($id);
    $query.=$id;
    $data->query($query);

    if($ajax)
      echo $data->ExtendedJSONResult();
    else {
      return $data->arrayResult();
    }
  }

  //update
  function updateUser($id,$newUsername,$newPassword, $ajax = 0) {
    global $data;
    $data->utilityFilter($id);
    $data->utilityFilter($newUsername);
    $data->utilityFilter($newPassword);

    $query = "UPDATE users SET username = '$newUsername', password ='$newPassword' WHERE id = '$id'";
    $result = $data->query($query);
    if($ajax)
    	echo $result;
    else
    	return $result;
  }

  //delete

  function deleteUser($id,$ajax = 0) {
    global $data;
    $data->utilityFilter($id);
    $query = "DELETE FROM users WHERE id = $id";

    $result = $data->query($query);

    if($ajax)
      echo $data->affected;
    else
      return $data->affected;
  }

  function authenticate($username,$password,$ajax = 0) {
    global $data;
    $data->utilityFilter($username);
    $data->utilityFilter($password);
    $query = "SELECT * FROM users WHERE username = '$username' AND passwd = '$password'";
    $data->query($query);

    if($ajax) {
      	$fetchedUser = $data->JSONResult();
      	createUserSession($fetchedUser);
      	echo $fetchedUser;
    }
    else
      return $data->rows;
  }

  function userNameExists($username,$ajax = 0) {
    global $data;
    $data->utilityFilter($username);
    $query = "SELECT * FROM users WHERE username = '$username'";
    $data->query($query);

    if($ajax) {
      echo $data->rows;
    } else {
      return $data->rows;
    }
  }

  function createUserSession($userObject) {
  	session_start();
  	$userObject = json_decode($userObject)[0];
  	$_SESSION["logged"] = 1;
  	$_SESSION["id"] = $userObject->id;
  	$_SESSION["username"] = $userObject->username;
  }


  function deleteUserSession($username,$password) {
  }
