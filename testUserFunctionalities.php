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
      <h1>Read Test</h1>
      <form id="readForm" method="GET" action="./php/userRouter.php?route=readUser">
        <select name="id" id="users"></select>
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
      <div style="display:none" id="loggedin">
        <h1>Logged-in section</h1>
          <h3>Follow Test</h3>
          <form id="followForm" method="POST" action="./php/userRouter.php?route=follow">
            <select name="followed" id="followable">
              
            </select>
            <input type="submit" value="FOLLOW">
          </form>
          <hr>
          <h3>Notifies Test</h3>
          <button onclick="getNotifies()">Get</button>
          <button disabled id="readNotifies" onclick="readNotifies()">Read</button>
          <ul id="notifies" style="display:none">
            
          </ul>
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

  function loginCompleted(result) {
    var user = JSON.parse(result);
    if(user.length) {
      document.getElementById("loggedin").style.display = "block";
      document.getElementById("logout").style.display = "block";
      document.getElementById("loginForm").style.display = "none";
      document.getElementById("readNotifies").disabled = 1;
      loggedInUser = user[0];
      get("./php/userRouter.php?route=readAll",function(result) {
          var users = JSON.parse(result).data;
          for(var i = 0; i < users.length; ++i) {
            appendOption(document.getElementById("users"),users[i].username,users[i].id);
            if(users[i].id != loggedInUser.id)
              appendOption(document.getElementById("followable"),users[i].username,users[i].id);
          }
      });
    }
  }

  function logoutCompleted(result) {
    loggedInUser = {};
    empty(document.getElementById('users'));
    empty(document.getElementById('followable'));
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

  function appendLi(ul,text) {
    var li = document.createElement("li");
    li.textContent = text;
    ul.appendChild(li);
  }

  var logoutButton = document.getElementById("logout");

  logoutButton.onclick = function(event) {
    get("./php/userRouter.php?route=logout",logoutCompleted);
  }

  function notifiesGot(result) {
    var resultObj = JSON.parse(result);
    var count = resultObj.length;
    var data = resultObj.data;

    empty(document.getElementById("notifies"));
    if(count > 0) {
      document.getElementById("readNotifies").disabled = 0;
      document.getElementById("readNotifies").textContent = "READ (" + count + ")";
      for(var i = 0; i < data.length; ++i) {
        appendLi(document.getElementById("notifies"),JSON.stringify(data[i]));
      }
    }
  }

  function readNotifies() {
    document.getElementById("notifies").style.display = "block";
    document.getElementById("readNotifies").textContent = "READ";
    document.getElementById("readNotifies").disabled = 1;

    get("./php/userRouter.php?route=emptyNotifies",logTest);
  }


  function getNotifies() {
    get("./php/userRouter.php?route=getNotifies",notifiesGot);
  }

	setAjax(document.getElementById("registerForm"),logTest);
	setAjax(document.getElementById("loginForm"),loginCompleted);
	setAjax(document.getElementById("readForm"),logTest);
  setAjax(document.getElementById("followForm"),logTest);

 


</script>
</html>
