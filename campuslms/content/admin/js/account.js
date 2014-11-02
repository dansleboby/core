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

	$('document').on('change','.dataFix',function(e){
		alert('Selected '+$(this).val());
	})
});

function validateImport(){
	if ($('#importstep').length == 1){
		switch($('#importstep').val()){
			case '2':
				if ($('.dataFix').length == 0){
					var types = {none:0,usercode:0,firstname:0,lastname:0,email:0,password:0}

					$('.dataTypeList').each(function(){
						if (types[$(this).val()] == undefined)
							types[$(this).val()] = 0;

						types[$(this).val()]++;
					})

					console.log('types',types);

					//Make sure email OR usercode is selected somewhere
					if (types.usercode == 0 && types.email == 0){
						alert('Veuillez sélectionner une colonne permettant d\'identifier les utilisateurs.');
						return false;
					}

					//Make sure password is selected somewhere
					if (types.password == 0){
						alert('Veuillez sélectionner une colonne contenant le mot de passe des utilisateurs.');
						return false;
					}

					//Make sure each type of field is only selected ONCE
					if (types.usercode > 1 || types.firstname > 1 || types.lastname > 1 || types.email > 1 || types.password > 1){
						alert('Chaque type de champ ne peut être sélectionné qu\'une seule fois.');
						return false;
					}
				}

				return true;
			break;
		}		
	}
}

//Also on enterprise.js
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