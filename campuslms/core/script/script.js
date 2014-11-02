var ready = false;
var nbReady = 0;
var onPage = false;
var mobile = false;

var curUrl = null;

var base = null;

if(typeof(console) === 'undefined') {
    var console = {}
    console.log = console.error = console.info = console.debug = console.warn = console.trace = console.dir = console.dirxml = console.group = console.groupEnd = console.time = console.timeEnd = console.assert = console.profile = function() {};
}

$(function(){
	//Ajouter les resize handler 
	//Ajouter scroll handler
	$(window).resize(resizeHandler);
	$(window).scroll(scrollHandler);
	resizeHandler();

	base = $('base').attr('href');

	var url = window.location.href.toString().split(base)[1];

    if (history.pushState) {
	    $(window).bind('popstate', popHandler);
		setState(url, '');
	}else{
		//Vérifier si on a déjà un hashtag - Si oui, «click it»!
	}

	//Ajouter les événements
	addEventListenerToContent();
	addEventListenerToSidebar();
	addEventListenerToFooter();

	//Animer le contenu
	$('body>aside').animate({left:"0px"},1300,'easeInOutExpo');
//	$('body>section').delay(300).fadeIn(1200, function(){
//	$('body>header').fadeIn(700,function(){
		stopLoad(true);
		$('body').removeClass('loading');
		$('body').addClass('loaded');
//	});

	var hash = window.location.hash.substring(1);
	if (hash.substring(0,7) == "filtrer"){
		$('#filter').val(decodeURIComponent(hash.substring(8))).keyup();
	}

	$(window).bind('beforeunload', function(){ 
		//Make sure we are not uploading things
		var files = 0;
		for(var i=0;i<uploads.length;i++){
			if (uploads[i].pct < 101 && uploads[i].pct > 0){
				files++;
			}
		}

		if (files > 0){
			return "Des téléversements sont en cours. Quitter la page annulera le transfert des fichiers.";
		}else{
			return;
		}
	});

	$('#UIBackButton').click(function(){
		if (onPage && $('body>section').find('#inPageBackBtn').length > 0){
			$($('body>section').find('#inPageBackBtn').get(0)).trigger('click');
		}else{
			onPage = false;
			$('body').removeClass('onPage');			
		}
	});

	notificationDaemon_run()
});

function popHandler(e){
    console.log('Popstate');

    var state = e.originalEvent.state;
    if (state !== null) {
		loadAjax(state, undefined, undefined, true);
/*        if (state.page !== undefined) {
            ajaxLoadPage(state.page);
        }*/
    }
}

function hashHandler(e){

}

/*
	Ajouter les eventListener à la navigation principale
*/

function addEventListenerToSidebar(){
	$('body>aside nav a').bind('click',function(e){
		var parent = $(this).parent('li');

		//If we are on mobile, make sure we don't reload (if it's already loaded)
		if (mobile && parent.length > 0) {
			if (parent.hasClass('active')){
				onPage = true;
				$('body').addClass('onPage');

				e.preventDefault();
				e.stopPropagation();
				return;
			}
		}

		loadAjax($(this).attr('href'));

		if (parent.length > 0){
			//Remove class from older
			$(this).parent().siblings().removeClass('active');
			$(this).parent().parent().find('a').removeClass('color_text');
			//Add class to new one
			$(this).parent().addClass('active');
			$(this).addClass('color_text');
			//Show arrow
//			showSidefleche($(this).position().top+$('body>aside').scrollTop());
		}

		onPage = true;
		$('body').addClass('onPage');

		e.preventDefault();
		e.stopPropagation();
	});
}

function showSidefleche(top){
	var sideFleche = $('#sideFleche');

	if (sideFleche.length > 0){
		sideFleche.animate({'top':top+"px"});
	}else{
		var div = document.createElement('div');
		$(div).addClass('color_background');
		div.id='sideFleche';
		$(div).css({'width':0,'top':top+"px"});
		$('body>aside>nav').append(div);
		$(div).animate({'width':'29px'});
	}
}

function addEventListenerToFooter(){
	$('body>aside footer a.openInMenuBar').bind('click',function(e){
		loadInMenuBar($(this).attr('href'));

		e.preventDefault();
		e.stopPropagation();
	});
}

function showMenuBar(content, which, force){
	//On désactive la barre temporaire
	var currentBar = $('#menuBar');

	if (currentBar.length > 0){
		if (currentBar.attr('data-which') == which && force != true){
			return;
		}else{
			currentBar.addClass('old').attr('id','exMenuBar');
			autoUnbind(currentBar);
		}
	}

//	$('body>section').css({'pointer-events':'none'}).stop().animate({'zoom':'0.9','margin-left':'288px','opacity':'0.4'});
//	$('body>section').stop().animate({'opacity':'0.6'});

	var div = document.createElement('div');
	div.id = "menuBar";
	$(div).addClass('menuBar');
	$(div).html(content);
	$(div).attr('data-which',which);

	$('body').append(div);

	$(div).css({'bottom':($(div).outerHeight()*-1)+'px'});

	$(div).animate({'bottom':'0'},function(){
		$('#exMenuBar').remove();
		addEventListenerToMenuBar();
	});

//	$('body>header').animate({'bottom':($('body>header').outerHeight()*-1)+'px'});

}

