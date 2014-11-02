<!--<header>
	<h1>Administration</h1>
	<h2>Nouveau groupe</h2>
</header>-->

<form method="post" action="<?php echo $_GET['data']; ?>">
	<h1>SUPPRIMER</h1>
	<h2>travail pratique</h2>
	<div class="clr"></div>

<?php
	if ($data['deleted']){
		echo "<p>Le TP a été supprimée.</p>";
	}else{
		echo "<p>Êtes-vous sûr de vouloir supprimer ce TP? Entrez votre mot de passe pour confirmer.</p>";

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