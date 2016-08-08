/* FUNZIONI DI APPOGGIO PER LA CREAZIONE DEGLI ELEMENTI DI UN ELEMENTO PIC */

//Costruisce il contenuto che viene mostrato quando si passa con il mouse
//sopra un elemento pic.
function makeMoreContent(pic) {
	var moreContent = document.createElement("div");


    /*thumb up*/ //Creazione del "bottone" per pollice in su"
   	var tupA = document.createElement("a");
    var tup = document.createElement("img");
    tup.alt = "thumb up"
   	if(pic.userLiked == 1)
    	tup.src = "./res/thumbActive.png";
    else
    	tup.src = "./res/thumb.png";
    tup.className = "thumb-up";
    var tupCount = document.createElement("span");
    tupCount.textContent = pic.up;
    tupA.appendChild(tup);


    /*thumb down*/ //Creazione del bottone per "pollice in giù"
    var tdownA = document.createElement("a");
    var tdown = document.createElement("img");
    tdown.alt = "thumb down";
    if(pic.userLiked == 0)
    	tdown.src = "./res/thumbActive.png";
    else
    	tdown.src = "./res/thumb.png";
   	tdown.className = "thumb-down";
   	var tdownCount = document.createElement("span");
    tdownCount.textContent = pic.down;
    tdownA.appendChild(tdown);
   

   	//Imposto le azioni per i pulsanti appena creati
   	//devono essere creati con codice separato e non
   	//condiviso per poter interagire tra loro
   	//con azioni
    setThumbAction(tdownA,tupA,pic,tupCount,tdownCount,0);
    setThumbAction(tupA,tdownA,pic,tupCount,tdownCount,1);


    //Creazione dell'icona per i commenti e del contatore
    //dei commenti
    var commentIcons = makeCommentIcons(pic);

    moreContent.appendChild(tupCount);
   	moreContent.appendChild(tupA);
   	moreContent.appendChild(tdownCount); 
   	moreContent.appendChild(tdownA);
   	moreContent.appendChild(commentIcons['count']);
   	moreContent.appendChild(commentIcons['icon']);
   	return moreContent;
}


//Creo l'iconda dei commenti e il contatore dei commenti
//ritorna un oggetto con l'img contentente l'icona e 
//un campo testuale contente il contatore dei commenti
function makeCommentIcons(pic) {
	var commentCount = document.createElement("span")
	commentCount.textContent = pic.comments;
	var commentIcon = document.createElement("img");
	commentIcon.alt = "comment";
	commentIcon.width = "50";
	commentIcon.src = "./res/comment.png";
	return {"icon":commentIcon,"count":commentCount};
}


//Imposta le azioni al click dei pollici in su o in giù
function setThumbAction(thumbClicked,otherThumb,pic,counterUp,counterDown,vote) {
	thumbClicked.href = "./php/picRouter.php?route=likePic&picId="+pic.id+"&vote="+vote;
	makeAjaxAnchor(thumbClicked,function(result) {
		var results = JSON.parse(result)[0];
		counterUp.textContent = results.up;
		counterDown.textContent = results.down;

		if(vote == 1) { //Se il voto è in su
			pic.up = parseInt(pic.up) + 1; //aggiorno il contatore "up"
			if(pic.userLiked == 0) //se il voto precedente era "down"
				pic.down = parseInt(pic.down) - 1; //decremento il relativo contatore
		}
		if(vote == 0) { //stesso se il voto è down
			pic.down = parseInt(pic.down) + 1;
			if(pic.userLiked == 1)
				pic.up = parseInt(pic.up) - 1;
		}

		thumbClicked.querySelector("img").src = "./res/thumbActive.png";
		otherThumb.querySelector("img").src = "./res/thumb.png";

		//Se il voto è uguale a quello già espresso 
		//l'utente ha voluto eliminare completamente
		//la valutazione. Il voto è già stato incrementato
		//tuttavia, quindi si decrementa di due.
		if(vote == pic.userLiked) {
			thumbClicked.querySelector("img").src = "./res/thumb.png";
			pic.userLiked = undefined;
			pic.up = parseInt(pic.up) - 2; /*************/
		} else {
			pic.userLiked = vote;
		}


	})
}
/*********************************************/



