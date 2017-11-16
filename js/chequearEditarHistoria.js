$( function() {
    $('.editar').click(function(e){
    	console.log(e.currentTarget);
    	var dom = (e.currentTarget).parentNode;
    	var pediatraId = dom.childNodes[1].value;
    	var usuario = document.getElementById("usuario").value;
    	if (pediatraId != usuario ) {
    		alert("Solo el pediatra que cargo la historia clinica puede modificarla");
    		return false;
    	};
	})
  } );