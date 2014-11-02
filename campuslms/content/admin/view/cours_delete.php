<!--<header>
	<h1>Administration</h1>
	<h2>Nouveau cours</h2>
</header>-->

<form method="post" action="admin/cours/<?php echo $_GET['qs'][2]; ?>/delete">
	<h1>SUPPRIMER</h1>
	<h2>Cours</h2>
	<div class="clr"></div>

<?php
	if ($data['deleted']){
		echo "<p>Le cours a été supprimé.</p>";
	}else{
		echo "<p>Êtes-vous sûr de vouloir supprimer ce cours? Entrez votre mot de passe pour confirmer.</p>";

		if ($_POST['confirmkey']){
			echo "<p>Une erreur est survenue. Veuillez réessayer.</p>";
		}

		echo '<input type="password" class="text" placeholder="Entrez votre mot de passe pour confirmer." name="confirm" id="confirm">';
		echo '<input type="hidden" name="confirmkey" value="'.$data['confirmkey'].'"/>';

		echo '<input type="submit" class="submit" value="Sauvegarder"/>';
	}
	echo '<a class="cancel" href="#">Fermer</a>';
?>
</form>