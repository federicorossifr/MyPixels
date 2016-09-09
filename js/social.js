function displaySocial(buddies,container) {
		empty(container);
		var buddyLis = [];
		var buddyAs = [];
		for(var i = 0; i < buddies.length; ++i) {
			buddyLis[i] = document.createElement("li");
			buddyAs[i] = document.createElement("a");
			buddyAs[i].textContent = buddies[i].username;
			buddyAs[i].href = "./profile.php?user="+buddies[i].userId;
			buddyLis[i].appendChild(buddyAs[i]);
			container.appendChild(buddyLis[i]);
		}
	}

function socialLoaded(result) {
	var resultObj = JSON.parse(result);
	var buddies = resultObj.data;
	displaySocial(buddies,document.getElementById("social-list"));
	showModal(document.getElementById("social-modal"));
}  	