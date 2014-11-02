<!--<header>
	<h1>Administration</h1>
	<h2>Nouveau cours</h2>
</header>-->

<form method="post" id="formSaveFichier" data-onsubmit="validateAddFile" action="<?php echo $_GET['data']; ?>">
	<h1><?php echo (($_GET['qs']['5'] > 0)?'Modifier':'Nouveau'); ?> fichier</h1>
	<div class="clr"></div>
<!--	<label for="titre">Nom</label>!-->
<?php
/*	<input type="text" class="text" placeholder="Type de fichier (par défaut : Fichier)" name="titre" id="titre" value="<?php echo $res['titre']; ?>"/>*/
?>

	<input type="text" class="text" placeholder="nom" name="nom" id="nom" value="<?php echo escape($res['nom']); ?>"/>

	<textarea class="specialField" data-specialType="wysiwyg" placeholder="Description du fichier" name="description" id="description"><?php echo $res['description']; ?></textarea>

<?php
	if ($_GET['qs'][5] > 0){
		//Afficher fichier courrant & download?
	}else{
		echo '<label for="fichier" style="background:#FFFFFF;color:#555555;width:700px;height:24px;display:block;">';
			echo '<input type="file" class="text" name="fichier" id="fichier"/>';
		echo '</label>';
	}
?>

	<input type="submit" class="submit" value="Sauvegarder"/>
	<a class="cancel" href="#">Annuler</a>
	<?php
		if ($_GET['qs'][5] > 0){
			$link = $_GET['qs'];
			$link[6] = 'delete';
			$link = implode('/', $link);

			echo '<a class="delete openInMenuBar" href="'.$link.'">Supprimer le fichier</a>';
		}
	?>
</form>