function addEventListenerToMenuBar(){
	$('.menuBar a').on('click',function(e){
		if ($(this).attr('href') == '#'){
			hideMenuBar(true);
		}else{
			if ($(this).hasClass('openInMenuBar')){
				loadInMenuBar($(this).attr('href'));
			}else if ($(this).hasClass('openInRightBar')){
				loadInRightBar($(this).attr('href'));
			}else if ($(this).hasClass('openInContentPane')){
				loadAjax($(this).attr('href'));
			}else{
				//Don't preventDefault.
				return;
			}
		}

		e.preventDefault();
		e.stopPropagation();
	});

	$('.menuBar form').removeAttr('onsubmit').on('submit',function(e){
//		console.log('onsubmitting',$(this).attr('data-onsubmit'), this);
		if ($(this).attr('data-onsubmit')){
			if (window[$(this).attr('data-onsubmit')](e) === false){
				e.preventDefault();
				e.stopPropagation();
				return false;
			}
		}
		//Make sure every required fields are filled (automatically)

		if (confirmForm(this)){
			var data = $(this).serialize();

			if ($(this).hasClass('openInRightBar')){
				loadInRightBar($(this).attr('action'), data,function(){
//					refreshCurPage();
				});
				hideMenuBar();
			}else{
				loadInMenuBar($(this).attr('action'), data,function(){
//					refreshCurPage();
				});
			}
		}

		e.preventDefault();
		e.stopPropagation();
	});

	autoBind('#menuBar');
}

function hideMenuBar(showHeader){
	$('.menuBar').animate({'bottom':($('.menuBar').outerHeight()*-1)+'px'},function(){
		autoUnbind(this);
		$(this).remove();
	});
	if (showHeader){
//		$('body>section').css({'pointer-events':'auto'}).stop().animate({'zoom':'1','margin-left':'240px','opacity':'1'});
//		$('body>section').stop().animate({'opacity':'1'});
//		$('body>header').animate({'bottom':'0px'});
	}
}

/* 
	Refresh current page (and keep state as much as possible)
*/

function refreshCurPage(){
	if (currentstate){
//		var scrolltop = $('body').get(0).scrollTop

		//Refresh page

		console.log('LOAD AJAX');
			loadAjax(currentstate, null, 'update');
	}else{
//		alert('ERR 1001');
		console.log('ERR 1001');
	}
}


/* 
	Resize handler
*/

function resizeHandler(e){
	/* Detect mobile */
	if (($('html').hasClass('mobilable')) && ($(window).width() < 430 || $(window).height() < 350)) {
		$('body').addClass('mobile');
		mobile = true;
	}else{
		$('body').removeClass('mobile');
		mobile = false;
	}

	if (e != false && typeof templateResizeAndler == "function"){
		templateResizeAndler(e);
	}
}

function scrollHandler(e){
	//soon
	if ($('.banner').length == 1){
		var prev = $('.banner').prev();
		if ($('body').scrollTop() > $(prev).offset().top + $(prev).outerHeight()){
			$('.banner').addClass('fixed');
		}else{
			$('.banner').removeClass('fixed');
		}
	}
}

/*
	Gestion des requêtes ajax
*/

var animPage = false;
function loadAjax(url, data, callback, noPop){
	if (callback == undefined){
		if (mobile){
			$('body>section').html('').hide();
		}else{
			$('body>section').stop().fadeOut();
		}

		if (data){
			startLoad(false, data.keepBars);
		}else{
			startLoad(false);			
		}
	}

	if (callback == 'update'){
		animPage = false;
	}else{
		animPage = true;
	}

	if (data){
		if (typeof data === 'object'){
			data = $.param(data);
		}
		data = "ajax=true&"+data;
	}else{
		data = {'ajax':'true'};
	}

//	var jqxhr = $.post(url, data, function(result, textStatus, request){
	var jqxhr = $.ajax({
		type: "POST",
		url: url,
		data: data,
		dataType: "JSON",
		beforeSend: function(request, settings) {
			request.url = settings.url;
		},
		success: function(result, textStatus){
			decrementReady();
	//		ready = true;
			stopLoad();

	//		console.log(result);

			//Support refreshContent
			if (result['goTo']){
				loadAjax(result['goTo']);
			}else if (result['updateDOM']){
//				console.log('=====');
//				console.log(result['updateDOM']);
//				console.log('=====');
				for(var i=0;i<result['updateDOM'].length;i++){
					switch(result['updateDOM'][i]['action']){
						case 'remove':
							$(result['updateDOM'][i]['target']).remove();
						break;
						case 'update':
							$(result['updateDOM'][i]['target']).html(result['updateDOM'][i]['value']);
						break;
					}
				}
			}else if (result['refreshContent']){
				refreshCurPage();
	//			alert('Requested refresh');
			}


			//Support confirmText
			if (result['confirmText']){
				showMenuBar(result['confirmText'], 'confirm');
				setTimeout(function(){
					if ($('#menuBar').attr('data-which') == 'confirm'){
						hideMenuBar();
					}
				},1500);
				return;
			}

			if (result['content']){
				result['content'] = parseContent(result['content']);
			}

			//Handle notification

			//Handle chat

	//		if (callback != undefined){
			if ($.isFunction(callback)){
				console.log('Exec callback fct');
				callback(result);
			}else{
				if (callback != 'update'){
					//Change URL in browser for «url»
					setState(url, '', noPop);

					curUrl = url;

					//Update the header
				}

				var header = $.parseHTML(result['header'], document, true);

	//			console.log(result['header']);

				$.each( header, function( i, el ) {
					switch(el.nodeName.toUpperCase()){
						case 'TITLE':
							//Update title
							var title = $(el).html();
							document.title = title;
	//						console.log('Titre modifié : '+title);
						break;
						case 'LINK':
							var rel = $(el).attr('rel');
							if (rel == "stylesheet"){
								var media = $(el).attr('media');
								var href = SITE_URL+""+$(el).attr('href');
								var type = $(el).attr('type');

		//						$('head').append('<link rel="'+rel+'" media="'+media+'" href="'+href+'" type="'+type+'" />');
								$('head').append("<link rel='stylesheet' type='text/css' href='"+href+"' />");
								console.log('Nouveau CSS chargé : '+href);
							}
						break;
						case 'SCRIPT':
							incrementReady();

							//Load/exec script
							var src = $(el).attr('src');
							if (src){
								$.getScript(src,function(){
									decrementReady();
								});
								console.log('Script chargé : '+src);
							}
						break;
						default:
							console.log('NodeName inconnu...'+el.nodeName);
						break;
					}
				});

				if (callback != 'update'){
					$('body').removeClass();
					$('body').addClass('loaded '+url.split('/').join(' ')+(mobile?' mobile':'')+''+(onPage?' onPage':''));

	//Disabled for now Max 5 janvier 2014
	//				$('body>section').stop(true, true).css({'display':'none'});
//					$('body>section').stop(true, true).css({'display':'block'});

					$('body>section').stop(true, true).fadeIn();
				}else{
					var sctop = $('body').get(0).scrollTop;
				}

	//			console.log(result['content']);

				$('body>section').html(result['content']);
				addEventListenerToContent();

				$('body').get(0).scrollTop = sctop;

	//			if (callback != 'update'){
					contentReady();
	//			}
			}
		}
	});

	jqxhr.fail(function(jqXHR, textStatus, errorThrown){
		//Cancel request & stop loading ?
		decrementReady();
	//		ready = true;
		stopLoad();

		switch(jqXHR.url){
			case 'campuslms/core/ajax/notifications.php':
			case 'campuslms/core/ajax/checkSkypeStatus.php':
				//We can ignore that
			break;
			case '':

			break;
			default:
				//IDÉE - Répetter la requête UNE SEULE FOIS (si possible). Faire une erreur à la deuxième tentative.

//				alert('ERROR '+textStatus+' - '+errorThrown);
			break;
		}
	});
}

