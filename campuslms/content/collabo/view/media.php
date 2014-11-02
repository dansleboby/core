<?php
if ($_POST['doSave'] == "true"){
	switch($_GET['qs'][5]){
		case 'image':
			echo "Votre fichier est en cours de téléversement. Il sera disponible sous peu.";
		break;
		case 'video':
			echo "Votre sélection a été sauvegardée.";
		break;
	}
}else{
	switch($_GET['qs'][5]){
		case 'image':
?>

<form method="post" enctype="multipart/form-data" action="<?php echo $_GET['qs'][0]."/".$_GET['qs'][1]; ?>/lecon/<?php echo $_GET['qs'][3]; ?>/media/image">
	<h1>Leçon</h1>
	<h2>Téléverser une image</h2>
	<div class="clr"></div>

	<label for="fichier" style="background:#FFFFFF;color:#555555;width:700px;height:24px;display:block;">
		<input type="hidden" name="doSave" id="doSave" value="true" />

		<input type="hidden" name="MAX_FILE_SIZE" id="fichiermfs" value="<?php echo return_bytes(ini_get('post_max_size')); ?>" />
		<input type="file" class="text" name="fichier" id="fichier"/>
	</label>

	<input type="submit" class="submit" value="Sauvegarder"/>
	<a class="cancel" href="#">Annuler</a>
</form>
<?php
		break;
		case 'video':

		switch($_GET['qs'][6]){
			case 'embed':
?>
<form method="post" action="<?php echo implode('/',$_GET['qs']); ?>">
	<h1>Leçon</h1>
	<h2>Insérer une vidéo embed</h2>
	<div class="clr"></div>

	<p>Entrez le code fournis par le fournisseur. Notez que les vidées sont affichés dans une zone de 700*402px</p>

	<input type="hidden" name="doSave" id="doSave" value="true" />

	<textarea class="text" placeholder="Code du video" name="url" id="url"></textarea>

	<input type="submit" class="submit" value="Sauvegarder"/>
	<a class="cancel" href="#">Annuler</a>
</form>
<?php
			break;
			case 'heberge':
?>
<form method="post" action="<?php echo implode('/',$_GET['qs']); ?>">
	<h1>Leçon</h1>
	<h2>Insérer une vidéo hébergé (youtube ou vimeo)</h2>
	<div class="clr"></div>

	<p>Entrez l'adresse URL menant au vidéo tel qu'il apparaît dans la barre d'adresse de votre navigateur.</p>

	<p>Par exemple : <ul><li>http://www.youtube.com/watch?v=IAISUDbjXj0</li><li>http://www.vimeo.com/20241459</li></ul></p>

	<input type="hidden" name="doSave" id="doSave" value="true" />

	<input type="text" class="text" placeholder="Lien vers le video" name="url" id="url"/>

	<input type="submit" class="submit" value="Sauvegarder"/>
	<a class="cancel" href="#">Annuler</a>
</form>
<?php
			break;
			default:
?>
<nav style="display:<?php echo (($_GET['qs'][6])?'none':'block'); ?>;">
	<h1>Leçon</h1>

	<a class="openInMenuBar" href="<?php echo $_GET['qs'][0]."/".$_GET['qs'][1]; ?>/lecon/<?php echo $_GET['qs'][3]; ?>/media/video/heberge">Hébergé (youtube ou vimeo)</a>
	<a class="openInMenuBar" href="<?php echo $_GET['qs'][0]."/".$_GET['qs'][1]; ?>/lecon/<?php echo $_GET['qs'][3]; ?>/media/video/embed">Embed</a>
</nav>
<?php
			break;
		}
		break;
	}
}
?>