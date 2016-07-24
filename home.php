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
	get("./php/userRouter.php?route=getSession",function(result) {
    	var dataObj = JSON.parse(result);
	    if(!dataObj.length) return;
	    globals.loggedUser = dataObj.data;
	    loadPictures(document.getElementById("picturesContainer"));
  	});


  	function loadPictures(feedContainer) {
		get("./php/picRouter.php?route=getFeed",function(result){
			picsLoaded(result,feedContainer);
		});
  	}

  	document.getElementById("orderSelector").onchange = function(event) {
  		this.blur();
  		doSort(event.target.value,document.getElementById("picturesContainer"));
  	}

  	get("./php/userRouter.php?route=getNotifies",function(result) {
		displayNotifies(result,document.getElementById("notifies"),document.getElementById("notifies-count"),document.getElementById("picturesContainer"));
	});

	makeActiveLink("home");


</script>
</html>