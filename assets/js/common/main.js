$(document).ready(function(){

	//Show Shade
	$(document).on("click", ".view", function showScreen(){
		$('.screen').css({'opacity':'0','display':'block'});
			$('.screen').animate({opacity:1},300);
	});
	
	//Hide Shade
	$(document).on("click", ".screen, .close", function hideScreen(){
		
		$('.screen').css({'opacity':'0','display':'none'});
		$('.close').trigger('click');
		$('.screen').animate({opacity:0},300);
	});
	
});