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
	<a id="new-button" class="floating" href="#"><img alt="new" src="./res/new.png"></img></a>
	<?php include "./layout/creationModal.php"; ?>
</body>

<script type="text/javascript">
	initShowcase("getFeed","home",document.getElementById("picturesContainer"),"Non ho trovato nulla, segui qualche tuo amico o carica una foto");

	function previewOnChange(event,previewer,modal) {
		var imgAccept = ["jpg","jpeg","png"];
	    if (event.target.files && event.target.files[0]) {
	        var reader = new FileReader();
	        var extension = event.target.files[0].name.split('.').pop().toLowerCase();
	        reader.onload = function (e) {
	        		if(imgAccept.indexOf(extension) < 0) {
	        			alert("Tipo non supportato!");
	        			previewer.src = "./res/new.png";
	        			event.target.value = "";
	        		} else {
	            		previewer.src = e.target.result;
	            	}
	            	previewer.onload = function() {showModal(modal);}
	        }

	        reader.readAsDataURL(event.target.files[0]);
    	}
	}


	document.getElementById("new-button").onclick = function() {
		showModal(document.getElementById("creation-modal"));
	}

  	setFileTrigger(document.getElementById("pic-file"),document.getElementById("creation-form").pic,function(event) {
  		previewOnChange(event,
  						document.getElementById("previewer"),
  						document.getElementById("creation-modal"));
  	});
  	checkForm(document.getElementById("creation-form"));
  	setAjax(document.getElementById("creation-form"),function(result) {
  		initShowcase("getFeed","home");
  	})
</script>
</html>