function parseContent(content){
	var dom = $("<div>"+content+"</div>");

	//Create image uploader
	dom.find("input[type=file]").each(function(){
		var data = newUpload(this);

		//Retirer le bouton original
		$(this).remove();
	});

//	console.log("===parsed");
//	console.log(content);
//	alert('parsed');

	return dom.html();
}

function contentReady(){
	if (ready == false){
		loaderTimer = setTimeout("contentReady()",300);
		return;
	}

/*	$('body>section').stop(true, true).fadeIn();

	var delay = 0;

	//Animate content pop up
	$('body>section>*').hide().each(function(index, value){
		if (this.nodeName.toUpperCase() == 'ARTICLE')
			delay += 40;
		else
			delay += 300;

		$(this).delay(delay).fadeIn(500);
	});*/
}

function loadInMenuBar(href, data, successFct){
	startLoad(false);

	hideMenuBar();
	hideRightBar();

	//Indiquer à showMenuBar qu'on veut peut-être réafficher le même popup, même s'il a le même URL.
	if (data){
		var force = true;
	}

	loadAjax(href,data,function(result){
		stopLoad();

		showMenuBar(result['content'], href, force);

		if (successFct){
			successFct(result);
		}
	});
}

function loadInRightBar(href, data, successFct){
	startLoad(false);

	hideMenuBar();
	hideRightBar();

	//Indiquer à showRightBar qu'on veut peut-être réafficher le même popup, même s'il a le même URL.
	if (data){
		var force = true;
	}

	loadAjax(href,data,function(result){
		stopLoad();

		showRightBar(result['content'], href, force);

		if (successFct){
			successFct(result);
		}
	});
}

function showRightBar(content, which, force){
	//On désactive la barre temporaire
	var currentBar = $('#rightBar');

	if (currentBar.length > 0){
		if (currentBar.attr('data-which') == which && force != true){
			return;
		}else{
			currentBar.addClass('rightBar').attr('id','exRightBar');
			autoUnbind(currentBar);
		}
	}

//	$('body>section').css({'pointer-events':'none'}).stop().animate({'zoom':'0.9','margin-left':'288px','opacity':'0.4'});
//	$('body>section').stop().animate({'opacity':'0.6'});

	var div = document.createElement('div');
	div.id = "rightBar";
	$(div).addClass('rightBar');
	$(div).addClass('color_background');
	$(div).html(content);
	$(div).attr('data-which',which);

	$('body').append(div);

	$(div).css({'right':'-240px'});

	$('#oldRightBar').animate({'right':'-240px'});

	$(div).animate({'right':'0'},function(){
		$('#oldRightBar').remove();
		addEventListenerToRightBar();
	});

//	$('body>header').animate({'bottom':($('body>header').outerHeight()*-1)+'px'});
}
function addEventListenerToRightBar(){
	$('.rightBar a').on('click',function(e){
		if ($(this).attr('href') == '#'){
			hideRightBar(true);
		}else{
			if ($(this).hasClass('openInMenuBar')){
				loadInMenuBar($(this).attr('href'));
			}else if ($(this).hasClass('openInRightBar')){
				loadInRightBar($(this).attr('href'));
			}else if ($(this).hasClass('openInContentPane')){
				loadAjax($(this).attr('href'));
			}else{
				//Don't preventDefault.
				return;
			}
		}

		e.preventDefault();
		e.stopPropagation();
	});

	$('.rightBar form').on('submit',function(e){
		//Make sure every required fields are filled (automatically)

		if (confirmForm(this)){
			var data = $(this).serialize();

			if ($(this).hasClass('openInMenuBar')){
				loadInMenuBar($(this).attr('action'), data,function(){
//					refreshCurPage();
				});
				hideRightBar();
			}else{
				loadInRightBar($(this).attr('action'), data,function(){
//					refreshCurPage();
				});
			}
		}

		e.preventDefault();
		e.stopPropagation();
	});

	autoBind('.rightBar');
}

