<!--<header>
	<h1>Administration</h1>
	<h2>Nouveau cours</h2>
</header>-->

<form method="post" id="formSaveLink" data-onsubmit="validateAddLien" action="<?php echo $_GET['data']; ?>">
	<h1><?php echo (($_GET['qs'][5] > 0)?'Modifier':'Nouveau'); ?> lien</h1>
	<div class="clr"></div>
<!--	<label for="titre">Nom</label>!-->

	<input type="text" class="text" placeholder="nom" name="titre" id="titre" value="<?php echo escape($data['nom']); ?>"/>

	<input type="text" class="text" placeholder="URL complet (http://www....)" name="url" id="url" value="<?php echo $data['description']; ?>"/>

	<input type="submit" class="submit" value="Sauvegarder"/>
	<a class="cancel" href="#">Annuler</a>
	<?php
		if ($_GET['qs'][5] > 0){
			$link = $_GET['qs'];
			$link[6] = 'delete';
			$link = implode('/', $link);

			echo '<a class="delete openInMenuBar" href="'.$link.'">Supprimer le lien</a>';
		}
	?>
</form>