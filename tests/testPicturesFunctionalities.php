<!DOCTYPE html>
<html>
  <head>
  	<script type="text/javascript" src="./js/ajax.js"></script>
    <script type="text/javascript" src="./js/forms.js"></script>
    <meta charset="utf-8">
    <title>Pxls - My pixels</title>
    <style type="text/css">
      .hashtag {
          color: white;
          background: lightblue;
          display: inline-block;
          padding: 5px;
          margin: 2px;
          cursor: pointer;
      }
    </style>
  </head>
  <body>
      <a href="./">Back</a>
  		<h1>Register Test</h1>
  		<form id="registerForm" method="POST" action="./php/userRouter.php?route=createUser">
  			<input type="text" name="username">
  			<input type="text" name="password">
  			<input type="text" name="firstName">
  			<input type="text" name="surname">
  			<input type="submit" value="SEND">
  		</form>
      <hr>
      <h1>Login Test</h1>
      <form id="loginForm" method="POST" action="./php/userRouter.php?route=authenticate">
        <input type="text" name="username">
        <input type="text" name="password">
        <input type="submit" value="SEND">
      </form>
      <a id="logout" style="display:none">Logout</a>
      <hr>
      <h1>Read Pics</h1>
      <form method="GET" id="getPicForm" action="./php/picRouter.php?route=readPic">
        <select name="picId" id="pics">
          
        </select>
        <input type="submit" value="SHOW">
      </form>
      <div id="picContainer"></div>
      <div style="display:none" id="loggedin">
        <h1>Logged-in section</h1>
        <h3>Create Pic test</h3>
        <form id="createPicForm" method="POST" enctype="multipart/form-data" action="./php/picRouter.php?route=createPic">
          <textarea name="description" id="input">Description...</textarea>
          <input type="file" name="pic" >
          <input type="hidden" name="tags" id="memory">
          <input type="radio" name="mime" value="1">Image
          <input type="radio" name="mime" value="0">Video
          <input type="submit" value="UPLOAD">
        </form>
        <br>
        <p id="display"></p>
        <hr>
        <h1>Feed</h1>
        <div id="feed"></div>
      </div>
  </body>


