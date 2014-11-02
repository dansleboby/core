$(function(){
	if ($('#err').length == 1){

	}else{
		$('body>*').hide();
		$('body>header').css({'margin-top':'-36px'}).show();

		$('body>header').delay(1000).animate({'margin-top':'-200px'});

		$('#fx').delay(700).fadeIn(1000);
	//	$('body>header').delay(500).fadeIn(1000);
		$('body>section').css({'box-shadow':'none'}).delay(1300).fadeIn(500);

		$({foo:0}).delay(1500).animate({foo:100},{duration:2500,step:function(val){
			var opacity = 0.35*val/100;
			var y = 20+30*val/100;
			var blur = 50+130*val/100;

			$('body>section').css({'box-shadow':'0 '+y+'px '+blur+'px '+y+'px rgba(255,255,255,'+opacity+')'});
		}});
	}

	$('.logintab').click(function(){
		$('.logintab').removeClass('active color_text').addClass('inactive');
		$(this).removeClass('inactive').addClass('active color_text');
		$('#loginmode').val($(this).attr('data-type'));
	});

	if ($('#loginmode').val() == "cie"){
		$('#loginEntreprise').click();
	}

	$(window).resize(resizeHandler);
	resizeHandler();
});

function resizeHandler(e){
	/* Mobile UI */
	if ($(window).width() < 430){
		$('body').addClass('mobile');
	}else{
		$('body').removeClass('mobile');
	}
}