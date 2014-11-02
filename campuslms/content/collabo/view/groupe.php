<!--<header>
	<h1>Administration</h1>
	<h2>Nouveau cours</h2>
</header>-->

<form method="post" action="<?php echo implode('/', $_GET['qs']); ?>">
	<h1><?php echo (($_GET['qs'][3] > 0)?'Modifier':'Nouvelle'); ?></h1>
	<h2>séquence</h2>
	<div class="clr"></div>

	<?php
		switch($data['error']){
			case 'noName':
				echo "<p>Veuillez indiquer un nom.</p>";
			break;
		}
	?>

	<input type="text" class="text" placeholder="nom" name="nom" id="nom" value="<?php echo $data['nom']; ?>"/>
	<input type="hidden" name="save" id="save" value="1"/>

	<input type="submit" class="submit" value="Sauvegarder"/>
	<a class="cancel" href="#">Annuler</a>
	<?php
		if ($_GET['qs'][3] > 0){
			echo '<a class="delete openInMenuBar" href="'.$_GET['data'].'/delete">Supprimer la leçon</a>';
		}
	?>
</form>