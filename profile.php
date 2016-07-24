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
		<div class="user-picture"><img width="200" id="profilePic" src="./res/anonymous.png"></div>
		<div class="user-details">
			<h2><?= $user[0]["firstName"] . " " . $user[0]["surname"] ?><br><small> @<?= $user[0]["username"] ?></small></h2>
			<?php if($me) { ?>
				<a id="logoutButton" class="submitButton" href="./php/userRouter.php?route=logout">Esci</a>
			<?php } else { ?>
				<a id="followButton" class="submitButton" href="./php/userRouter.php?route=follow&followed=<?= $_GET['user'] ?>">
				<?= ($user[0]["following"] == "1")  ? "Segui gi&agrave;":"Segui"; ?>
				</a>
			<?php } ?>				
		</div>
	</aside>
	<main id="picturesContainer" class="flex-parent">
	</main>
</body>
<script type="text/javascript">
	var userId = <?= $user[0]['id'] ?>;
	get("./php/userRouter.php?route=getProfilePic&id="+userId,function(result) {
		var dataObj = JSON.parse(result);
		if(!dataObj.length) return;
		var pic = dataObj.data[0];
		document.getElementById("profilePic").src = pic.path;
	});
  	function loadPictures(feedContainer) {
		get("./php/picRouter.php?route=getUserFeed&id="+userId,function(result){
			picsLoaded(result,feedContainer);
		});
  	}

  	loadPictures(document.getElementById("picturesContainer"));
  	makeAjaxAnchor(document.getElementById("logoutButton"),function(result) {
  		if(result == 1) window.location.href ="./index.php";
  	})

  	makeAjaxAnchor(document.getElementById("followButton"),function(result) {
  		var resultObj = JSON.parse(result);
  		document.getElementById("followButton").textContent = (resultObj[0].state == "f") ? "Segui gi\340":"Segui";
  	})

  	get("./php/userRouter.php?route=getNotifies",function(result) {
		displayNotifies(result,document.getElementById("notifies"),document.getElementById("notifies-count"),document.getElementById("picturesContainer"));
	});

  	makeActiveLink("profile");
</script>
</html>