$(function(){
	$('body>section>article>*').hide();
	$('body>section>article>header>*').hide();
	$('body>section>article>header').show();

//	initLeconWhenReady();
});

function initLeconWhenReady(){
	if (!ready){
		setTimeout(initLeconWhenReady,200);
		return;
	}

	var delay = 0;

/*	$('body>section>article>header>*').each(function(e){
		$(this).delay(delay).fadeIn(500);
		delay += 200;
	});

	$('body>section>article>*').each(function(e){
		$(this).delay(delay).fadeIn(500);
		delay += 200;
	});*/
}