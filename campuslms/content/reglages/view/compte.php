<!--<header>
	<h1>Administration</h1>
	<h2>Nouveau cours</h2>
</header>-->

<form method="post" action="<? echo implode('/', $_GET['qs']); ?>">
	<?php
	if ($data['err']){
		switch($data['err']){
			case 'passLengthError':
				echo "<p>Assurez-vous que votre nouveau mot de passe comporte 8 caractères ou plus.</p>";
			break;
			case 'passConfirmError':
				echo "<p>Assurez-vous que votre confirmation de mot de passe concorde.</p>";
			break;
			case 'emailUsed':
				echo "<p>Cette adresse courriel n'est pas disponible.</p>";
			break;
			case 'emailWeird':
				echo "<p>Cette adresse courriel semble invalide.</p>";
			break;
			case 'wrongPassword':
				echo "<p>Vous avez entré le mauvais mot de passe.</p>";
			break;
			default:
				echo "<p>Une erreur est survenue. Veuillez réessayer.</p>";
			break;
		}
	}else if ($data['updated']){
		echo "<p>Votre profil a été mis à jour.</p>";
	}else{
		switch($_GET['qs'][2] == 'password'){
			case 'password':
			?>
				<h1>Modifier</h1><h2>mot de passe</h1>
				<div class="clr"></div>
				<p>Pour modifier votre mot de passe, veuillez d'abord confirmer votre courriel actuel, puis entrez deux fois votre nouveau mot de passe, pour fins de confirmations.</p>

				<input type="password" class="text" placeholder="Mot de passe actuel" name="pass" id="pass"/>

				<input type="password" class="text" placeholder="Nouveau mot de passe" name="pass1" id="pass1"/>

				<input type="password" class="text" placeholder="Confirmez" name="pass2" id="pass2"/>
			<?php
			break;
			default:
			?>
				<h1>Modifier</h1><h2>profil</h1>
				<div class="clr"></div>
				<p>Afin de confirmer votre identité, veuillez entrer votre mot de passe actuel pour faire vos modifications.</p>

				<input type="text" class="text disabled" placeholder="Code d'identification" name="numero" id="numero" value="<?php echo $data['user']['numero']; ?>" disabled="disabled"/>

				<input type="text" class="text disabled" placeholder="Prénom" name="prenom" id="prenom" value="<?php echo $data['user']['prenom']; ?>" disabled="disabled"/>

				<input type="text" class="text disabled" placeholder="Nom" name="nom" id="nom" value="<?php echo $data['user']['nom']; ?>" disabled="disabled"/>

				<input type="text" class="text" placeholder="courriel" name="email" id="email" value="<?php echo $data['user']['email']; ?>"/>

				<input type="text" class="text" placeholder="Identifiant Skype" name="skype" id="skype" value="<?php echo $data['user']['skype']; ?>"/>
				<p>Pour permettre l'affichage de votre status Skype sur le web, assurez-vous d'activer l'option «Afficher mon état sur le Web» dans les préférences de confidentialité de Skype</p>

				<input type="password" class="text" placeholder="Mot de passe actuel" name="pass" id="pass"/>
				<p>Veuillez entrer votre mot de passe actuel pour confirmer votre identité. Si vous désirez <a href="reglages/compte/password" class="openInMenuBar">modifiez votre mot de passe, cliquez ici</a>.</p>
			<?php
			break;
		}
		echo '<input type="submit" class="submit" value="Sauvegarder"/>';
	}
	?>

	<a class="cancel" href="#">Annuler</a>
</form>