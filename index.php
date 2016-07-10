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
  			<input type="text" name="id">
  			<input type="submit" value="SEND">
  		</form>
  		<hr>
  </body>


<script type="text/javascript">

	function logTest(result) {
		alert(result);
	}

	setAjax(document.getElementById("registerForm"),logTest);
	setAjax(document.getElementById("loginForm"),logTest);
	setAjax(document.getElementById("readForm"),logTest);


</script>
</html>
