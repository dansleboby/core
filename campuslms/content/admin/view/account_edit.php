<!--<header>
	<h1>Administration</h1>
	<h2>Nouveau cours</h2>
</header>-->

<form method="post" action="<?php echo implode('/', $_GET['qs']); ?>" data-onsubmit="validateNewUser">
	<h1><?php echo (($_GET['qs'][1] == 'account')?(($_GET['qs'][2] > 0)?'Modifier':'Nouvel'):(($_GET['qs'][4] > 0)?'Modifier':'Nouvel')); ?></h1>
	<h2>utilisateur</h2>
	<div class="clr"></div>
	<?php
		echo '<input type="hidden" id="insertmode" value="'.(($_GET['qs'][1] == 'account')?(($_GET['qs'][2] > 0)?'edit':'new'):(($_GET['qs'][4] > 0)?'edit':'new')).'"/>';

		switch($data['error']){
			case 'usercodeExists':
				echo "<p>Ce code d'utilisateur est déjà utilisé.</p>";
			break;
			case 'emailExists':
				echo "<p>Ce courriel est déjà utilisé.</p>";
			break;
			case 'userNotFound':
				echo "<p>Cet utilisateur est introuvable.</p>";
			break;
			case 'incomplete':
				echo "<p>Veuillez entrer un courriel et le mot de passe.</p>";			
			break;
		}

		if (USE_USERCODE == "true"){
			echo '<input type="text" class="text" placeholder="Code interne" name="usercode" id="usercode" value="'.escape($data['usercode']).'"/>';
		}
	?>


<!--	<label for="titre">Nom</label>!-->
	<input type="text" class="text" placeholder="Prénom" name="prenom" id="prenom" value="<?php echo escape($data['prenom']); ?>"/>

<!--	<label for="titre">Nom</label>!-->
	<input type="text" class="text" placeholder="Nom" name="nom" id="nom" value="<?php echo escape($data['nom']); ?>"/>

	<input type="text" class="text" placeholder="courriel" name="email" id="email" value="<?php echo escape($data['email']); ?>"/>

	<input type="text" class="text" placeholder="Mot de passe<?php echo (($_GET['qs'][2] > 0)?' (Laissez vide pour ne pas modifier)':''); ?>" name="pass" id="pass"/>

	<select name="niveau" id="niveau" class="">
		<optgroup label="Choisissez un niveau">
			<?php
				if (!$data['niveau'])
					$data['niveau'] = 'etudiant';

				if ($_GET['qs'][1] == 'account'){
					$niveaux = array('disabled'=>'Bloqué',
				                 'etudiant'=>'Étudiant',
				                 'enseignant'=>'Enseignant',
				                 'collaborateur'=>'Collaborateur',
				                 'admin'=>'Administrateur',
				                 'sadmin'=>'Technicien');
				}else{
					$niveaux = array('disabled'=>'Bloqué',
				                 'etudiant'=>'Étudiant',
				                 'admin'=>'Administrateur');
				}

				foreach ($niveaux as $k => $v) {
					echo '<option value="'.$k.'"'.(($k == $data['niveau'])?' selected':'').'>'.$v.'</option>';
				}
			?>
		</optgroup>
	</selet>

	<input type="submit" class="submit" value="Sauvegarder"/>
	<a class="cancel" href="#">Annuler</a>
	<?php
	if (($_GET['qs'][1] == 'account' && $_GET['qs'][2] > 0) || ($_GET['qs'][1] != 'account' && $_GET['qs'][4] > 0)){
		echo '<a class="delete openInMenuBar" href="'.implode('/', $_GET['qs']).'/delete">Supprimer le compte</a>';
	}else if($_GET['qs'][1] == 'account'){
		echo '<a class="openInMenuBar" href="admin/account/import">Importer une liste d\'utilisateurs</a>';
	}
	?>
</form>