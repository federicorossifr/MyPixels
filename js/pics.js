/* FUNZIONI DI APPOGGIO PER LA CREAZIONE DEGLI ELEMENTI DI UN ELEMENTO PIC */
function makeMoreContent(pic) {
	var moreContent = document.createElement("div");


    /*thumb up*/
   	var tupA = document.createElement("a");
    var tup = document.createElement("img");
   	if(pic.userLiked == 1)
    	tup.src = "./res/thumbActive.png";
    else
    	tup.src = "./res/thumb.png";
    tup.className = "thumb-up";
    var tupCount = document.createElement("span");
    tupCount.textContent = pic.up;
    tupA.appendChild(tup);


    /*thumb down*/
    var tdownA = document.createElement("a");
    var tdown = document.createElement("img");
    if(pic.userLiked == 0)
    	tdown.src = "./res/thumbActive.png";
    else
    	tdown.src = "./res/thumb.png";
   	tdown.className = "thumb-down";
   	var tdownCount = document.createElement("span");
    tdownCount.textContent = pic.down;
    tdownA.appendChild(tdown);
   
    setThumbAction(tdownA,tupA,pic,tupCount,tdownCount,0);
    setThumbAction(tupA,tdownA,pic,tupCount,tdownCount,1);

    var commentIcons = makeCommentIcons(pic);

    moreContent.appendChild(tupCount);
   	moreContent.appendChild(tupA);
   	moreContent.appendChild(tdownCount); 
   	moreContent.appendChild(tdownA);
   	moreContent.appendChild(commentIcons['count']);
   	moreContent.appendChild(commentIcons['icon']);
   	return moreContent;
}


function makeCommentIcons(pic) {
	var commentCount = document.createElement("span")
	commentCount.textContent = pic.comments;
	var commentIcon = document.createElement("img");
	commentIcon.width = "50";
	commentIcon.src = "./res/comment.png";
	return {"icon":commentIcon,"count":commentCount};
}


function setThumbAction(thumbClicked,otherThumb,pic,counterUp,counterDown,vote) {
	thumbClicked.href = "./php/picRouter.php?route=likePic&picId="+pic.id+"&vote="+vote;
	makeAjaxAnchor(thumbClicked,function(result) {
		var results = JSON.parse(result)[0];
		counterUp.textContent = results.up;
		counterDown.textContent = results.down;

		if(vote == 1) {
			pic.up = parseInt(pic.up) + 1;
			if(pic.userLiked == 0)
				pic.down = parseInt(pic.down) - 1;
		}
		if(vote == 0) {
			pic.down = parseInt(pic.down) + 1;
			if(pic.userLiked == 1)
				pic.up = parseInt(pic.up) - 1;
		}

		thumbClicked.querySelector("img").src = "./res/thumbActive.png";
		otherThumb.querySelector("img").src = "./res/thumb.png";
		if(vote == pic.userLiked) {
			thumbClicked.querySelector("img").src = "./res/thumb.png";
			pic.userLiked = undefined;
			pic.up = parseInt(pic.up) - 2;
		} else {
			pic.userLiked = vote;
		}


	})
}
/*********************************************/


function displayPic(container,pic) {
	var picElement;
    if(pic.mime == 1) {
      picElement = document.createElement("img");
    } else {
      picElement = document.createElement("video");
    }
    picElement.src = pic.path;
    picElement.className = "flexible-img";

    var creatorLabel = document.createElement("span");
    creatorLabel.className = "creator-label thin";
    creatorLabel.textContent = pic.username;


    var hoverMore = document.createElement("div");
    hoverMore.className = "pic-details";
   	var moreContent = makeMoreContent(pic);
   	hoverMore.appendChild(moreContent);
    empty(container);
    container.appendChild(creatorLabel);
    container.appendChild(picElement);
    container.appendChild(hoverMore);
    return moreContent;
}

