<!--<header>
	<h1>Administration</h1>
	<h2>Nouveau cours</h2>
</header>-->

<form method="post" action="<? echo implode('/', $_GET['qs']); ?>">
	<?php
	if ($data['err']){
		echo "<p>Une erreur est survenue. Veuillez réessayer.</p>";
	}else if ($data['updated']){
		echo "<p>Le message a été mis à jour.</p>";
	}else{
		?>
		<h1>Modifier</h1><h2>message</h1>
		<div class="clr"></div>
		<p>Veuillez indiquer le message à transmettre au groupe ci-dessous. Ce message sera affiché au haut de la formation<!-- et une notification sera envoyée aux étudiants!-->.</p>

		<textarea name="message" id="message"><?php echo $data['message']; ?></textarea>

		<input type="submit" class="submit" value="Sauvegarder"/>
		<?php
	}
	?>

	<input type="date" class="text" placeholder="Date de début (YYYY-MM-DD) - laissez vide si immédiat" name="date_debut" id="date_debut" value="<?php echo $res['date_debut']; ?>">

	<input type="date" class="text" placeholder="Date de début (YYYY-MM-DD) - laissez vide si aucun" name="date_fin" id="date_fin" value="<?php echo $res['date_fin']; ?>">

	<a class="cancel" href="#">Annuler</a>

	<?php
		echo '<a class="delete openInMenuBar" href="enseignant/'.$_GET['qs'][1].'/message/delete">Supprimer le message</a>';
	?>
</form>