function hideRightBar(showHeader){
	$('.rightBar').animate({'right':'-240px'},function(){
		autoUnbind(this);
		$(this).remove();
	});


	if (showHeader){
//		$('body>section').css({'pointer-events':'auto'}).stop().animate({'zoom':'1','margin-left':'240px','opacity':'1'});
//		$('body>section').stop().animate({'opacity':'1'});
//		$('body>header').animate({'bottom':'0px'});
	}
}

function test(){
	var elem = $('body>section');

	$(elem).find('table.fixed').each(function(){
		//Add container
		var tid = "fixedTableCtn"+new Date().getTime();

		$('<div id="'+tid+'" class="fixedTableCtn"></div>').insertAfter(this);

		//Move table there.
		$(this).appendTo('#'+tid);

		if ($(this).hasClass('fixed1col')){
			//Add table for fixed col (left)
			$('<table id="col1'+tid+'" class="fixedTableFixedCol"></div>').insertBefore(this);

//			var l = 0;

			//Find each 
			$(this).find('tr').each(function(){
				var first = $(this).find(">:first-child");

				console.log(first);

				var html = $(first).html();

				var w = first.innerWidth();
				var h = first.innerHeight();

				console.log(w+"x"+h);
				var col = $(first).css('background');

//				l = Math.max(l, w);

				var elem = $("<tr><td>"+html+"</td></tr>");
				$(elem).find('td').css({'width':w+'px','height':h+'px','background':col});
				$(elem).appendTo('#col1'+tid);
				$(this).css({'height':h+'px'});
				$(first).remove();
			});

//			$('<div id="tctn'+tid+'" class="tableContent"></div>').insertAfter('#col1'+tid);

//			$(this).appendTo('#tctn'+tid);
//			$('#tctn'+tid).css({'left':$('#col1'+tid).width()+'px'});
			$(this).css({'margin-left':$('#col1'+tid).width()+'px'});
		}
    });
}










function addEventListenerToContent(){
	$('body>section a').on('click',function(e){
		if ($(this).hasClass('openInMenuBar')){
			loadInMenuBar($(this).attr('href'));
		}else if ($(this).hasClass('openInRightBar')){
			loadInRightBar($(this).attr('href'));
		}else if ($(this).hasClass('openInContentPane')){
			loadAjax($(this).attr('href'));
		}else{
			//Don't preventDefault.
			return;
		}

		e.preventDefault();
		e.stopPropagation();
	});

	initRadioButtons();

	autoBind('body>section');
}


var blurTimeout = null;

