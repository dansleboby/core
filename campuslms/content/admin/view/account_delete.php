<!--<header>
	<h1>Administration</h1>
	<h2>Nouveau cours</h2>
</header>-->

<form method="post" action="<?php echo $_GET['data']; ?>">
	<h1>SUPPRIMER</h1>
	<h2>Utilisateur</h2>
	<div class="clr"></div>

<?php
	if ($data['deleted']){
		echo "<p>L'utilisateur a été supprimé.</p>";
	}else{
		echo "<p>Êtes-vous sûr de vouloir supprimer cet utilisateur?</p>";

		if ($_SESSION['id_cie'] != 0){
			echo "<p>Cet utilisateur sera toujours comptabilisé dans vos quotas s'il a été préalablement assigné à une formation.</p>";
		}

		echo "<p>Entrez votre mot de passe pour confirmer.</p>";

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