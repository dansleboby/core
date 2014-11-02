$(function(){
	$('body.cours article.leconbtn').bind('click',function(e){
		if ($(this).find('a').hasClass('openInMenuBar')){
			loadInMenuBar($(this).find('a').attr('href'));
		}else{
			loadAjax($(this).find('a').attr('href'));
		}

		e.preventDefault();
		e.stopPropagation();
	});
});