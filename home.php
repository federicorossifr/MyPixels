<?php
	include "./php/userController.php";
	$session = getSession();
?>
<!DOCTYPE html>
<html>
	<?php include "./layout/head.php"; ?>
<body>
	<?php include "./layout/topBar.php"; ?>
	<main id="picturesContainer" class="flex-parent">

	</main>
</body>

<script type="text/javascript" src="./js/globals.js"></script>
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

  	function displayPic(container,pic) {
  		var picElement;
	    if(pic.mime == 1) {
	      picElement = document.createElement("img");
	    } else {
	      picElement = document.createElement("video");
	    }
	    picElement.src = pic.path;
	    picElement.className = "flexible-img";

	    var hoverMore = document.createElement("div");
	    hoverMore.className = "pic-details";

	    var moreContent = document.createElement("div");
	   
	    var tup = document.createElement("img");
	    tup.src = "./res/thumb.png";
	    tup.className = "thumb-up";
	    var tupCount = document.createElement("span");
	    tupCount.textContent = pic.up;

	    var tdown = document.createElement("img");
	    tdown.src = "./res/thumb.png";
	   	tdown.className = "thumb-down";
	   	var tdownCount = document.createElement("span");
	    tdownCount.textContent = pic.down;

	    moreContent.appendChild(tupCount);
	   	moreContent.appendChild(tup);
	   	moreContent.appendChild(tdownCount); 
	   	moreContent.appendChild(tdown);



	   	hoverMore.appendChild(moreContent);

	    empty(container);
	    container.appendChild(picElement);
	    container.appendChild(hoverMore);
	    return moreContent;
  	}

  	function picsLoaded(result,feedContainer) {
  		var dataObj = JSON.parse(result);
    	var pics = dataObj.data;
   		var containers = [];
    	empty(feedContainer);
    	for(var i = 0; i < pics.length; ++i) {
      		containers[i] = document.createElement("div");
      		containers[i].className = "item flex flex-3"
      		var contentToCenter = displayPic(containers[i],pics[i]);
      		feedContainer.appendChild(containers[i]);

      		var contentSizes = getRenderedSizes(contentToCenter);
      		var containerSizes = getRenderedSizes(containers[i]);
      		var leftProp = (containerSizes.width - contentSizes.width)/2;
      		contentToCenter.style.left = leftProp + "px";
    	}
  	}


</script>
</html>