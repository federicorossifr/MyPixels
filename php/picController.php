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
  function checkFormat($postFile,$type,$field) {
    $imgAccepted = array("jpg","jpeg","png");
    $vidAccepted = array("mp4");

    $postedFileName = $_FILES[$field]['name'];
    $postedFileExtension = pathinfo($postedFileName,PATHINFO_EXTENSION);
    $isImg = in_array($postedFileExtension, $imgAccepted);
    $isVid = in_array($postedFileExtension, $vidAccepted);

    if($type == 1 && $isImg) {
      return true;
    }
    if($type == 0 && $isVid) {
      return true;
    }

    return false;
  }

  //create
  function createPic($description,$postFile,$user,$mime,$feed,$field,$ajax = 0) {
    global $data;

    $data->utilityFilter($description);
    $data->utilityFilter($path);
    $data->utilityFilter($user);
    $data->utilityFilter($mime);
    $data->utilityFilter($feed);


    if(!checkFormat($postFile,$mime,$field)) {
     die("Format error");
    }

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


  //read
  function readPic($picId,$ajax = 0) {
    global $data;

    $data->utilityFilter($picId);

    $query = "SELECT * FROM extendedFeedPics WHERE id = $picId";

    $data->query($query);

    if($ajax)
      echo $data->ExtendedJSONResult();
    else {
      return $data->arrayResult();
    }
  }

  function getFeed($userId,$ajax = 0) {
    global $data;
    $data->utilityFilter($userId);

    $query = "SELECT *,(SELECT L.upvote FROM likes  L WHERE L.userid = $userId AND  L.picId = EP.id) AS userLiked FROM extendedFeedPics EP WHERE EP.userId IN (SELECT followed FROM followship WHERE follower = $userId) OR EP.userId = $userId";
    $data->query($query);

    if($ajax)
      echo $data->ExtendedJSONResult();
    else
      return $data->arrayResult();
  }

  function getUserFeed($userId,$ajax = 0) {
    global $data;
    $data->utilityFilter($userId);

    $query = "SELECT *,(SELECT L.upvote FROM likes  L WHERE L.userid = $userId AND  L.picId = EP.id) AS userLiked FROM extendedFeedPics EP WHERE EP.userId = $userId";
    $data->query($query);

    if($ajax)
      echo $data->ExtendedJSONResult();
    else
      return $data->arrayResult();  
  }

  function likePic($userId,$picId,$vote,$ajax = 0) {
    global $data;
    $data->utilityFilter($userId);
    $data->utilityFilter($picId);
    $data->utilityFilter($vote);

    $query = "CALL likePic($userId,$picId,$vote)";
    $data->query($query);

    if($ajax)
      echo $data->JSONResult();
    else
      return $data->arrayResult();
  }


  function commentPic($userId,$picId,$comment,$ajax = 0) {
    global $data;
    $data->utilityFilter($userId);
    $data->utilityFilter($picId);
    $data->utilityFilter($comment);

    $query = "INSERT INTO comments(userId,picId,commentBody) VALUES($userId,$picId,'$comment')";
    $data->query($query);

    if($ajax)
      echo $data->insertedId;
    else
      return $data->insertedId;
  }

  function tagPic($picId,$tags,$ajax = 0) {
    global $data;
    $result = 0;
    $data->utilityFilter($userId);
    $baseQuery = "CALL tagPic($picId,'";
    for($i = 0; $i < count($tags); ++$i) {
      $data->utilityFilter($tags[$i]);
      $result += $data->query($baseQuery . $tags[$i] . "')");
    }
    if($ajax) {
      echo $result;
    } else {
      return $result;
    }
  }

  function getPicComments($picId,$ajax = 0) {
    global $data;
    $data->utilityFilter($picId);

    $query = "SELECT C.*,U.username FROM comments C INNER JOIN users U ON C.userId = U.id WHERE C.picId = $picId";
    $data->query($query);

    if($ajax)
      echo $data->ExtendedJSONResult();
    else
      return $data->arrayResult();
  }

  function getPicTags($picId,$ajax = 0) {
    global $data;
    $data->utilityFilter($picId);

    $query = "SELECT TT.* FROM pics P INNER JOIN tagship T ON P.id = T.picId INNER JOIN tags TT ON T.tagId = TT.id WHERE P.id = $picId";
    $data->query($query);

    if($ajax)
      echo $data->ExtendedJSONResult();
    else
      return $data->arrayResult();    
  }


  /*** DEBUG GET ALL PICS ***/
  function readAll2($ajax = 0) {
    global $data;


    $query = "SELECT id FROM pics ORDER BY created DESC ";

    $data->query($query);

    if($ajax)
      echo $data->ExtendedJSONResult();
    else 
      return $data->arrayResult();    
  }