<script type="text/javascript">

  var loggedInUser = {};
	function logTest(result) {
		alert(result);
	}

  function empty(element) {
    while(element.hasChildNodes())
      element.removeChild(element.firstChild);
  }

  function logInActions(user) {
      document.getElementById("loggedin").style.display = "block";
      document.getElementById("logout").style.display = "block";
      document.getElementById("logout").textContent = "Logged as: " + user[0].username + " Logout";
      document.getElementById("loginForm").style.display = "none";
      loggedInUser = user[0];

      get("./php/picRouter.php?route=getFeed",showPics);
  }

  function loginCompleted(result) {
    var user = JSON.parse(result);
    if(user.length) {
      logInActions(user);
    }
  }

  function logoutCompleted(result) {
    loggedInUser = {};
    document.getElementById("loginForm").style.display = "block"
    document.getElementById("loggedin").style.display = "none";
    document.getElementById("logout").style.display = "none";
  }

  function appendOption(select,optionName,optionValue) {
    var option = document.createElement("option");
    option.textContent = optionName;
    option.value = optionValue;
    select.appendChild(option);
  }

  function appendLi(ul,text,additional) {
    var li = document.createElement("li");
    li.textContent = text;
    if(additional)
      li.appendChild(additional);
    ul.appendChild(li);
  }

  var logoutButton = document.getElementById("logout");

  logoutButton.onclick = function(event) {
    get("./php/userRouter.php?route=logout",logoutCompleted);
  }

  function displayPic(container,pic) {
    var picElement;
    if(pic.mime == 1) {
      picElement = document.createElement("img");
    } else {
      picElement = document.createElement("video");
    }
    picElement.src = pic.path;

    var info = document.createElement("div");
    info.style.display = "block";
    var user = document.createElement("span");
    user.style.fontWeight = "bolder";
    user.textContent = "By: " + pic.username;
    var time = document.createElement("span");
    time.textContent = "At: " + pic.created;
    time.style.marginLeft = "10px";
    var description = document.createElement("p");
    description.textContent = pic.description;

    var likeA = document.createElement("a");
    makeAjaxAnchor(likeA,logTest);
    likeA.textContent = "I like it! ("+ pic.up +")";
    likeA.href = "./php/picRouter.php?route=likePic&picId="+pic.id+"&vote=1";
    var unlikeA = document.createElement("a");
    unlikeA.textContent = "I don't like it ("+ pic.down + ")";
    makeAjaxAnchor(unlikeA,logTest);
    unlikeA.href = "./php/picRouter.php?route=likePic&picId="+pic.id+"&vote=0";

    var commentDiv = document.createElement("div");
    get("./php/picRouter.php?route=getPicComments&picId="+pic.id,function(result) {
      var dataObj = JSON.parse(result);
      var comments = dataObj.data;
      var spans = [];
      for(var i = 0; i < comments.length; ++i) {
        spans[i] = document.createElement("span");
        spans[i].textContent = "By: "+comments[i].username + " --> " + comments[i].commentBody;
        commentDiv.appendChild(spans[i]);
      }
    })


    var tagDiv = document.createElement("div");
    get("./php/picRouter.php?route=getTags&picId="+pic.id,function(result) {
      var dataObj = JSON.parse(result);
      var tags = dataObj.data;
      var spans = [];
      for(var i = 0; i < tags.length; ++i) {
        spans[i] = document.createElement("span");
        spans[i].className = "hashtag";
        spans[i].textContent = tags[i].tagName;
        tagDiv.appendChild(spans[i]);
      }
    });

    var commentForm = document.createElement("form");
    commentForm.method ="POST";
    commentForm.action = "./php/picRouter.php?route=commentPic";
    var commentField = document.createElement("input");
    commentField.type = "text";
    commentField.name = "comment";
    var picField = document.createElement("input");
    picField.type = "hidden";
    picField.name = "picId";
    picField.value = pic.id;
    var submitButton = document.createElement("input");
    submitButton.type ="submit";
    submitButton.value="SEND";
    commentForm.appendChild(picField);
    commentForm.appendChild(commentField);
    commentForm.appendChild(submitButton);
    setAjax(commentForm,logTest);

    empty(container);
    info.appendChild(user);
    info.appendChild(time);
    container.appendChild(info);
    container.appendChild(picElement);
    container.appendChild(description);
    container.appendChild(tagDiv);
    container.appendChild(document.createElement("br"));
    container.appendChild(likeA);
    container.appendChild(document.createElement("br"));    
    container.appendChild(unlikeA);
    container.appendChild(commentDiv);
    container.appendChild(commentForm);
  }

  function showPic(result) {
    var dataObj = JSON.parse(result);
    var pic = dataObj.data[0];
    displayPic(document.getElementById("picContainer"),pic);
  }

  function showPics(result) {
    var dataObj = JSON.parse(result);
    var pics = dataObj.data;
    var containers = [];
    var feed = document.getElementById("feed");
    empty(feed);
    for(var i = 0; i < pics.length; ++i) {
      containers[i] = document.createElement("div");
      displayPic(containers[i],pics[i]);
      feed.appendChild(containers[i]);
    }
  }


	setAjax(document.getElementById("registerForm"),logTest);
	setAjax(document.getElementById("loginForm"),loginCompleted);
  setAjax(document.getElementById("getPicForm"),showPic);
  setAjax(document.getElementById("createPicForm"),logTest);

  get("./php/userRouter.php?route=getSession",function(result) {
    var dataObj = JSON.parse(result);
    if(!dataObj.length) return;
    user = [dataObj.data];
    logInActions(user);
  });

  get("./php/picRouter.php?route=readAll",function(result) {
    var dataObj = JSON.parse(result);
    var pics = dataObj.data;
    for(var i = 0; i < pics.length; ++i) {
      appendOption(document.getElementById("pics"),pics[i].id,pics[i].id);
    }
  });

  catchTags(document.getElementById("input"),document.getElementById("memory"),document.getElementById("display"));


  




</script>
</html>
