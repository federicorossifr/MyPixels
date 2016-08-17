<?php
	include "./php/userController.php";
	$session = getSession();
	if(!isLoggedIn($session))
		header("Location: ./index.php");
	$user = null;
	if(isset($_GET['user'])) {
		$user = getUserById($_GET['user'],$session["data"]["id"]);
	}
	if(!count($user))
		header("Location: 404.php");
	$me = $_GET['user'] == $session["data"]["id"];
?>
<!DOCTYPE html>
<html>
	<?php include "./layout/head.php"; ?>
<body>
	<?php include "./layout/topBar.php"; ?>
	<aside class="flex-parent">
		<div class="user-picture"><img alt="profile" width="200" id="profilePic" src="./res/anonymous.png"></div>
		<div class="user-details">
			<h2><?= $user[0]["firstName"] . " " . $user[0]["surname"] ?><br><small> @<?= $user[0]["username"] ?></small></h2>
			<?="<a id='followers-button' href='./php/userRouter.php?route=getFollowers&id=" . $_GET['user'] . "' class='submitButton'>" .  $user[0]["followers"] . " seguaci</a>"?>
			<?="<a id='followed-button' href='./php/userRouter.php?route=getFollowed&id=" . $_GET['user'] . "' class='submitButton'>" .  $user[0]["followeds"] . " seguiti</a>"?>	
			<?php if($me) { ?>
				<a id="profilePicButton" class="submitButton" href="#">Cambia immagine</a>
				<a href="./messages.php" class="submitButton">Messaggi</a>
				<a id="logoutButton" class="submitButton" href="./php/userRouter.php?route=logout">Esci</a>
			<?php } else { $sText = ($user[0]["following"] == "1")?"Segui gi&agrave;":"Segui";?>
				<a id="followButton" class="submitButton" href="./php/userRouter.php?route=follow&amp;followed=<?= $_GET['user'] ?>"><?php echo $sText ?></a>
			<?php } ?>
		</div>
	</aside>
	<main id="picturesContainer" class="flex-parent">
	</main>
	<div id="social-modal" class="modal">
		<div class="modal-body">
			<a class="modal-close" onclick="hideModal(this.parentNode.parentNode)">&times;</a>
			<ul id="social-list" class="chat-list scrollable">
				
			</ul>				
		</div>
	</div>

	<div id="change-modal" class="modal">
		<div class="modal-body">
			<a class="modal-close" onclick="hideModal(this.parentNode.parentNode)">&times;</a>
			<div class="flex-parent">
				<form id="change-form" enctype="multipart/form-data" action="./php/userRouter.php?route=setPic" method="POST">
						<div id="pic-file" class="inputfile wide">
							Seleziona immagine...
		        		</div>
						<input type="file" required name="pic"><br>
						<input class="submitButton wide" type="submit" value="Cambia">
				</form>
			</div>			
		</div>
	</div>
</body>
<script type="text/javascript">
	var userId = <?= $user[0]['id'] ?>;
	get("./php/userRouter.php?route=getProfilePic&id="+userId,function(result) {
		var dataObj = JSON.parse(result);
		if(!dataObj.length) return;
		var pic = dataObj.data[0];
		document.getElementById("profilePic").src = pic.path;
	});
	initShowcase("getUserFeed&id="+userId,"profile");
	
  	

  	makeAjaxAnchor(document.getElementById("logoutButton"),function(result) {
  		if(result == 1) window.location.href ="./index.php";
  	})

  	makeAjaxAnchor(document.getElementById("followButton"),function(result) {
  		var resultObj = JSON.parse(result);
  		document.getElementById("followButton").textContent = (resultObj[0].state == "f") ? "Segui gi\340":"Segui";
  	})

  	makeAjaxAnchor(document.getElementById("followed-button"),socialLoaded);
	makeAjaxAnchor(document.getElementById("followers-button"),socialLoaded);
  	setFileTrigger(document.getElementById("pic-file"),document.getElementById("change-form").pic);
  	setAjax(document.getElementById("change-form"),function(result) {
  		var picPath = JSON.parse(result)[0]["path"];
  		console.log(picPath);
  		document.getElementById("profilePic").src = picPath;
  	})
  	document.getElementById("profilePicButton").onclick = function() {
  		showModal(document.getElementById("change-modal"));
  	}
</script>
</html>