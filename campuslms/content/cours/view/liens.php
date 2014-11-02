<!--<header>
	<h1>Administration</h1>
	<h2>Nouveau cours</h2>
</header>-->

<form>
	<h1>Lien</h1>
	<h2><?php echo $data['fichier']['nom']; ?></h2>
	<div class="clr"></div>

	<p>Ce lien s'ouvrira dans une nouvelle fenêtre.</p>

	<p>Le lien a été ajouté le <?php echo date('Y-m-d',strtotime($data['fichier']['date'])); ?>. Il peut avoir été modifié depuis sa création. Veuillez signaler à votre enseignant tout problème avec ce lien.</p>

	<a class="color_background btn2x" target="_blank" href="<?php echo $data['fichier']['description']; ?>" onclick="hideMenuBar();">
		<strong>Ouvrir dans une nouvelle fenêtre</strong>
		<?php echo $data['fichier']['description']; ?>
	</a>

	<a class="cancel" href="#">Annuler</a>
</form>