function picIterator(feedContainer) {
	var containers = [];
	empty(feedContainer);
	for(var i = 0; i < globals.pics.length; ++i) {
		containers[i] = document.createElement("div");
		containers[i].className = "item flex flex-3"
		var contentToCenter = displayPic(containers[i],globals.pics[i]);
		feedContainer.appendChild(containers[i]);
		containers[i].setAttribute("data-pic",i);
		var contentSizes = getRenderedSizes(contentToCenter);
		var containerSizes = getRenderedSizes(containers[i]);
		var leftProp = (containerSizes.width - contentSizes.width)/2;
		contentToCenter.style.left = leftProp + "px";
		containers[i].onclick = function() {
			showPicModal(globals.pics[this.getAttribute("data-pic")],feedContainer);
		}
	}
}

function displayComments(result,container) {
	empty(container);
	var dataObj = JSON.parse(result);
    var comments = dataObj.data;
    var userSpan = [];
    var commentSpan = [];
    for(var i = 0; i < comments.length; ++i) {
    	userSpan[i] = document.createElement("span");
        userSpan[i].style.fontFamily = "rb";
        commentSpan[i] = document.createElement("span");
        userSpan[i].textContent = comments[i].username + " ";
        commentSpan[i].textContent = recape(comments[i].commentBody);
        container.appendChild(userSpan[i]);
        container.appendChild(commentSpan[i]);
        container.appendChild(document.createElement("br"));
    };
}

function displayCommentForm(container,pic,feedContainer) {
	var commentForm = document.createElement("form");
	commentForm.method = "POST";
	commentForm.className = "fixed-form";
	commentForm.action = "./php/picRouter.php?route=commentPic";
	var inputText = document.createElement("input");
	inputText.type = "text";
	inputText.name = "comment";
	var submitButton = document.createElement("input");
	submitButton.type ="submit";
	submitButton.className = "submitButton";
	submitButton.value = "Invia";
	var hiddenPicId = document.createElement("input");
	hiddenPicId.value = pic.id;
	hiddenPicId.type="hidden";
	hiddenPicId.name = "picId";
	commentForm.appendChild(inputText);
	commentForm.appendChild(submitButton);
	commentForm.appendChild(hiddenPicId);
	container.appendChild(commentForm);
	setAjax(commentForm,function(result) {
		if(!isNaN(result) && parseInt(result) > 0) {
			get("./php/picRouter.php?route=getPicComments&picId="+pic.id,function(result) {
	    		displayComments(result,container.firstChild);
	    		pic.comments = parseInt(pic.comments) +1;
	    		picIterator(feedContainer);
	    		inputText.value ="";
    		});
		};
	});
}

function showPicModal(pic,feedContainer) {
	var modalContent = document.createElement("div");
	modalContent.className = "flex-parent";
	var imgDiv = document.createElement("div");
	imgDiv.className ="flex flex-2";
	var img = document.createElement("img");
	img.src = pic.path;
	img.className = "flexible-img";
	imgDiv.appendChild(img);
	var cDiv = document.createElement("div");
	cDiv.className = "flex flex-2";
	var innerCDiv = document.createElement("div");
	cDiv.appendChild(innerCDiv);
	modalContent.appendChild(imgDiv);
	modalContent.appendChild(cDiv);
	var modal = createModal(modalContent);
	get("./php/picRouter.php?route=getPicComments&picId="+pic.id,function(result) {
    	displayComments(result,innerCDiv);
    	displayCommentForm(cDiv,pic,feedContainer);
    	showModal(modal);
    });
}

function picsLoaded(result,feedContainer) {
	var dataObj = JSON.parse(result);
	globals.pics = dataObj.data;
	picIterator(feedContainer);
}


function upSort(picA,picB) {
	var upA = picA.up;
	var upB = picB.up;
	return upA < upB;
}

function downSort(picA,picB) {
	var downA = picA.down;
	var downB = picB.down;
	return downA < downB;
}

function dateSort(picA,picB) {
	return new Date(picB.created) - new Date(picA.created);
}

function commentSort(picA,picB) {
	return picA.comments < picB.comments;
}


function doSort(sortType,newContainer) {
	switch(sortType) {
		case 'up':
			globals.pics.sort(upSort);
			picIterator(newContainer);
			break;
		case 'down':
			globals.pics.sort(downSort);
			picIterator(newContainer);
			break;
		case 'date':
			globals.pics.sort(dateSort);
			picIterator(newContainer);			
			break;
		default:
			globals.pics.sort(commentSort);
			picIterator(newContainer);
			break;
	}
}
