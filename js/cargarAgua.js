function cargarAgua() {
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
    
    var aguaPac = document.getElementById("aguaPac").value;
    var trim = $.trim(aguaPac); 
    var nombre = obj[trim-1].nombre;
    document.getElementById("tipoAgua").innerHTML = nombre; 
	
  };

  xhr.onerror = function() {
    alert('Woops, there was an error making the request.');
  };

  xhr.send();
}

cargarAgua();