$(function(){
	$('input#filter').on('keyup paste',function(){
		console.log('keyup');
		filtrer($('input#filter').attr('data-conteneur'), $('input#filter').attr('data-quoi'), $('input#filter').attr('data-ou'), $('input#filter').val());
	});
});