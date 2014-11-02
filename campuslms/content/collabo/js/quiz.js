function saveQuiz(){
//	$('#theform').submit();
	doSaveQuiz();
	return false;
}

function doSaveQuiz(){
	var nbq = $('.qContainer').length;
	var nbr = 0;

	$('.qContainer').each(function(){
		if ($(this).find('input:checked').length > 0){
			$(this).removeClass('error');
			nbr++;
		}else{
			$(this).addClass('error');
		}
	});

	if (nbr >= nbq){
		var data = $('#theform').serialize();

		loadInMenuBar($("#theform").attr('action'), data, function(result){
//			console.log(result)

			var page = $('#menuBar').attr('data-which')
			var i = page.lastIndexOf("/");

			while(page.substr(i) != "/quiz"){
				page = page.substr(0,i);
				i = page.lastIndexOf("/");
			}

			i = page.lastIndexOf("/");
			page = page.substr(0,i);

			loadAjax(page, {'keepBars':true});

		});
		hideRightBar();

//		return true;
	}else{
		alert('Veuillez répondre à toute les questions');
		return false;
	}
}