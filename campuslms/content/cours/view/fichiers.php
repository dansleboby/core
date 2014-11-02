<!--<header>
	<h1>Administration</h1>
	<h2>Nouveau cours</h2>
</header>-->

<form>
	<h1>Fichier</h1>
	<h2><?php echo $data['fichier']['nom']; ?></h2>
	<div class="clr"></div>

	<p><?php echo nl2br4txt($data['fichier']['description']); ?></p>

	<a class="color_background btn2x" target="_blank" href="<?php echo implode('/',$_GET['qs']); ?>/download">
		<strong>Télécharger le fichier</strong>
		<?php echo $data['fichier']['name']." (".humanFilesize($data['fichier']['location']).")"; ?>
	</a>

	<a class="cancel" href="#">Annuler</a>
</form>