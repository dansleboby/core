<!--<header>
	<h1>Administration</h1>
	<h2>Nouveau cours</h2>
</header>-->

<form method="post" action="<?php echo $_GET['data']; ?>">
	<h1><?php echo (($_GET['4'] > 0)?'Modifier':'Nouveau'); ?> travail pratique</h1>
	<div class="clr"></div>
<!--	<label for="titre">Nom</label>!-->

	<input type="text" class="text" placeholder="nom" name="titre" id="titre" value="<?php echo escape($data['fichier']['nom']); ?>"/>

	<textarea class="specialField" data-specialType="wysiwyg" placeholder="Description du TP" name="description" id="description"><?php echo $data['fichier']['description']; ?></textarea>

	<input type="text" class="text" placeholder="Pondération (valeur au bulletin)" name="valeur" id="valeur" value="<?php echo $data['fichier']['valeur']; ?>"/>


<?php
	if ($_GET['qs'][5] > 0){
		//Afficher fichier courrant & download?
		if ($data['fichier']['etat'] == "recu") {
			?>
			<a class="color_background btn2x" target="_blank" href="<?php echo implode('/',$_GET['qs']); ?>/download">
				<strong>Télécharger</strong>
				<?php echo $data['fichier']['name']." (".humanFilesize($data['fichier']['location']).")"; ?>
			</a>
			<?php
		}else{
			echo "&mdash; Aucun fichier lié à ce TP &mdash;";
		}
	}else{
		echo '<a id="fileUploadLink" href="javascript:$(\'#fileUploadDiv\').show();$(\'#fileUploadLink\').hide();" onclick="$(\'#fileUploadDiv\').show();$(\'#fileUploadLink\').hide();return false;">Joindre un fichier (optionel)</a>';
		echo '<div id="fileUploadDiv" style="display:none;">';
			echo '<label for="fichier" style="background:#FFFFFF;color:#555555;width:700px;height:24px;display:block;">';
				echo '<input type="file" class="text" name="fichier" id="fichier"/>';
			echo '</label>';
		echo '</div>';
	}
?>

	<input type="submit" class="submit" value="Sauvegarder"/>
	<a class="cancel" href="#">Annuler</a>
	<?php
		if ($_GET['qs'][5] > 0){
			$link = $_GET['qs'];
			$link[6] = 'delete';
			$link = implode('/', $link);

			echo '<a class="delete openInMenuBar" href="'.$link.'">Supprimer le TP</a>';
		}
	?>
</form>