$(function(){
	$('div.textarea').addClass('hoverFx');
	$('div.textarea').bind('click',function(e){
		var name = $(this).attr('data-name');
		var value = $(this).html();

		showEditPopup('textarea', name, value);

		e.stopPropagation();
		e.preventDefault();
	});
});

function showEditPopup(type, name, value, placeholder, displayName){
	if (!displayName)
		displayName = name;

	if (!placeholder)
		placeholder = "Veuillez entrer votre contenu ici";

	var content = '<form method="post" action="'+window.location.href+'/save">	<h1>Modifier</h1><h2'+displayName+'</h2>	<div class="clr"></div>';

	if (1){
		content = content+'<textarea placeholder="'+placeholder+'" name="'+name+'" id="thedata">'+value+'</textarea>';
	}

	content = content+'</select><input type="submit" class="submit" value="Sauvegarder">	<a class="cancel" href="#">Annuler</a></form>';

	showMenuBar(content, type+''+name, true);
}