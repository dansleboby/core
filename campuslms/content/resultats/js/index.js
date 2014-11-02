$(function(){
	$('.titlebar').on('click',function(){
		var id = $(this).attr('data-for');
		if ($(this).hasClass('active')){
			$(this).removeClass('active');
			$('#resultats'+id).slideUp();//hide();
		}else{
			$(this).addClass('active');
			$('#resultats'+id).slideDown();//show();
		}
	})
})