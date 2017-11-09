$( function() {
    var fecha =  $('#datepicker').val() ;
    $( "#datepicker" ).datepicker("setDate", fecha);
  	$( "#datepicker" ).datepicker({dateFormat: 'dd/mm/yy'});
  } );