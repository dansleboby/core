function updateQ(){
	var checked = $('#checkbox').prop('checked');

	if (checked){
		$('#noCheckbox').hide();
		$('#yesCheckbox').show();

		var pts = 0;

		$('.points').each(function(){
			if ($(this).val() > 0) {
				pts = pts+$(this).val()*1;
			}
		});
	}else{
		$('#yesCheckbox').hide();
		$('#noCheckbox').show();

		var pts = 0;
		$('.points').each(function(){
			if ($(this).val()*1 > pts) {
				pts = $(this).val()*1
			}
		});
	}

	$('#valeurQuestion').html(pts);
}

function newQuestion(){
	var nbq = $('#nbRep').val()*1;
	nbq++
	$('#nbRep').val(nbq);

	var html = '<div class="reponse"> <input type="text" class="text reponse" placeholder="Réponse" name="r'+nbq+'" id="r'+nbq+'"> <select class="points" name="r4v" id="r4v" onchange="updateQ();"><option value="-5">-5 points</option><option value="-4">-4 points</option><option value="-3">-3 points</option><option value="-2">-2 points</option><option value="-1">-1 point</option><option value="0" selected="selected">—</option><option value="1">1 point</option><option value="2">2 points</option><option value="3">3 points</option><option value="4">4 points</option><option value="5">5 points</option><option value="6">6 points</option><option value="7">7 points</option><option value="8">8 points</option><option value="9">9 points</option><option value="10">10 points</option></select>';

	$(html).insertBefore('#nbRep');

	return false;
}

function allowFichier(){
	$('#fichierQuestion').hide();
	$('#fichierContainer').show();

	return false;
}