<header>
	<nav id="topBar">
			<h1 class="brand bold">Pixels</h1>
			<?php if(!isset($panic)) { ?>
				<?php if(isset($session) && $session["length"] && $session["data"]["logged"]) { ?>
					<ul class="links">
						<li id="home"><a href="./home.php"><img alt="home" src="./res/home.png"><img src="./res/homeDark.png"></a></li>
						<li id="notifies-link">
							<a href="#" onclick="toggleNotifies()">
								<img alt="heart" src="./res/heart.png">
								<img src="./res/heartDark.png">
							</a>
							<?php include __DIR__ .  "/notifies.php"; ?>
							<span class="bold" id="notifies-count"></span>
						</li>
						<li id="explore"><a href=""><img alt="explore" src="./res/explore.png"><img src="./res/exploreDark.png"></a></li>
					<li id="profile"><a href="./profile.php?user=<?= $session["data"]["id"] ?>"><img alt="profile" src="./res/profile.png"><img src="./res/profileDark.png"></a></li>
					<span class="userLabel"><?= $session["data"]["username"] ?></span></ul>
				<?php } else { ?>
					<form id="loginForm" method="POST" action="./php/userRouter.php?route=authenticate" class="navForm">
						<input required type="text" id="username" name="username" class="light" placeholder="Username">
						<input required type="password" id="password" name="password" class="light" placeholder="Password">
						<input type="submit" class="submitButton regular" value="Accedi">
					</form>
				<?php } ?>
			<?php } ?>
	</nav>
</header>