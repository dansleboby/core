$(function(){
	$('.moreDetails').addClass('closed react').bind('click',function(){
		if ($(this).hasClass('closed')){
			$(this).find('ul').slideDown();
			$(this).removeClass('closed');
		}else{
			$(this).addClass('closed');
			$(this).find('ul').slideUp();
		}
	});

	$('input#filter').on('keyup paste',function(){
		filtrer($('input#filter').attr('data-conteneur'), $('input#filter').attr('data-quoi'), $('input#filter').attr('data-ou'), $('input#filter').val());
	});

	$('body>section a.openInMenuBar').bind('click',function(e){
		loadInMenuBar($(this).attr('href'));

		e.preventDefault();
		e.stopPropagation();
	});
});

//Also on account.js
function validateNewUser(e){
	if ($('#usercode').length == 1){
		if ($('#usercode').val().length == 0 && $('#email').val().length == 0){
			$('#usercode').focus();
			alert('Veuillez entrer un courriel ou un code interne.');
			e.preventDefault();
			return false;
		}
	}else{
		if ($('#email').val().length == 0){
			$('#email').focus();
			alert('Veuillez entrer un courriel.');
			e.preventDefault();
			return false;
		}
	}

	if ($('#insertmode').val() == "new"){
		if ($('#pass').val().length == 0){
				$('#pass').focus();
				alert('Veuillez entrer un mot de passe.');
				e.preventDefault();
				return false;
		}
	}
}