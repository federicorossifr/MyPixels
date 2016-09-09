<?php

  require __DIR__ . "/db.php";
  $data = new db();

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

  function getUserById($id,$userId,$ajax = 0) {
    global $data;
    $data->utilityFilter($id);
    $data->utilityFilter($userId);
    $query = "SELECT U.*,
              (SELECT COUNT(*) FROM  followship WHERE follower=$userId AND followed=$id) AS following,
              (SELECT COUNT(*) FROM followship WHERE followed = $id) AS followers, 
              (SELECT COUNT(*) FROM followship WHERE follower = $id) AS followeds
              FROM users U WHERE id = $id";
    $data->query($query);

    if($ajax)
      echo $data->ExtendedJSONResult();
    else {
      return $data->arrayResult();
    }
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

  function setPic($userId,$picId,$ajax = 0) {
    global $data;
    $data->utilityFilter($userId);
    $data->utilityFilter($picId);
    $query = "UPDATE users SET profilePic = $picId WHERE id = $userId";
    $data->query($query);
    $pathQuery = "SELECT path FROM pics WHERE id = $picId";
    $result = $data->query($pathQuery);

    if($ajax)
      echo $data->JSONResult();
    else
      return $data->arrayResult();

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
  		$query = "SELECT id as userId,username FROM followship INNER JOIN users ON id = followed WHERE follower = $userId";
  	else if($followType == 0)
  		$query = "SELECT id as userId,username FROM followship INNER JOIN users ON id = follower WHERE followed = $userId";

  	$data->query($query);

  	if($ajax)
  		echo $data->ExtendedJSONResult();
  	else
  		return $data->arrayResult();
  }


  function getNotifies($userId,$ajax = 0) {
  	global $data;
  	$data->utilityFilter($userId);

  	$query = "SELECT N.*,U.username FROM notifies N INNER JOIN users U ON U.id = N.userDone WHERE userId=$userId ORDER BY eventAt DESC";
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

  function  getChats($userId,$ajax = 0) {
    global $data;
    $data->utilityFilter($userId);
    $query = "SELECT OM.dstId AS userId,U.username FROM orderedMessages OM INNER JOIN users U ON OM.dstId  = U.id WHERE srcId = $userId GROUP BY dstId
      UNION
      SELECT OM.srcId AS userId,U.username FROM orderedMessages OM INNER JOIN users U ON OM.srcId  = U.id WHERE dstId = $userId GROUP BY srcId";

    $data->query($query);
    if($ajax)
      echo $data->ExtendedJSONResult();
    else
      return $data->arrayResult();
  }

  function getMessages($userId,$buddy,$ajax = 0) {
    global $data;
    $data->utilityFilter($userId);
    $query = "SELECT M.*,P.path,US.username AS us,UD.username AS ud FROM messages M LEFT OUTER JOIN pics P ON P.id = M.picId INNER JOIN users US ON (M.srcId = US.id) INNER JOIN users UD ON M.dstId = UD.id WHERE (M.dstId = $userId AND M.srcId = $buddy) OR (M.srcId = $userId AND M.dstId = $buddy)  ORDER BY M.messageTime ASC ";
    $data->query($query);

    if($ajax)
      echo $data->ExtendedJSONResult();
    else
      return $data->arrayResult();
  }

  function getProfilePic($userId,$ajax = 0) {
      global $data;
      $data->utilityFilter($userId);
      $query = "SELECT path FROM pics P INNER JOIN users U ON U.profilePic = P.id WHERE U.id = $userId";
      $data->query($query);

      if($ajax)
        echo $data->ExtendedJSONResult();
      else
        return $data->arrayResult();
  }

  function searchByUsername($username,$ajax = 0) {
    global $data;
    $data->utilityFilter($username);
    $query = "SELECT id,username FROM users WHERE username LIKE '%$username%'";
    $data->query($query);

    if($ajax) {
      echo $data->ExtendedJSONResult();
    } else {
      return $data->arrayResult();
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
    echo "1";
  }

  function getSession($ajax = 0) {
    session_start();
    $response['data'] = $_SESSION;
    $response['length'] = count($_SESSION);
    if($ajax)
      echo json_encode($response);
    else
      return $response;
  }

  function isLoggedIn($session) {
    return ($session["length"] && $session["data"]["logged"]);
  }