function autoBind(elem){
	$(elem).find('.autoBottom').each(function(){
		this.scrollTop = this.scrollHeight;
	});

	$(elem).find('.onLoad').each(function(){
		var action = $(this).attr('data-onload');
		window[action](this);
	});

	$(elem).find('.bindReturn').keydown(function(e){
	    var code = (e.keyCode ? e.keyCode : e.which);
		if(code == 13) { //Enter keycode
			var action = $(this).attr('data-action');
			window[action](this);
			e.preventDefault();
			e.stopPropagation();
		}
	});

	$('input[type=color]').spectrum({
	    preferredFormat: "hex",
	    showInput: true,
	    cancelText: "Annuler",
	    chooseText: "Choisir"
	});

	$(elem).find('.specialField').not('.ready').each(function(){
		console.log('Found specialField ',this);

		$(this).addClass('ready');
		var id=$(this).attr('id');
		if (!id){
			id = uniqid();
			$(this).attr('id',id);
		}
 

		switch($(this).attr('data-specialType')){
			case 'selectHelper':
				console.log('create selectHelper '+id);

				$('<div class="helperFieldContainer" id="helperFieldContainer'+id+'"></div>').insertAfter(this);

				$('<div id="helperFieldView'+id+'" data-id="'+id+'" data-multiple="'+$(this).attr('multiple')+'" class="helperField placeholder">'+$(this).attr('data-placeholder')+'</div>').appendTo("#helperFieldContainer"+id);

				//If something is selected, WRITE IT !
				$(this).on('change',function(){
					var placeholder = true;
					if ($(this).val()){
						if (!$(this).attr('multiple')){
							if ($(this).find("option[value='"+$(this).val()+"']").hasClass('noSelect')){
								placeholder = true;
							}else{
								placeholder = false;
								$('#helperFieldView'+$(this).attr('id')).removeClass('placeholder').html($(this).find("option[value='"+$(this).val()+"']").html());
							}
						}else{
							placeholder = false;
							$('#helperFieldView'+$(this).attr('id')).removeClass('placeholder').html($(this).val().length+" sélection(s)");
						}
					}
					if (placeholder){
						$('#helperFieldView'+$(this).attr('id')).addClass('placeholder').html($(this).attr('data-placeholder'));
					}

					$('#helperBox').remove();
				}).trigger('change').hide();

				$('#helperFieldView'+id).on('click',function(){
					if ($('#helperBox').attr('data-interacted') == '1' && $('#helperBox').attr('data-for') == $(this).attr('data-id')){
						$('#helperBox').attr('data-interacted','0');
						return;
					}

					if (blurTimeout != null){
						clearTimeout(blurTimeout);
						blurTimeout = null;
					}

					//Remove previous dropdown if needed
					$('#helperBox').remove();
					//Generate the dropdown
//					$('<ul id="helperBox" class="color_border"></ul>').appendTo("#helperFieldContainer"+$(this).attr('data-id')).width($(this).outerWidth()+4).css({'top':$(this).outerHeight()+'px'});
					$('<ul id="helperBox" class="color_border"></ul>').appendTo("body").width($(this).outerWidth()+4);
					$('#helperBox').attr('data-interacted','0');
					$('#helperBox').attr('data-for',$(this).attr('data-id'));

					var pos = $(this).offset();
					var left = pos.left;
					var top = pos.top-2;

					$('#helperBox').css({'left':pos.left+'px','top':pos.top+'px','height':$(this).outerHeight()+'px'});

					if (top+210 > $(document).height()){
						top = $(document).height()-210;
					}

					$('#helperBox').animate({'left':left+'px','top':top+'px','height':'200px'},300);

					//If multiple, clear the field
					if ($(this).attr('data-multiple') == "multiple"){
						$(this).val('');
					}

					var input = document.createElement('input');
					$(input).addClass('helperField');
					$(input).attr('data-id',id);
					$(input).attr('data-multiple',$(this).attr('multiple'));
					$(input).attr('placeholder',"Écrivez pour trier...");

					$('<li class="searchField color_background"></li>').appendTo('#helperBox');
					$(input).appendTo("#helperBox .searchField");

					//Select everything
					$(input).focus().select();

					$('#helperBox').click(function(){
						$('#helperBox').attr('data-interacted','1');
					})

					$(input).on('mouseup',function(e){
						e.preventDefault();
					}).on('keyup',function(){
						$('#helperBox').find('li.result, li.noresult').remove();
						showVisibleThings = false;
						var multiple = $(this).attr('data-multiple') == "multiple";

						if ($(this).val().length == 0){
							//Si multiple & quelque chose de sélectionné...
							if (multiple && $("#"+$(this).attr('data-id')+' option:selected').length > 0){
								$("#"+$(this).attr('data-id')+' option').addClass('hidden');
								$("#"+$(this).attr('data-id')+' option:selected').removeClass('hidden');
								showVisibleThings = true;
							}else if (multiple){
								$("#"+$(this).attr('data-id')+' option').removeClass('hidden');
								showVisibleThings = true;
							}else{
								//Sinon, tout écrire
//								$('#helperBox').append('<li data-value="starter" class="">Commencez à écrire...</li>');
								$("#"+$(this).attr('data-id')+' option').removeClass('hidden');
								showVisibleThings = true;
							}
						}else{
							//Filtrer
							filtrer("#"+$(this).attr('data-id'), "option", null, $(this).val());
							showVisibleThings = true;
						}

						if (showVisibleThings){
							//Show everything that fit on a dropdown
							var i = 0;
							$("#"+$(this).attr('data-id')+' option').not('.hidden, .noSelect').each(function(){
								i++;
								//If multiple, add a checkbox on the left of everything and add a select all / deselect all options
								if (multiple){
									$('#helperBox').append('<li class="result" data-value="'+$(this).attr('value')+'"><label for=helperBoxValue'+$(this).attr('value')+'"><input type="checkbox" name="helperBoxValue'+$(this).attr('value')+'" id="helperBoxValue'+$(this).attr('value')+'"/>'+$(this).html()+'</label></li>');

									if ($(this).is(':selected')){
										$('#helperBoxValue'+$(this).attr('value')).prop('checked', true);
									}
								}else{
									$('#helperBox').append('<li class="result" data-value="'+$(this).attr('value')+'">'+$(this).html()+'</li>');
								}
							});

							//Aucun résultat
							if (i == 0){
								$('#helperBox').append('<li class="noresult">Aucun résultat</li>');
/*							}else if (multiple){
								$('#helperBox').append('<li class="result" data-value="deselectAll">Tout déselectionner</li>');*/
							}else{
								$('#helperBox').append('<li class="result" data-value="deselectAll">Tout déselectionner</li>');
							}

							$('#helperBox li.result').on('mousedown',function(){
								if (blurTimeout != null){
									clearTimeout(blurTimeout);
									blurTimeout = null;
								}

								$('#helperBox').attr('data-interacted','1');
							}).on('click',function(){
								switch($(this).attr('data-value')){
									case 'deselectAll':
										$('#'+$('#helperBox').attr('data-for')).val(null);
										$('#'+$('#helperBox').attr('data-for')).trigger('change');
	//									var field = $('#helperFieldContainer'+$('#helperBox').attr('data-for')+' input.helperField');
										$('#helperBox').remove();
	//									field.val('').focus().trigger('focus')
									break;
									case '':
									case 'starter':
									case undefined:
										//Nothing to do
	//									var field = $('#helperFieldContainer'+$('#helperBox').attr('data-for')+' input.helperField');
										$('#helperBox').remove();
	//									field.val('').focus().trigger('focus')
									break;
									default:
										if ($('#'+$('#helperBox').attr('data-for')).attr('multiple')) {
											if ($(this).find('input').is(':checked')) {
												//Uncheck it
												$(this).find('input').prop('checked',false);
												var val = $('#'+$('#helperBox').attr('data-for')).val();
												if ($.inArray($(this).attr('data-value'), val) >= 0){
													val.splice($.inArray($(this).attr('data-value'), val), 1 );
													$('#'+$('#helperBox').attr('data-for')).val(val);
												}

												$('.helperField').focus();
											}else{
												//Check it
												$(this).find('input').prop('checked',true);
												var val = $('#'+$('#helperBox').attr('data-for')).val();
												if (val == null){
													val = new Array($(this).attr('data-value'));
												}else{
													val.push($(this).attr('data-value'));
												}
												$('#'+$('#helperBox').attr('data-for')).val(val);
											}
										}else{
											$('#'+$('#helperBox').attr('data-for')).val($(this).attr('data-value'));
										}

										$('#'+$('#helperBox').attr('data-for')).trigger('change');

								}

//								$('#helperFieldContainer'+$('#helperBox').attr('data-for')+' input.helperField').focus();
							});					}
					}).on('blur',function(){
						if (blurTimeout != null){
							clearTimeout(blurTimeout);
						}

						//Kill everything after 250ms (if we clicked on the dropdown, cancel the timer)
						blurTimeout = setTimeout(function(){
							if ($('#helperBox').attr('data-interacted') == '0') {
								$('#'+$('#helperBox').attr('data-for')).trigger('change');
							}
						},250);
					});
 
					$(input).trigger('keyup');
				});
			break;
			case 'wysiwyg':
				var id = $(this).attr('id')+"_"+uniqid();
				$(this).attr('id',id);

				$(this).tinymce({
				    script_url : base+'campuslms/core/script/tinymce/tinymce.min.js',
//				    inline: true,
				    toolbar: "bold italic strikethrough underline | subscript superscript | bullist numlist outdent indent",
				    statusbar : false,
				    resize:false,
				    menubar: false
				});
			break;
			case 'dateHelper':
				$(this).datepicker({
					dateFormat: "yy-mm-dd" 
				});

    $( "#datepicker" ).datepicker({
      altField: "#alternate",
      altFormat: "DD, d MM, yy"
    });


			break;
			default:

			break;
		}
	})
}

