$(function(){
	enableClick();

	//Update sidebar (useful when user add content without refreshing)
	if ($('body').not('.lecon') && $('body>section>article.leconbtn.newLecon').length){
		var url = document.URL;
		url = url.split("/");

		var idcours = '#sidebarLeconCtn'+url[url.length-1];

		if ($(idcours).length == 1){
			var nb = $('#articleContainer>article').length;
			$(idcours+' span').html(nb+" leçon"+((nb>1)?'s':''));
		}else{
			console.log('Cours '+idcours+' not found');
		}
	}

	$('#reorderButton').click(function(){
		if ($('#reorderButton').attr('data-organizing') == "true"){
			var data = {groupes:[],lecons:[]};

			var groupe = 0;

			$('#articleContainer>*').each(function(){
				if ($(this).attr('data-groupe')){
					groupe = $(this).attr('data-groupe');
					data.groupes.push(groupe);
				}else{
					data.lecons.push([$(this).attr('data-lecon'),groupe]);
				}
			});

			$('#articleContainer').animate({'opacity':0.2});

			loadAjax(curUrl+"/ordre",{data:data},'update');
//			loadInMenuBar($(this).find('a').attr('href'));
		}else{
			$('body.collabo>section>article.leconbtn').off('click');

			$("#articleContainer").disableSelection();
			$("#articleContainer").sortable({scroll:false});
			$('#reorderButton').html('<strong>Sauvegarder</strong> les modifications').attr('data-organizing','true')
		}
	});

	$('h3.title').on('click',function(){
		groupe = $(this).attr('data-groupe');
		loadInMenuBar(curUrl+"/groupe/"+groupe);
	})
});

function enableClick(){
	$('body.collabo>section article.leconbtn').bind('click',function(e){
		if (!$(this).hasClass('disabled')){
			if ($(this).find('a').hasClass('openInMenuBar')){
				loadInMenuBar($(this).find('a').attr('href'));
			}else{
				loadAjax($(this).find('a').attr('href'));
			}
		}

		e.preventDefault();
		e.stopPropagation();
	});
}