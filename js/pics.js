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

    moreContent.appendChild(tupCount);
   	moreContent.appendChild(tupA);
   	moreContent.appendChild(tdownCount); 
   	moreContent.appendChild(tdownA);
   	return moreContent;
}



function setThumbAction(thumbClicked,otherThumb,pic,counterUp,counterDown,vote) {
	thumbClicked.href = "./php/picRouter.php?route=likePic&picId="+pic.id+"&vote="+vote;
	makeAjaxAnchor(thumbClicked,function(result) {
		var results = JSON.parse(result)[0];
		console.log(results);
		counterUp.textContent = results.up;
		counterDown.textContent = results.down;

		thumbClicked.querySelector("img").src = "./res/thumbActive.png";
		otherThumb.querySelector("img").src = "./res/thumb.png";

		if(vote == pic.userLiked) {
			thumbClicked.querySelector("img").src = "./res/thumb.png";
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

    var hoverMore = document.createElement("div");
    hoverMore.className = "pic-details";
   	var moreContent = makeMoreContent(pic);
   	hoverMore.appendChild(moreContent);
    empty(container);
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

		var contentSizes = getRenderedSizes(contentToCenter);
		var containerSizes = getRenderedSizes(containers[i]);
		var leftProp = (containerSizes.width - contentSizes.width)/2;
		contentToCenter.style.left = leftProp + "px";
	}
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
			console.log("not implemented");
			break;
	}
}