function autoUnbind(elem){
	$(elem).find('.onUnload').each(function(){
		var action = $(this).attr('data-onunload');
		window[action]();
	});
}

//Skype daemon (check status)
function skypeDaemon_check(elem){
	console.log(elem);
	var id = $(elem).attr('data-id');
	var username = $(elem).attr('data-username');

	var data = {'id':id,
				'username':username
				}
	//Ping server to check skype status
	loadAjax("campuslms/core/ajax/checkSkypeStatus.php", data, function(result){

		$('.skypeButton'+result.id+" span").removeClass().addClass(result.status).html(result.text);
	});
}

//////////
//	DAEMON
//////////

var daemon = new Object();


//Notification daemon
function notificationDaemon_start(){
	if (daemon.notification != -1){
		notificationDaemon_stop();
	}
	console.log('START notificationDaemon');
	daemon.notification = setTimeout(notificationDaemon_run,20000);
}

function notificationDaemon_stop(){
	console.log('STOP notificationDaemon');
	clearTimeout(daemon.notification);
	daemon.notification = -1;
}

function notificationDaemon_run(){
	console.log('RUN notificationDaemon');
	//Trouver le plus récent
	var nb = $('#lienNotifications').attr('data-nb');

	loadAjax("campuslms/core/ajax/notifications.php", {'prevNb':nb,'isDaemon':'true'}, function(data){
		$('#lienNotifications').attr('data-nb',data.nb);

		if (data.nb > 0){
			$('#lienNotifications').addClass('unread');

			if (data.delta > 0){
//				alert(data.alert);
			}
		}else{
			$('#lienNotifications').removeClass('unread');
		}
		console.log('RAN notificationDaemon');

		if (daemon.notification != -1){
			notificationDaemon_start();
		}
	});
}

//Message daemon
function msgDaemon_start(){
	if (daemon.msg != -1){
		msgDaemon_stop();
	}
	console.log('START msgDaemon');
	daemon.msg = setTimeout(msgDaemon_run,6000);
}

function msgDaemon_stop(){
	console.log('STOP msgDaemon');
	clearTimeout(daemon.msg);
	daemon.msg = -1;
}

function msgDaemon_run(){
	console.log('RUN msgDaemon');
	//Trouver le plus récent
	var newest = $('#msgContainer>p').last().attr('data-id');
	var to = $('#theMessage').attr('data-to');

	loadAjax("campuslms/core/ajax/chatUpdate.php", {'newest':newest,'id_user':to,'isDaemon':'true'}, function(data){
		console.log('RAN msgDaemon');
		msgDaemon_runComplete(data);
		if (daemon.msg != -1){
			msgDaemon_start();
		}
	});
}

function msgDaemon_runComplete(data){
	console.log('UPDATE msgDaemon ('+data.length+')');
//	console.log(data);

	if (data.length > 0){
		for(var i=0;i<data.length;i++){
			var p = document.createElement('p');
			$(p).attr('data-id',data[i].id);
			if (data[i].self == true){
				$(p).addClass('right color_background');
			}else{
				$(p).addClass('left rcolor_background');
			}

			var str = '<time datetime="'+data[i].time+'">'+data[i].timed+'</time>'+data[i]['text'];

//			console.log('    '+str);
			$(p).html(str);

			$(p).appendTo('#msgContainer');
		}

		$('#msgContainer').animate({'scrollTop':$('#msgContainer').get(0).scrollHeight+'px'},1000)
	}
}

//Send message
function sendMessage(){
	var msg = $('#theMessage').val();

	if (msg.length > 0){
		var url = $($('.rightBar').get(0)).attr('data-which');
		var newest = $('#msgContainer>p').last().attr('data-id');
		var to = $('#theMessage').attr('data-to');

		msgDaemon_stop();

		loadAjax(url, {'theMessage':msg,'newest':newest,'id_user':to,'isDaemon':'true'}, function(data){
			msgDaemon_runComplete(data);

			msgDaemon_start();
		});

		$('#theMessage').val('');
	}
}

/*
	Gestion de l'affichage du «loader»
*/

var loaderTimer = null;

