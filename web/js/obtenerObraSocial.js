function obtenerObraSocial() {
  // This is a sample server that supports CORS.
  var url = 'https://api-referencias.proyecto2017.linti.unlp.edu.ar/obra-social';
  var xhr = createCORSRequest('GET', url);
  if (!xhr) {
    alert('CORS not supported');
    return;
  }

  // Response handlers.
  xhr.onload = function() {
    var text = xhr.responseText;
    var obj = JSON.parse(text);
    //console.log(obj);
    var lista = document.getElementsByClassName("obraSocial");
    //debugger;
    //uno = $.trim(uno);
    //console.log(lista.length);
    for (i = 0; i < lista.length; i++) {
      var idObra = lista[i].childNodes[0].nodeValue;
      //console.log(idDoc);
      var obra = obj[idObra - 1];
      //console.log(doc.nombre);
      lista[i].childNodes[0].nodeValue = obra.nombre;
    }
  };

  xhr.onerror = function() {
    alert('Woops, there was an error making the request.');
  };

  xhr.send();
}

obtenerObraSocial()