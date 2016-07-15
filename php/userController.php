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

  /**** DEBUG READ ALL ****/

  function readAll($ajax = 0) {
    global $data;
    $query = "SELECT * FROM users";
    $data->query($query);

    if($ajax)
      echo $data->ExtendedJSONResult();
    else
      return $data->arrayResult();
  }


  /************************/

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

        if($data->rows)
      	 createUserSession($fetchedUser);
      	echo $fetchedUser;
    }
    else
      return $data->rows;
  }

  function followUser($follower,$followed,$ajax = 0) {
    global $data;
    $data->utilityFilter($follower);
    $data->utilityFilter($followed);
    $query = "CALL followUser($follower,$followed)";
    $data->query($query);

    if($ajax)
      echo $data->JSONResult();
    else
      return $result;
  }

  function getFollow($userId,$followType,$ajax = 0) {
  	global $data;
  	$data->utilityFilter($userId);
  	$query = "";

  	if($followType == 1) //userId as follower
  		$query = "SELECT * FROM followship INNER JOIN users ON id = followed WHERE follower = $userId";
  	else
  		$query = "SELECT * FROM followship INNER JOIN users ON id = follower WHERE followed = $userId";

  	$data->query($query);

  	if($ajax)
  		echo $data->ExtendedJSONResult();
  	else
  		return $data->arrayResult();
  }


  function getNotifies($userId,$ajax = 0) {
  	global $data;
  	$data->utilityFilter($userId);

  	$query = "SELECT * FROM notifies WHERE userId=$userId AND unread=1 ORDER BY eventAt DESC";
  	$data->query($query);

  	if($ajax)
  		echo $data->ExtendedJSONResult();
  	else
  		return $data->arrayResult();
  }

  function emptyNotifies($userId,$ajax = 0) {
  	global $data;
  	$data->utilityFilter($userId);

  	$query = "UPDATE notifies SET unread = 0 WHERE userId = $userId";
  	$data->query($query);
  	if($ajax)
  		echo $data->affected;
  	else
  		return $data->affected;
  }

  function sendMessage($src,$dst,$message,$picId,$ajax = 0) {
  	global $data;
  	$data->utilityFilter($src);
  	$data->utilityFilter($dst);
  	$data->utilityFilter($message);
  	$data->utilityFilter($picId);

    $query = "";
    if(!$picId)
  	 $query = "INSERT INTO messages(srcId,dstId,messageBody,picId) VALUES ($src,$dst,'$message',NULL)";
    else
      $query = "INSERT INTO messages(srcId,dstId,messageBody,picId) VALUES ($src,$dst,'$message',$picId)";
  	$data->query($query);

  	if($ajax)
  		echo $data->insertedId;
  	else
  		return $data->insertedId;
  }

  function getMessages($userId,$ajax = 0) {
  	global $data;
  	$data->utilityFilter($userId);
  	$query = "SELECT M.*,P.path,US.username AS us,UD.username AS ud FROM messages M LEFT OUTER JOIN pics P ON P.id = M.picId INNER JOIN users US ON (M.srcId = US.id) INNER JOIN users UD ON M.dstId = UD.id WHERE M.dstId = $userId OR M.srcId = $userId  ORDER BY M.messageTime ASC ";
  	$data->query($query);

  	if($ajax)
  		echo $data->ExtendedJSONResult();
  	else
  		return $data->arrayResult();
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


  function deleteUserSession() {
    session_start();
    unset($_SESSION['logged']);
    unset($_SESSION['id']);
    unset($_SESSION['username']);
    unset($_SESSION['password']);
    session_destroy();
  }

  function getSession() {
    session_start();
    $response['data'] = $_SESSION;
    $response['length'] = count($_SESSION);
   	echo json_encode($response);
  }
