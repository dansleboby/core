<!--<header>
	<h1>Administration</h1>
	<h2>Nouveau cours</h2>
</header>-->

<form method="post" action="<?php echo implode('/', $_GET['qs']); ?>">
	<h1><?php echo (($_GET['qs'][2] > 0)?'Modifier':'Nouvelle'); ?></h1>
	<h2>entreprise</h2>
	<div class="clr"></div>
	<?php
		switch($data['error']){
			case 'noName':
				echo "<p>Veuillez entrer un nom.</p>";
			break;
		}
	?>

<!--	<label for="titre">Nom</label>!-->
	<input type="text" class="text" placeholder="Nom de l'entreprise" name="nom" id="nom" value="<?php echo escape($data['nom']); ?>"/>

	<input type="submit" class="submit" value="Sauvegarder"/>
	<a class="cancel" href="#">Annuler</a>
	<?php
	if ($_GET['qs'][2] > 0){
		echo '<a class="delete openInMenuBar" href="admin/entreprise/'.$_GET['qs'][2].'/delete">Supprimer l\'entreprise</a>';
	}else{
		echo '<a id="hiddenContentLink" href="javascript:'."$('#hiddenContentLink').hide();$('#hiddenContent').show();$('#alsoAddUser').val('1');".'" onclick="'."$('#hiddenContentLink').hide();$('#hiddenContent').show();$('#alsoAddUser').val('1');return false;".'">Ajouter un administrateur</a>';

		echo '<input type="hidden" name="alsoAddUser" id="alsoAddUser" value="0"/>';

		echo '<div id="hiddenContent" style="display:none;">';

		if (USE_USERCODE == "true"){
			echo '<input type="text" class="text" placeholder="Code interne" name="usercode" id="usercode" value="'.$data['usercode'].'"/>';
		}

		?>
			<input type="text" class="text" placeholder="Prénom" name="prenom" id="prenom" value="<?php echo $_POST['prenom']; ?>"/>

			<input type="text" class="text" placeholder="Nom" name="userNom" id="userNom" value="<?php echo $_POST['userNom']; ?>"/>

			<input type="text" class="text" placeholder="courriel" name="email" id="email" value="<?php echo $_POST['email']; ?>"/>

			<input type="text" class="text" placeholder="Mot de passe" name="pass" id="pass"/>
		<?php

		echo '</div>';
	}
	?>
</form>