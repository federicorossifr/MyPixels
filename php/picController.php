<?php
  require_once __DIR__ .  "/db.php";
  $data = new db();

  //FUNZIONE DI UTILITA' PER SALVARE UN FILE NEL FILESYSTEM
  function saveFile($postFile,$path,$type) {
    $fileName = sha1_file($postFile['tmp_name']);
    move_uploaded_file($postFile['tmp_name'],"." . $path . $fileName . $type);
    return $path . $fileName;
  }


  //create
  function createPic($name,$postFile,$user,$collection,$ajax = 0) {
    global $data;
    $collection = $collection || null;

    $data->utilityFilter($name);
    $data->utilityFilter($path);
    $data->utilityFilter($user);
    $data->utilityFilter($collection);

    $path = saveFile($postFile, "./images/",".jpeg");
    $query = "INSERT INTO pics(name,path,user,collection) VALUES('$name','$path',$user,$collection)";
    $result = $data->query($query);

    if($ajax) {
      echo $result;
    } else {
      return $result;
    }
  }
