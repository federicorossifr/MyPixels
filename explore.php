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

	<aside id="searchUser">
		<input type="text" id="userSearch" list="users" placeholder="Cerca i tuoi amici...">
		<a class="submitButton" href="#">Vai</a>
		<datalist id="users">
		</datalist> 
	</aside>
	<aside id="orderPics">
		<div class="select-styler">
			<select id="orderSelector" >
				<option value="up">By thumbs up</option>
				<option value="down">By thumbs down</option>
				<option value="comment">By comments</option>
				<option value="date" selected>By date</option>
			</select>
		</div>
	</aside>


	<main id="picturesContainer" class="flex-parent">

	</main>
</body>

<script type="text/javascript">
	initShowcase("getRelatedFeed","explore");
	var globalSearchedUsers = [];
	var old = "";
	document.getElementById("userSearch").oninput = function(event) {
		var username = event.target.value;
		if(old == username) {goSelect(username); return;}
		if(username == "") return;
		get("./php/userRouter.php?route=searchByUsername&username="+username,function(result) {
			empty(document.getElementById("users"));
			var userData = JSON.parse(result).data;
			globalSearchedUsers = [];
			for(var i = 0; i < userData.length; ++i) {
				globalSearchedUsers[i] = document.createElement("option");
				globalSearchedUsers[i].setAttribute("value",userData[i].username);
				globalSearchedUsers[i].setAttribute("data-id",userData[i].id);
				document.getElementById("users").appendChild(globalSearchedUsers[i]);
			}
		});
		old = username;
	}

	function goSelect(username) {
		var ss = username;
		for(var i = 0; i < globalSearchedUsers.length; ++i) {
			if(ss == globalSearchedUsers[i].value) {
				window.location.href ="./profile.php?user="+globalSearchedUsers[i].getAttribute("data-id");
				break; return;
			}
		}
	}


</script>
</html>