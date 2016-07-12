<?php
  require_once __DIR__ .  "/db.php";
  $data = new db();

  //FUNZIONE DI UTILITA' PER SALVARE UN FILE NEL FILESYSTEM
  function saveFile($postFile,$path,$type) {
    $fileName = sha1_file($postFile['tmp_name']);
    move_uploaded_file($postFile['tmp_name'],"." . $path . $fileName . $type);
    return $path . $fileName . $type;
  }

  //FUNZIONE DI UTILITA' PER IL CONTROLLO DEL FORMATO
  function checkFormat($postFile,$type) {



  }

  //create
  function createPic($description,$postFile,$user,$mime,$feed,$ajax = 0) {
    global $data;

    $data->utilityFilter($description);
    $data->utilityFilter($path);
    $data->utilityFilter($user);
    $data->utilityFilter($mime);
    $data->utilityFilter($feed);

    if($mime == 1)
    	$path = saveFile($postFile, "./pics/",".jpeg");
    else
    	$path = saveFile($postFile, "./pics",".mp4");  
    $query = "INSERT INTO pics(description,path,userId,mime,feed) VALUES('$description','$path',$user,$mime,$feed)";
    $result = $data->query($query,0);

    if($ajax) {
      echo $data->insertedId;
    } else {
      return $data->insertedId;
    }
  }
