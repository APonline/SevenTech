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

	//Select Change
	$(document).on("change", ".drop-down", function changeSelect(){
		pg = $(this).attr('data-id');
		value = $(this).val();

		$.ajax({
			url: 'adminPosts',
			type: "POST",
			data: {'action':'filter','name':value},
			success: function(data){
				var newDoc = document.open("text/html", "replace");
					newDoc.write(data);
					newDoc.close();
			},
			error: function(data){
				console.log('bad',data);
			}
		});
	});

});
