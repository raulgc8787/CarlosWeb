function menuResponsive (){
	
	$('#mp-menu').addClass('menuShow');
	$('#generalCont').addClass('moveall').css('position','fixed');
	$('.bkgblack').addClass('showBg');
}

function volver(){

	$('#mp-menu').removeClass('menuShow');
	$('#generalCont').removeClass('moveall').css('position','relative');
	$('.bkgblack').removeClass('showBg');
}


$(document).ready(function(){

	$('#mp-menu span').on('click', function(){
		var status = $(this).parent('li').children('ul').css('display');
		if(status == "block"){
			$(this).parent('li').children('ul').css('display','none');
		} else{
			$(this).parent('li').children('ul').css('display','block');
		}
		
	});
})

