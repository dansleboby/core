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