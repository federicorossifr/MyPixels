<?php

  require __DIR__ . "/db.php";
  $data = new db();

  //create
  function createUser($username,$password) {
    global $data;
    $data->utilityFilter($username);
    $data->utilityFilter($password);

    $query = "INSERT INTO users(username,password) VALUES('$username','$password')";
    $result = $data->query($query);
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
      echo $data->JSONResult();
    else {
      return $data->arrayResult();
    }
  }

  //update
  function updateUser($id,$newUsername,$newPassword) {
    global $data;
    $data->utilityFilter($id);
    $data->utilityFilter($newUsername);
    $data->utilityFilter($newPassword);

    $query = "UPDATE users SET username = '$newUsername', password ='$newPassword' WHERE id = '$id'";
    $result = $data->query($query);
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
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $data->query($query);

    if($ajax)
      echo $data->rows;
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
