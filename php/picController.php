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
  function checkFormat($postFile,$field) {
    $imgAccepted = array("jpg","jpeg","png");

    $postedFileName = $_FILES[$field]['name'];
    $postedFileExtension = pathinfo($postedFileName,PATHINFO_EXTENSION);
    $isImg = in_array($postedFileExtension, $imgAccepted);

    if($isImg) {
      return true;
    }
    return false;
  }

  //create
  function createPic($description,$postFile,$user,$feed,$field,$ajax = 0) {
    global $data;

    $data->utilityFilter($description);
    $data->utilityFilter($path);
    $data->utilityFilter($user);
    $data->utilityFilter($feed);


    if(!checkFormat($postFile,$field)) {
     die("Format error");
    }

    $path = saveFile($postFile, "./pics/",".jpeg");
    $query = "INSERT INTO pics(description,path,userId,feed) VALUES('$description','$path',$user,$feed)";
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

  function getRelatedFeed($userId,$ajax = 0) {
    global $data;
    $data->utilityFilter($userId);

    $query = "SELECT DISTINCT EP.* FROM extendedFeedPics EP WHERE EP.id IN (
                SELECT C.picId FROM comments C WHERE( C.userId IN (
                  SELECT F.followed FROM followship F WHERE F.follower = $userId
                )
              )
              OR EP.id IN (
                SELECT L.picId FROM likes L WHERE L.userId IN (
                  SELECT F.followed FROM followship F WHERE F.follower = $userId
              )
            )) AND EP.id NOT IN (
                SELECT EP2.id FROM extendedFeedPics EP2 
                WHERE EP2.userId IN 
                (SELECT followed FROM followship WHERE follower = $userId) OR EP2.userId = $userId);";
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