//Gestisce la creazione e la disposizione
//di un elemento pic in un container
function displayPic(container,pic) {
	var picElement;
    picElement = document.createElement("img");
    picElement.src = pic.path;
    picElement.className = "flexible-img";
    picElement.alt = pic.description;

    var creatorLabel = document.createElement("a");
    creatorLabel.href = "./profile.php?user="+pic.userId;
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

//Itera sull'array globale di elementi pic
//per mostrarli in un feedContainer
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


//Funzione per la disposizione di un array di commenti "result"
//in un contenitore "container"
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



//Funzione per la creazione di un form per l'invio di commenti
//a una "pic" in un "container". Necessita di feedContainer e modal
//per l'aggiornamento della view di pic.
function displayCommentForm(container,pic,feedContainer,modal) {
	var commentForm = document.createElement("form");
	commentForm.method = "POST";
	commentForm.className = "fixed-form";
	commentForm.action = "./php/picRouter.php?route=commentPic";

	var inputText = document.createElement("input");
	inputText.type = "text";
	inputText.name = "comment";
	inputText.required = "required";

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
	
	checkForm(commentForm);

	setAjax(commentForm,function(result) {
		if(!isNaN(result) && parseInt(result) > 0) {
			get("./php/picRouter.php?route=getPicComments&picId="+pic.id,function(result) {
	    		displayComments(result,container.firstChild);
	    		pic.comments = parseInt(pic.comments) +1;
	    		picIterator(feedContainer);
	    		inputText.value ="";
	    		showModal(modal);
    		});
		};
	});
}


//Mostra il modal relativo ad una pic. Necessita
//di feedContainer per l'aggiornamento della vista.
function showPicModal(pic,feedContainer) {
	var modalContent = document.createElement("div");
	modalContent.className = "flex-parent";
	var imgDiv = document.createElement("div");
	imgDiv.className ="flex flex-2";

	var img = document.createElement("img");
	img.src = pic.path;
	img.className = "fixed-img";
	img.alt = pic.description;

	var picDescription = document.createElement("div");
	picDescription.textContent = recape(pic.description);
	picDescription.className = "flex flex-2";
	
	imgDiv.appendChild(img);
	
	var cDiv = document.createElement("div");
	cDiv.className = "flex flex-2";

	var innerCDiv = document.createElement("div");

	cDiv.appendChild(innerCDiv);

	modalContent.appendChild(imgDiv);
	modalContent.appendChild(cDiv);
	modalContent.appendChild(picDescription);
	var modal = createModal(modalContent);
	get("./php/picRouter.php?route=getPicComments&picId="+pic.id,function(result) {
    	displayComments(result,innerCDiv);
    	displayCommentForm(cDiv,pic,feedContainer,modal);
    	showModal(modal);
    });
}



//Callback per il caricamento ajax delle pic.
function picsLoaded(result,feedContainer) {
	var dataObj = JSON.parse(result);
	globals.pics = dataObj.data;
	picIterator(feedContainer);
}



//Ordinamento in base ai pollici in su
function upSort(picA,picB) {
	var upA = picA.up;
	var upB = picB.up;
	return upA < upB;
}


//Ordinamento in base ai pollici in giù
function downSort(picA,picB) {
	var downA = picA.down;
	var downB = picB.down;
	return downA < downB;
}


//Ordinamento in base alla data
function dateSort(picA,picB) {
	return new Date(picB.created) - new Date(picA.created);
}


//Ordinamento in base ai commenti
function commentSort(picA,picB) {
	return picA.comments < picB.comments;
}

//Switch per la scelta del criterio di ordinamento dell'array
//globale di pic (sort funzione di libreria jaavascript)
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


//Cerca tramite l'id l'elemento pic nell'arrya globale di Pic.
function findPicById(id) {
	for(var i = 0; i < globals.pics.length; ++i) {
		if(globals.pics[i].id == id)
			return globals.pics[i];
	}
	return undefined;
}