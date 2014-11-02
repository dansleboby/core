$(function(){
	$('div.textarea').addClass('hoverFx');
	$('div.textarea').bind('click',function(e){
		var name = $(this).attr('data-name');
		var value = $(this).html();

		showEditPopup('textarea', name, value);

		e.stopPropagation();
		e.preventDefault();
	});

	$('.reorderButton').click(function(){
		var forwhat = $(this).attr('data-for')+"Container";

		if ($(this).attr('data-organizing') == "true"){
			var data = [];

			if (reordoring > 1){
				if (confirm("Voulez-vous aussi sauvegarder l'ordre des autres sections ?")){
					//Save ALL sections
					$('.reorderButton').each(function(){
						$('#'+$(this).attr('data-for')+'Container>a').each(function(){
							data.push($(this).attr('id'));
						});
					});
				}else{
					//Save THIS section
					$('#'+forwhat+'>a').each(function(){
						data.push($(this).attr('id'));
					});
				}
			}else{
				$('#'+forwhat+'>a').each(function(){
					data.push($(this).attr('id'));
				});				
			}

			loadAjax(curUrl+"/ordre",{data:data});
		}else{
			reordoring++;
			//Go for it !
			$("#"+forwhat).disableSelection();
			$("#"+forwhat).sortable({scroll:false});
			$(this).html('Sauvegarder les modifications').attr('data-organizing','true')
		}
	})
});

var reordoring = 0;

function showEditPopup(type, name, value, placeholder, displayName){
	if (!displayName)
		displayName = name;

	if (!placeholder)
		placeholder = "Veuillez entrer votre contenu ici";

	var content = '<form method="post" action="'+window.location.href+'/save">	<h1>Modifier</h1><h2'+displayName+'</h2>	<div class="clr"></div>';

	if (1){
		content = content+'<textarea placeholder="'+placeholder+'" name="'+name+'" id="thedata">'+value+'</textarea>';
	}

	content = content+'</select><input type="submit" class="submit" value="Sauvegarder">	<a class="cancel" href="#">Annuler</a></form>';

	showMenuBar(content, type+''+name, true);
}

function validateAddFile(e){
	if ($('#menuBar').find('#nom').val().length == 0){
		alert('Veuillez nommer votre fichier.');
		$('#nom').focus();
		e.preventDefault();
		e.stopPropagation();
		return false;
	}

	if ($('#fichieruploadNb').length == 1){
		var filenb = $('#fichieruploadNb').val();
		if ($('#fileName'+filenb).length == 1){
			if ($('#fileName'+filenb).val() == ""){
				alert('Veuillez sélectionner votre fichier.');
				e.preventDefault();
				e.stopPropagation();
				return false;		
			}
		}
	}
}

function validateAddLien(e){
	if ($('#menuBar').find('#titre').val().length == 0){
		alert('Veuillez nommer votre lien.');
		$('#titre').focus();
		e.preventDefault();
		e.stopPropagation();
		return false;
	}

	if ($('#menuBar').find('#url').val().length == 0){
		alert('Veuillez entrer votre lien.');
		$('#url').focus();
		e.preventDefault();
		e.stopPropagation();
		return false;
	}

}