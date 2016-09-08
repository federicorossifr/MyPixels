<?php
	include "./php/userController.php";
	$session = getSession();
	if(isLoggedIn($session))
		header("Location: ./home.php");
?>
<!DOCTYPE html>
<html>
	<?php include "./layout/head.php"; ?>
<body>
	<?php include "./layout/topBar.php"; ?>
	<main class="flex-parent">
		<div class="flex-2">
			<img class="flexible-img splash" alt="splash" src="./res/splash.jpg">
		</div>

		<div class="flex-2">
			<h2 class="centered">Iscriviti per vedere le foto e i video<br> dei tuoi amici.</h2>
			<form autocomplete="off" id="registerForm" method="POST" action="./php/userRouter.php?route=createUser">
				<input required pattern="^[a-zA-Z\d]+$" type="text" name="username" class="light wide" placeholder="Username"><br>
				<input required type="password" name="password" class="light wide" placeholder="Password"><br>
				<input required pattern="^[a-zA-Z]+$" type="text" name="firstName" class="light wide" placeholder="First name"><br>
				<input required  pattern="^[a-zA-Z]+$" type="text" name="surname" class="light wide" placeholder="Surname"><br>
				<input type="submit" class="submitButton regular wide" value="Iscriviti" ><br><br>
				<a class="submitButton regular wide" href="./welcome.html">Scopri Pixels</a>
			</form>
		</div>

	</main>
	<div id="gpModal" class="modal">
		<div class="modal-body">
		<a class="modal-close" onclick="hideModal(this.parentNode.parentNode)">&times;</a>
		<p id="modalText"></p>
		</div>
	</div>
</body>

<script type="text/javascript">
	function loginCompleted(result) {
		var resultObj = JSON.parse(result);
		if(!resultObj.length) {
			document.getElementById("modalText").textContent = "Username o password scorrette";
			showModal(document.getElementById("gpModal"));
		} else {
			window.location.href = "./home.php";
		}
	}

	function registerCompleted(result) {
		if(!isNaN(result) && parseInt(result) > 0) {
			document.getElementById("modalText").textContent = "Registrazione completata, effettua l'accesso!";
		} else {
			document.getElementById("modalText").textContent = "C'è stato un errore";
		}
		showModal(document.getElementById("gpModal"));
	}

	setAjax(document.getElementById("loginForm"),loginCompleted);
	setAjax(document.getElementById("registerForm"),registerCompleted);
	checkForm(document.getElementById("loginForm"));
	checkForm(document.getElementById("registerForm"));
</script>
</html>