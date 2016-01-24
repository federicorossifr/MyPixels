


function get(url,callback) {
    var client = new XMLHttpRequest();
    client.onreadystatechange = function() {
      if (client.readyState == 4 && client.status == 200) {
        callback(client.responseText);
      }
    };    client.open("GET",url);
    client.send();
}


function post(formElement,callback) {
  var client = new XMLHttpRequest();

  client.onreadystatechange = function() {
    if (client.readyState == 4 && client.status == 200) {
      callback(client.responseText);
    }
  };

  var data = new FormData();
  var elements = formElement.querySelectorAll("input, select");
  for (var i = 0; i < elements.length; i++) {
    var element = elements[i];
    if(element.type == "file") {
      var files = element.files;
      for (var j = 0; j < files.length; j++) {
        var file = files[j]
        data.append(element.name,file);
      }
    } else
      data.append(element.name,element.value);
  }

  client.open("POST",formElement.action);
  client.send(data);
}
