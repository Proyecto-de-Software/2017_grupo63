function cargarTipoDoc() {
  // This is a sample server that supports CORS.
  var url = 'https://api-referencias.proyecto2017.linti.unlp.edu.ar/tipo-documento';
  var xhr = createCORSRequest('GET', url);
  if (!xhr) {
    alert('CORS not supported');
    return;
  }

  // Response handlers.
  xhr.onload = function() {
    var text = xhr.responseText;
    var obj = JSON.parse(text);
    var docuPAc = document.getElementById("docu").innerHTML;
    document.getElementById("docu").innerHTML = obj[docuPAc-1].nombre;  
  };

  xhr.onerror = function() {
    alert('Woops, there was an error making the request.');
  };

  xhr.send();
}

cargarTipoDoc();