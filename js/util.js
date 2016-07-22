function empty(element) {
    while(element.hasChildNodes())
      element.removeChild(element.firstChild);
}


function escape(str) {
  str = str.replace(/&/g, "&amp;");
  str = str.replace(/>/g, "&gt;");
  str = str.replace(/</g, "&lt;");
  str = str.replace(/"/g, "&quot;");
  str = str.replace(/'/g, "&apos;");
  return str;
}

function recape(str) {
  str = str.replace(/&amp;/g, "&");
  str = str.replace(/&gt;/g, ">");
  str = str.replace(/&lt;/g, "<");
  str = str.replace(/&quot;/g, "\"");
  str = str.replace(/&apos;/g, "'");
  str = str.replace(/&#039;/g, "'");  
  return str;	
}