<form>
	<h1>Accès à la formation</h1>
	<h2><?php echo $data['nom']; ?></h2>
	<div class="clr"></div>
	<a class="cancel" href="#">Annuler</a>
	<?php
		switch($data['action']){
			case 'saved':
				echo "<p>Votre demande est sauvegardée.</p>";
			break;
			case 'previously':
				echo "<p>Votre demande a bien été reçue et est en cours de traitement.</p>";
			break;
			case 'none':
				echo "<p>Une erreur est survenue.</p>";
			break;
		}
	?>
</form>
