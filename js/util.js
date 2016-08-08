//Elimina tutti i figli di un elemento element.
function empty(element) {
    while(element.hasChildNodes())
      element.removeChild(element.firstChild);
}

//Aggiunge la classe "active" al link di id "LinkId"
function makeActiveLink(linkId) {
  document.getElementById(linkId).className += " active";
  document.getElementById(linkId).className = document.getElementById(linkId).className.trim();
}


//Escape di una stringa prima dell'invio al server.
//I caratteri speciali vengono tradotti in entità HTML prima
//di venir inviati al server (MYSQL NON SUPPORTA I PRIMI)
function escape(str) {
  str = str.replace(/&/g, "&amp;");
  str = str.replace(/>/g, "&gt;");
  str = str.replace(/</g, "&lt;");
  str = str.replace(/"/g, "&quot;");
  str = str.replace(/'/g, "&apos;");
  return str;
}


//Inversa della prcedente. Da entità HTML a entità Javascript
//Le prime non vengono mostrate in HTML in quanto si utilizza 
//la proprietà textContent per l'iniezione del testo e non
//innerHTML per l'iniezione dell'HTML.
function recape(str) {
  str = str.replace(/&amp;/g, "&");
  str = str.replace(/&gt;/g, ">");
  str = str.replace(/&lt;/g, "<");
  str = str.replace(/&quot;/g, "\"");
  str = str.replace(/&apos;/g, "'");
  str = str.replace(/&#039;/g, "'");  
  return str;	
}