<!--<header>
	<h1>Administration</h1>
	<h2>Nouveau cours</h2>
</header>-->

<form method="post" action="<?php echo implode('/', $_GET['qs']); ?>">
	<h1><?php echo (($_GET['qs'][3] > 0)?'Modifier':'Nouvelle'); ?></h1>
	<h2>leçon</h2>
	<div class="clr"></div>

	<?php
		switch($data['error']){
			case 'noName':
				echo "<p>Veuillez indiquer un nom.</p>";
			break;
		}
	?>

<!--	<label for="titre">Nom</label>!-->
	<input type="text" class="text" placeholder="nom" name="titre" id="titre" value="<?php echo $data['nom']; ?>"/>

<!--	<label for="description">description</label>!-->
	<textarea placeholder="Description" name="description" id="description"><?php echo $data['description']; ?></textarea>

<!--
	<select name="etat" id="etat" class="color_background">
		<optgroup label="Choisissez un état">
			<option value="inactif"<?php echo (($data['etat'] == "inactif")?' selected':''); ?>">Inactif</option>
			<option value="actif"<?php echo (($data['etat'] == "actif")?' selected':''); ?>>Actif</option>
		</optgroup>
	</selet>
!-->
	<input type="submit" class="submit" value="Sauvegarder"/>
	<a class="cancel" href="#">Annuler</a>
	<?php
		if ($_GET['qs'][3] > 0){
			echo '<a class="delete openInMenuBar" href="'.$_GET['data'].'/delete">Supprimer la leçon</a>';
		}
	?>
</form>