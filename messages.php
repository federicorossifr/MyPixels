<?php
	include "./php/userController.php";
	$session = getSession();
	if(!isLoggedIn($session))
		header("Location: ./index.php");
?>
<!DOCTYPE html>
<html>
	<?php include "./layout/head.php"; ?>
<body>
	<?php include "./layout/topBar.php"; ?>
	<main class="flex-parent">
		<div class="flex flex-4" id="chats">
			<h3><a class="chat-link active" id="chat-toggle" href="#">Chats</a> <span class="thin">|</span> <a id="user-toggle" class="chat-link" href="#">Utenti</a></h3>
			<ul class="chat-list" id="chat-list">
			</ul>
      <ul class="chat-list user-list" id="user-list"></ul>
		</div>

		<div class="flex flex-1" id="chat">
			<h3 class="chat-name">Seleziona una chat</h3>
			<ul class="message-list" id="message-list">
				
			</ul>
			<form id="chat-form" class="fixed-form" enctype="multipart/form-data" action="./php/userRouter.php?route=sendMessage" method="POST">
				<input type="text" required name="message">
				<div id="pic-file" class="inputfile">
					Seleziona un file...
        </div>
				<input type="file" name="attachment">
        <input type="hidden" name="dest">
				<input class="submitButton" type="submit" value="Invia">
			</form>
		</div>


	</main>
</body>

<script type="text/javascript">
	var userId = <?= $session["data"]["id"] ?>;
</script>
<script type="text/javascript" src="./js/messages.js"></script>
</html>