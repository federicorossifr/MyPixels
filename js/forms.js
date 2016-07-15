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