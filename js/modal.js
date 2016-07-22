function getRenderedSizes(element) {
		return {"width":element.clientWidth,"height":element.clientHeight}
	}

function getWindowSizes() {
	return {"width":window.innerWidth,"height":window.innerHeight};
}

function placeAtScreenCenter(element,sizes) {
	var windowSizes = getWindowSizes();
	var leftProp = (windowSizes.width - sizes.width) /2;
	var topProp = (windowSizes.height - sizes.height) /2;
	element.style.top = topProp + "px";
	element.style.left = leftProp + "px";
};

function getBounds(element) {
	var sizes = getRenderedSizes(element);
	var windowSizes = getWindowSizes();
	var rTop = (windowSizes.height-sizes.height)/2;
	var rLeft = (windowSizes.width - sizes.width)/2;
	return {"rTop":rTop,"rLeft":rLeft}
}

function showModal(modalElement) {
	modalElement.style.display = "block";
	var modalBody = modalElement.querySelector(".modal-body");
	var sizes = getRenderedSizes(modalBody);
	document.body.style.overflow = "hidden";
	placeAtScreenCenter(modalBody,sizes);
}

function hideModal(modalElement) {
	modalElement.style.display = "none";
	document.body.style.overflow = "auto";
}

function createModal(content) {
	var modal = document.createElement("div");
	modal.className = "modal";
	var modalBody = document.createElement("div");
	modalBody.className = "modal-body";
	var modalClose = document.createElement("a");
	modalClose.className = "modal-close";
	modalClose.textContent = "\u02DF";
	modalClose.onclick = function() {
		hideModal(modal);
	}
	modalBody.appendChild(content);
	modal.appendChild(modalBody);
	modalBody.appendChild(modalClose);
	document.body.appendChild(modal);
	return modal;
}