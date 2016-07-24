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
			<h3>Chats</h3>
			<ul class="chat-list" id="chat-list">
			</ul>
		</div>

		<div class="flex flex-1" id="chat">
			<h3 class="chat-name">Seleziona una chat</h3>
			<ul class="message-list" id="message-list">
				
			</ul>
			<form id="chat-form" class="fixed-form" action="" method="POST">
				<input type="text" name="message">
				<input class="submitButton" type="submit" value="Invia">
			</form>
		</div>


	</main>
</body>

<script type="text/javascript">
  	makeActiveLink("notifies-link");
  	var userId = <?= $session["data"]["id"] ?>;
  	function displayChats(chats,container,chatContainer) {
  		empty(container);
  		var chatLis = [];
  		var chatAs = [];
  		for(var i = 0; i < chats.length; ++i) {
  			chatLis[i] = document.createElement("li");
  			chatAs[i] = document.createElement("a");
  			chatAs[i].textContent = chats[i].username;
  			chatAs[i].href = "#";
  			chatLis[i].appendChild(chatAs[i]);
  			container.appendChild(chatLis[i]);

  			showChatAction(chatAs[i],chats[i],chatContainer);
  		}
  	}

  	function showChatAction(anchor,chat,chatContainer) {
  		anchor.onclick = function(){
  			empty(chatContainer);
  			document.querySelector(".chat-name").textContent = chat.username;
  			get("./php/userRouter.php?route=getMessages&buddy="+chat.userId,function(result) {
  				var resultObj = JSON.parse(result);
  				var messages = resultObj.data;
  				var messagesLis = [];
  				var messagesPics = [];
  				for(var i = 0; i < messages.length; ++i) {
  					messagesLis[i] = document.createElement("li");
  					messagesLis[i].textContent = messages[i].messageBody;
  					if(messages[i].srcId == userId)
  						messagesLis[i].className = "to";
  					else
  						messagesLis[i].className = "from";
  					chatContainer.appendChild(messagesLis[i]);
  					if(messages[i].path) {
  						messagesPics[i] = document.createElement("img");
  						messagesPics[i].src = messages[i].path;
  						messagesPics[i].className = "flexible-img";
  						messagesLis[i].appendChild(messagesPics[i]);
  					}

  				}
  			});
  		}
  	}

  	get("./php/userRouter.php?route=getChats",function(result) {
  		var resultObj = JSON.parse(result);
  		var chats = resultObj.data;
  		displayChats(chats,document.getElementById("chat-list"),document.getElementById("message-list"));
  	})
</script>
</html>