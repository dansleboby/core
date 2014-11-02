function toggleNotes(e){
	if ($('.notecontainer.over').css('display') == "block"){
		$('.notecontainer.over').animate({'top':'100%'},function(){
			$(this).hide();
		});

		$('#notesButton').html("<strong>Résultats</strong>Afficher tous les résultats");

		$('#messageButton').css({'display':'block','width':'0','margin-right':'0','padding-left':'0','padding-right':'0'}).animate({'width':'155px','margin-right':'10px','padding-left':'6px','padding-right':'6px'});
	}else{
		$('.notecontainer.over').css({'top':'100%'}).show().animate({'top':"70px"});

		$('#notesButton').html("<strong>Retour</strong>Fermer le panneau");

		$('#messageButton').animate({'width':'0','margin-right':'0','padding-left':'0','padding-right':'0'},function(){
			$('#messageButton').hide();
		});
	}
	return false;
}

$(function(){
	$('select#logTriUser').on('change',function(){
		hideThings();
	});

	$('select#logTriSujet').on('change',function(){
		hideThings();
	});
})

function hideThings(){
	$('.newsRadioControlled').addClass('hidden');
	var uid = $('select#logTriUser').val();
	var sujet = $('select#logTriSujet').val();

	if (uid != null && sujet != null){
		for (var i=0;i<uid.length;i++){
			for (var j=0;j<sujet.length;j++){
				$('.newsRadioControlled.sujet'+sujet[j]+'.uid'+uid[i]).removeClass('hidden');
			}
		}
	}else if (uid != null){
		for (var i=0;i<uid.length;i++){
			$('.newsRadioControlled.uid'+uid[i]).removeClass('hidden');
		}
	}else if (sujet != null){
		for (var i=0;i<sujet.length;i++){
			$('.newsRadioControlled.sujet'+sujet[i]).removeClass('hidden');
		}
	}else{
		$('.newsRadioControlled').removeClass('hidden');
	}

}