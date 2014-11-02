<!--<header>
	<h1>Administration</h1>
	<h2>Nouveau cours</h2>
</header>-->

<form method="post" action="<?php echo implode('/', $_GET['qs']); ?>">
	<h1>Gestion du profil</h1>
	<h2>Enseignant</h2>
	<div class="clr"></div>

	<textarea name="profile" id="profile" placeholder="Profil de l'enseignant"><?php echo $data['profile']; ?></textarea>

	<label for="fichier" style="background:#FFFFFF;color:#555555;width:700px;height:24px;display:block;">
		<input type="file" class="text" name="fichier" id="fichier"/>
	</label>

	<input type="submit" class="submit" value="Sauvegarder"/>
	<a class="cancel" href="#">Annuler</a>
</form>