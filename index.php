<?php
	include "./php/userController.php";
	$session = getSession();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Pxls</title>
	<link rel="stylesheet" type="text/css" href="./css/font.css">
	<link rel="stylesheet" type="text/css" href="./css/style.css">
	<link rel="stylesheet" type="text/css" href="./css/topbar.css">
	<link rel="stylesheet" type="text/css" href="./css/flex.css">
	<link rel="stylesheet" type="text/css" href="./css/form.css">
	<script type="text/javascript" src="./js/ajax.js"></script>
</head>
<body>
	<?php include "./layout/topBar.php"; ?>

	<main class="flex-parent">
		<div class="flex-2">
			<img class="flexible-img" src="./res/splash.jpg">
		</div>

		<div class="flex-2">
			<h2 class="centered">Iscriviti per vedere le foto e i video<br> dei tuoi amici.</h2>
			<form id="registerForm" method="POST" action="./php/userRouter.php?route=createUser">
				<input type="text" name="username" class="light wide" placeholder="Username"><br>
				<input type="password" name="password" class="light wide" placeholder="Password"><br>
				<input type="text" name="firstName" class="light wide" placeholder="First name"><br>
				<input type="text" name="surname" class="light wide" placeholder="Surname"><br>
				<input type="submit" class="submitButton regular wide" value="Iscriviti" >
			</form>
		</div>
	</main>
</body>

<script type="text/javascript">
	function logTest(result) {
		alert(result);
	}

	setAjax(document.getElementById("loginForm"),logTest);
	setAjax(document.getElementById("registerForm"),logTest);
</script>
</html>