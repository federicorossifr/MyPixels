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


</script>
</html>