function startLoad(clearContent, keepBars){
	incrementReady(true);
//	ready = false;

	if (clearContent == undefined)
		clearContent = true;

	if (!keepBars){
		hideMenuBar();
		hideRightBar();
	}

	if (clearContent){
//		IDÉE - ANIMATE CONTENT TRANSITION
//		$('body>section').stop().fadeOut(function(){
//			$('body>section>*').hide();
//		});
	}

	//Cancel previous loading
	if (loaderTimer != null){
		clearTimeout(loaderTimer);
		loaderTimer = null;
	}
//	console.log('loadcheck 2'+loaderTimer);

	//Show loadbar in 350ms
	loaderTimer = setTimeout('showLoad()',450);
//	console.log('loadcheck 3'+loaderTimer);
}

function showLoad(){
//	console.log('loadcheck 4'+loaderTimer);
	loaderTimer = null;

	$('#loadbar').stop().remove();
/*
	var loader = $('#loadbar');

	if (loader.length == 0){*/
		var loader = document.createElement('div');
		$(loader).attr('id','loadbar').addClass('color_border color_background');
		$('body').append(loader);
		$(loader).html('<div></div>');
//	}

	//Show loader and init default 25sec progress bar.
	$(loader).stop().css({'top':'-30px'}).animate({'top':'0'});
	$('#loadbar>div').stop().css({'width':'0'}).animate({'width':'60%'},25000,'easeOutCirc');
}

function stopLoad(noAnim){
//	console.log('loadcheck 5'+loaderTimer);
	if (loaderTimer){
		clearTimeout(loaderTimer);
		loaderTimer = null;
	}else{
		$('#loadbar>div').stop().animate({'width':'100%'},200);
		$('#loadbar').delay(100).animate({'top':'-30px'},function(){
			$('#loadbar').remove();
		});
	}
//	console.log('loadcheck 6'+loaderTimer);

	decrementReady();
//	ready = true;

	if (noAnim){
		$('#loader').stop().css({'display':'none'});
	}else{
		$('#loader').stop().fadeOut(200);
	}
}


/* 
	Gestion du téléversement 
*/

var uploads = new Array();

function newUpload(ou){
	var uploadNb = uploads.length;

	var data = {'nb':uploadNb,
				'name':$(ou).attr('name'),
				'id':$(ou).attr('id'),
				'ready':false,
				'multiSelection':false,
				'ref':null,
				'uploader':null,
				'startUpload':null,
				'pct':null
				}

	//Ajouter le placeholder à la place du bouton précédent

	$('<div class="fileUpload" id="fileUpload'+uploadNb+'" data-nb="'+uploadNb+'"><input type="hidden" name="'+$(ou).attr('name')+'uploadNb" id="'+$(ou).attr('name')+'uploadNb" value="'+uploadNb+'"/><input type="hidden" name="fileRef'+uploadNb+'" id="fileRef'+uploadNb+'"/><input type="hidden" class="fileName" name="fileName'+uploadNb+'" id="fileName'+uploadNb+'"/><a class="selectFiles loading" id="selectFiles'+uploadNb+'">Choisir fichier</a><div class="filelist" id="filelist'+uploadNb+'">Veuillez choisir votre fichier</div></div>').insertAfter(ou);


	//Obtenir la référence uploadRef
	loadAjax(base+"campuslms/core/ajax/upload.php", data, function(result){
		uploads[result.nb].ref = result.ref;
		$('#fileRef'+result.nb).val(result.ref);
		$('#selectFiles'+result.nb).removeClass('loading');

		//Initialize image upload
		setTimeout('initUploads('+result.nb+')',150);
	});

	//Save data
	uploads.push(data);

	return data;
}

function initUploads(nb){
	console.log('initUpload_'+nb);

	var v = uploads[nb];
	console.log(v);

//	$.each(uploads,function(i, v){
		if ($('#fileUpload'+v.nb).length > 0){
			//Just in case...
			if (v.ready)
				return;

			if ($('#uploadContainer').length == 0){
				var div = document.createElement('div');
				$(div).attr('id','uploadContainer');
				$('body').append(div);
			}

//REMOVE DOUBLE CAMPUSLMS HERE

			var uploader = new plupload.Uploader({
				runtimes : 'html5,flash,silverlight,html4',
				browse_button : 'selectFiles'+v.nb,
				container : 'uploadContainer',
				max_file_size : '30mb',
				chunk_size: '1mb',

				// Resize images on clientside if we can
				resize : {
					width : 1400,
					height : 804,
					quality : 90,
					preserve_headers : false
				},
				multi_selection : false,
				url : base+'campuslms/core/ajax/upload.php',
				flash_swf_url : base+'campuslms/lib/upload/plupload/plupload.flash.swf',
				silverlight_xap_url : base+'lib/upload/plupload/plupload.silverlight.xap',
				multipart_params : {
			        "uploadId" : uploads[v.nb].ref
			    },
			    nb:v.nb,
			    ref:uploads[v.nb].ref
			});

			uploader.init();

			uploader.bind('FilesAdded', function(up, files) {
				console.log('FilesAdded!');
				$.each(files, function(i, file) {
					$('#fileName'+up.settings.nb).val(file.name + ' (' + plupload.formatSize(file.size) + ')');

					$('#filelist'+up.settings.nb).html(file.name + ' (' + plupload.formatSize(file.size) + ')');
				});

				up.refresh(); // Reposition Flash/Silverlight
			});

			uploader.bind('UploadProgress', function(up, file) {
				console.log('UploadProgress!');
				uploads[up.settings.nb].pct = file.percent;

				$('#uploadLine'+up.settings.nb+'>div').css({'width':file.percent+'%'});
				$('#uploadLine'+up.settings.nb+'>p.pct').html(file.percent+'%');
			});

			uploader.bind('Error', function(up, err) {
				console.log('Error!');
				switch (err.code) {
					case '101': //Ouvrir fichier final
					case '102': //Ouvrire partie
					case '103': //Déplacer fichier téléverser
					default:
						alert('Une erreur '+err.code+' est survenue avec le fichier '+err.file.name+". Veuillez réessayer. Code d'erreur : X1-"+err.code);// : \n\r "+err.message);
					break;
				}

				removeUploader(up.settings.nb);

				console.log('FILE ERROR');
				console.log(up);
				console.log(err);

				up.refresh(); // Reposition Flash/Silverlight
			});

			uploader.bind('FileUploaded', function(up, file) {
				console.log('FileUploaded!');
				$('#uploadLine'+up.settings.nb+'>div').css({'width':'100%'});
				$('#uploadLine'+up.settings.nb+'>p.pct').html('100%');

				$('#uploadLine'+up.settings.nb).delay(1000).slideUp(function(){
					removeUploader(up.settings.nb);
				});
			});

			v.uploader = uploader;
			v.ready = true;
			v.startUpload = function(){
				this.uploader.start();
			}

		}else{
			//Callback
			setTimeout('initUploads('+nb+')',150);
		}
//	});
}

