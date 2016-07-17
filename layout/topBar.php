<nav id="topBar">
		<h1 class="brand bold">Pixels</h1>
		<?php if($session["length"] && $session["data"]["logged"]) { ?>
			<ul class="links">
				<li><a href=""><img src="./res/home.png"><img src="./res/homeDark.png"></a></li>
				<li><a href=""><img src="./res/heart.png"><img src="./res/heartDark.png"></a></li>
				<li><a href=""><img src="./res/explore.png"><img src="./res/exploreDark.png"></a></li>
				<li><a href=""><img src="./res/profile.png"><img src="./res/profileDark.png"></a></li>
			</ul>
		<?php } else { ?>
			<form id="loginForm" method="POST" action="./php/userRouter.php?route=authenticate" class="navForm">
				<input type="text" id="username" name="username" class="light" placeholder="Username">
				<input type="password" id="password" name="password" class="light" placeholder="Password">
				<input type="submit" class="submitButton regular" value="Accedi">
			</form>
		<?php } ?>
</nav>