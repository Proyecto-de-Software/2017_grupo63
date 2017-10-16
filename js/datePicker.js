$( function(data) {
    var fecha =  $('#datepicker').val() ;
    
    $( "#datepicker" ).datepicker();
  	$( "#datepicker" ).datepicker( "option", "dateFormat", "dd/mm/yy" );
  	$( "#datepicker" ).datepicker("setDate", fecha);
  } );