function removeUploader(nb){
	var uploader = $('#uploadLine'+nb);

	uploads[nb].pct = 101;

	if (uploader.length > 0){
		$(uploader).remove();
		if ($('#uploadbox>div').length == 0)
			$('#uploadbox').remove();
	}
}



function doStartUpload(nb){
	var uploadBox = $('#uploadbox');

	if (uploadBox.length == 0){
		uploadBox = $('<div id="uploadbox" class="color_background"></div>');
		uploadBox.appendTo('body');
	}

	var newDiv = $('<div id="uploadLine'+nb+'"><div></div><p class="nom">'+uploads[nb].uploader.files[0].name+'</p><p class="pct">0%</p></div>').css({'display':'none'});

	uploadBox.append(newDiv);

	newDiv.slideDown();

	uploads[nb].startUpload();
}


/*
	Form validation
*/

function confirmForm(form){
	var err = 0;

	//Perform form validation

	//If no error, start file upload if needed
	if (err == 0){
		$(form).find('.fileUpload').each(function(){
			if ($(this).find('.fileName').val().length > 0){
				doStartUpload($(this).attr('data-nb'));
			}
		});

		return true;
	}

	return false;
}

/* 
	Gestion de la navigation 
*/

var currentstate = null;

function setState(url, title, r) {
/*    if (currentstate == null) {
        var i = window.location.pathname;
        var s = i.split("/");
        var o = s[s.length - 1];
        var u = $(document).find("title").text();
        if (o == "" || o == null || o == undefined) o = "home";
        currentstate = o;
        if (history.pushState && !r) {
            window.history.pushState(o, u, i)
        }
    }*/
    currentstate = url;

    //Ajouter la navigation au pageTracker de Google Analytics
/*    if (pageTracker) {
        pageTracker._trackPageview("/" + url)
    }*/

    if (history.pushState && !r) {
        window.history.pushState(url, title, url)
    }else{
	    window.location.hash  = "!" + url;
    }
}

/* 
	Filtrer les éléments
	Masque les _quoi_ (enfant de _conteneur_) qui ne contiennent pas _string_ dans leur _ou_
*/

function filtrer(conteneur, quoi, ou, string,defaultAll){
	//Ignorer caractères étrange
	var searchTerm = string.replace(/[^a-zA-Z0-9]/,' ');

	//Reset
	$(conteneur+">"+quoi+".hidden").removeClass('hidden');

	if (searchTerm){
		var searchTerm = searchTerm.split(" ");

		//Rechercher
		for(var i=0;i<searchTerm.length;i++){
			if (searchTerm[i]){
				var rg = new RegExp(searchTerm[i],'i');
				$(conteneur+'>'+quoi).each(function(){
					if (ou){
			 			if($.trim($(this).find(ou).html()).search(rg) == -1) {
			 				$(this).addClass('hidden');
						}
					}else{
			 			if($.trim($(this).html()).search(rg) == -1) {
			 				$(this).addClass('hidden');
						}						
					}
				});
			}
		}
	}
}

/*
	radioButton
*/
	function initRadioButtons(){
		//Add attr to radioButton
		$('.radioButton').each(function(){
			$(this).addClass('radioButton_'+$(this).attr('data-control'));
		});

		//Add click event to radioButton
		$('.radioButton').click(function(){
			//Get infos
			var forwhat = $(this).attr('data-control');
			var keepwhat = $(this).attr('data-show');

			//Disable other buttons
			$('.radioButton_'+forwhat).removeClass('active');

			//Enable this button
			$(this).addClass('active');

			//Hide all things
			$('.'+forwhat).hide();

			//Show new things
			$('.'+forwhat+'.'+keepwhat).show();
		});

		//Activate first one
		$('.radioButton.active').click();
	}

/*
	uniqID
*/
function uniqid() {
    var ts=String(new Date().getTime()), i = 0, out = '';
    for(i=0;i<ts.length;i+=2) {        
       out+=Number(ts.substr(i, 2)).toString(36);    
    }
    return ('d'+out);
}

/*
	IncrementReady?
*/

function incrementReady(reset){
	ready = false;

	if (reset)
		nbReady = 0;

	nbReady++;
}

function decrementReady(){
	nbReady--;

	if (nbReady > 0)
		ready = false;
	else
		ready = true;
}