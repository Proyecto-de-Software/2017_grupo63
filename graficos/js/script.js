function clean(){
	$('li .active').removeClass('active');
}


function setActive(elem){
	clean();
	elem.addClass('active');
}