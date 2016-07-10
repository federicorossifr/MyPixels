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
  		<hr>

  		<h1>Read Test</h1>
  		<form id="readForm" method="GET" action="./php/userRouter.php?route=readUser">
  			<select name="id" id="users"></select>
  			<input type="submit" value="SEND">
  		</form>
  		<hr>   
  </body>


<script type="text/javascript">

	function logTest(result) {
		alert(result);
	}

  function appendOption(select,optionName) {
    var option = document.createElement("option");
    option.textContent = optionName;
    option.value = optionName;
    select.appendChild(option);
  }

	setAjax(document.getElementById("registerForm"),logTest);
	setAjax(document.getElementById("loginForm"),logTest);
	setAjax(document.getElementById("readForm"),logTest);

  get("./php/userRouter.php?route=readAll",function(result) {
    var users = JSON.parse(result).data;
    for(var i = 0; i < users.length; ++i)
      appendOption(document.getElementById("users"),users[i].id);

  });


</script>
</html>
