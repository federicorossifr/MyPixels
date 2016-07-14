<!DOCTYPE html>
<html>
  <head>
  	<script type="text/javascript" src="./js/ajax.js"></script>
    <meta charset="utf-8">
    <title>Pxls - My pixels</title>
  </head>
  <body>
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
          <textarea name="description">Description...</textarea>
          <input type="file" name="pic">
          <input type="radio" name="mime" value="1">Image
          <input type="radio" name="mime" value="0">Video
          <input type="submit" value="UPLOAD">
        </form>
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

  function showPic(result) {
    var dataObj = JSON.parse(result);
    var pic = dataObj.data[0];
    var picElement;
    if(pic.mime == 1) {
      picElement = document.createElement("img");
    } else {
      picElement = document.createElement("video");
    }
    picElement.src = pic.path;

    var description = document.createElement("p");
    description.textContent = pic.description;

    empty(document.getElementById("picContainer"));
    document.getElementById("picContainer").appendChild(picElement);
    document.getElementById("picContainer").appendChild(description);
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
  })

</script>
</html>
