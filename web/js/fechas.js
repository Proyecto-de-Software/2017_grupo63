$( function() {
    var fechaDesde =  $('#desde').val() ;
    
    $( "#desde" ).datepicker();
  	$( "#desde" ).datepicker( "option", "dateFormat", "dd/mm/yy" );
  	$( "#desde" ).datepicker("setDate", fechaDesde);

  	var fechaHasta = $('#hasta').val()
 	$( "#hasta" ).datepicker();
  	$( "#hasta" ).datepicker( "option", "dateFormat", "dd/mm/yy" );
  	$( "#hasta" ).datepicker("setDate", fechaHasta);
  } );