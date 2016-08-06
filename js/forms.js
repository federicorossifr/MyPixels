//Controlla la validità di un singolo campo di un FORM
function checkInput(event) {
	if(event.target.validity.valid)
		event.target.className = event.target.getAttribute("data-class") + " valid";
	else
		event.target.className = event.target.getAttribute("data-class") + " invalid";
}

//Imposta un elemento di tipo form ad essere validato con la funzione checInput
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


//Simula il click su di un <input type="file" /> a partire
//dal click su di un altro elemento (container).
function setFileTrigger(container,fileInput,callback) {
	container.onclick = function() {
		fileInput.click();
	}

	fileInput.onchange = function(event) {
		container.textContent = event.target.value;
		if(callback)
			callback(event);
	}
}