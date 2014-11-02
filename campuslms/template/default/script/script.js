function templateResizeAndler(e){
	/* Afficher/masquer l'entête (au bas de la page) */

/*	if ($(window).height() < $('body>section').outerHeight() && $('body>header').attr('data-hidden') != 'true'){
		$('body>header').stop().fadeOut();
		$('body>header').attr('data-hidden', 'true');
	}else if ($(window).height() > $('body>section').outerHeight() && $('body>header').attr('data-hidden') != 'false') {
		$('body>header').stop().fadeIn();
		$('body>header').attr('data-hidden', 'false');
	}*/

	/* gérer l'affichage du footer (dans la sidebar) */

	if ($('body>aside nav').offset().top + $('body>aside>nav').outerHeight() > $(window).height()-$('body>aside>footer').outerHeight()){
		$('body>aside').addClass('scrollable');
	}else{
		$('body>aside').removeClass('scrollable');
	}
}