function catchTags(inputElement,memory,display) {
	var tagz = [];
	console.log(inputElement);
	inputElement.oninput = function(event) {
		var displaySpans = [];
		var hashtagPattern = /#([a-zA-Z0-9])+( |$)/g;
		var contentTyped =  event.target.value;
		tagz = contentTyped.match(hashtagPattern);
		tagz = (tagz) ? tagz : [];
		empty(display);
		for(var i = 0; i < tagz.length; ++i) {
			tagz[i]=tagz[i].trim();
			displaySpans[i] = document.createElement("span");
			displaySpans[i].className = "hashtag";
			displaySpans[i].textContent = tagz[i];
			display.appendChild(displaySpans[i]);
		}
		memory.value = JSON.stringify(tagz);
	}
}

function checkInput(event) {
	if(event.target.validity.valid)
		event.target.className = event.target.getAttribute("data-class") + " valid";
	else
		event.target.className = event.target.getAttribute("data-class") + " invalid";
}

function checkForm(form) {
	var formElements = form.querySelectorAll("input:not([type='submit'])");
	for(var i = 0; i < formElements.length; ++i) {
		formElements[i].setAttribute("data-class",formElements[i].className);
		formElements[i].onblur = checkInput;
		formElements[i].oninvalid = function(event) {
			event.preventDefault();
		}
	}
}


function setFileTrigger(container,fileInput) {
	container.onclick = function() {
		fileInput.click();
	}

	fileInput.onchange = function(event) {
		container.textContent = event.target.value;
	}
}