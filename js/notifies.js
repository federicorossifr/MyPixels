//Funzione per la creazione del testo di una notifica a partire
//dal risultato estratto dal server. Un identificatore specifica il tipo
//della notifica.
function makeNotifyText(notify,feedContainer,anchor) {
	var action = notify.actionDone;

	switch(action) {
		case "COMMENT":
			var text = notify.username + " ha commentato la tua foto";
			anchor.onclick = function() {
				showPicModal(findPicById(notify.picInvolved),feedContainer);
				toggleNotifies();
			}
			return text;
		case "LIKE":
			anchor.onclick = function() {
				showPicModal(findPicById(notify.picInvolved),feedContainer);
				toggleNotifies();
			}
			return notify.username + " ha messo un pollice alla tua foto";
		case "FOLLOW":
			anchor.href = "./profile.php?user="+notify.userDone;
			return notify.username + " ora ti sta seguendo";
		case "MESSAGE":
			anchor.href = "./messages.php";
			return notify.username + " ti ha mandato un messaggio";

		default: return "NOT SUPPORTED";
	}
}

//Callback per l'estrazione AJAX di un array "result" di notifiche
function displayNotifies(result,container,counter,feedContainer) {
	var resultObj = JSON.parse(result);
	var notifies = resultObj.data;
	var lis = [];
	var as = [];
	var newCount = 0;
	for(var i = 0; i < notifies.length; ++i) {
		lis[i] = document.createElement("li");
		if(notifies[i].unread == "1") {
			lis[i].className = "new";
			newCount++;
		}
		as[i] = document.createElement("a");
		as[i].href = "#";
		as[i].textContent = makeNotifyText(notifies[i],feedContainer,as[i]);
		lis[i].appendChild(as[i]);
		container.appendChild(lis[i]);
	}
	counter.textContent = newCount;
}


//Funzione per che mostra/nasconde il popup delle notifiche a schermo.
function toggleNotifies() {
	var popup = document.getElementById("notifies-popup");
	var state = popup.style.display;

	if(state == "" || state == "none") {
		popup.style.display = "block";
		get("./php/userRouter.php?route=emptyNotifies",function() {});
	} else if(state == "block") {
		popup.style.display = "none";
		document.getElementById("notifies-count").textContent = 0;
		var lis = popup.querySelectorAll("li");
		for(var i = 0; i < lis.length; ++i) {
			lis[i].className = "";
		}
	}
}

