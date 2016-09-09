
//Funzione che restituisce un oggetto contenente
//le dimensioni "a schermo" di un elemento HTML
function getRenderedSizes(element) {
		return {"width":element.clientWidth,"height":element.clientHeight}
	}

//Ritorna le dimensioni della finestra browser
function getWindowSizes() {
	return {"width":window.innerWidth,"height":window.innerHeight};
}


//Funzione che posiziona al centro della finestra browser un elemento
//date come parametro le sue dimensioni.
function placeAtScreenCenter(element,sizes) {
	var windowSizes = getWindowSizes();
	var leftProp = (windowSizes.width - sizes.width) /2;
	var topProp = (windowSizes.height - sizes.height) /2;
	element.style.top = topProp + "px";
	element.style.left = leftProp + "px";
};

//Funzione che si occupa di mostrare un modalElement
//al centro dello schermo
function showModal(modalElement) {
	modalElement.style.display = "block";
	var modalBody = modalElement.querySelector(".modal-body");
	var sizes = getRenderedSizes(modalBody);
	document.body.style.overflow = "hidden";
	placeAtScreenCenter(modalBody,sizes);
}


//Funzione mirata alla chiusura di un modalElement
function hideModal(modalElement) {
	modalElement.style.display = "none";
	document.body.style.overflow = "auto";
}


//Creazione di un modalElement a partire dal suo body passato
//come primo parametro. Ritora il modalElement creato.
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