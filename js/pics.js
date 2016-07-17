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
