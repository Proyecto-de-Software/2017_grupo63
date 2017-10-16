$(document).ready(function(){
    $("#configForm").submit(function(){
        
        var pag = $( '#paginado' ).val();
        if (pag < 1) {
          alert ('La cantidad de paginas debe ser positiva');
          return false ;
        }
    });
});