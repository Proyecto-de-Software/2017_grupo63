function buscarAgua() {
  // This is a sample server that supports CORS.
  var url = 'https://api-referencias.proyecto2017.linti.unlp.edu.ar/tipo-agua';
  var xhr = createCORSRequest('GET', url);
  if (!xhr) {
    alert('CORS not supported');
    return;
  }

  // Response handlers.
  xhr.onload = function() {
    var text = xhr.responseText;
    var obj = JSON.parse(text);
    var listado = document.getElementById("sel3");
    var aguaPac = document.getElementById("aguaPac").innerHTML;
    var trim = $.trim(aguaPac); 
    for (i = 0; i < obj.length; i++) {
  		item=document.createElement("option");
  		item.text=obj[i].nombre;
  		item.value=obj[i].id;
      if (obj[i].id == trim) {
        item.selected = "true";
      };
  		listado.options.add(item);
  	}

	
  };

  xhr.onerror = function() {
    alert('Woops, there was an error making the request.');
  };

  xhr.send();
}

buscarAgua();