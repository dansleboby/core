<!--<header>
	<h1>Administration</h1>
	<h2>Groupes de l'utilisateur</h2>
</header>-->

<form method="post" action="<?php echo implode('/',$_GET['qs']); ?>" class="openInMenuBar">
	<h1>Ajouter un utilisateur au groupe</h1>
	<div class="clr"></div>

<?php
	$salt = uniqid();
	echo '<input type="hidden" name="salt" id="salt" value="'.$salt.'"/>';
?>

	<select name="user" id="user" class="specialField" data-specialType="selectHelper" data-placeholder='Pour ajouter un utilisateur, sélectionnez-le ici.'>
<?php
	if (!($_GET['qs']['4'] > 0)){
		echo '<option class="noSelect" value="0">Pour ajouter un utilisateur, sélectionnez-le ici.</option>';
	}
	$mysqli = dbconnect();
	$req = "SELECT * FROM users WHERE id_cie='".$_SESSION['id_cie']."' AND niveau='etudiant' ORDER BY nom ASC";
	$query = $mysqli->query($req);
	$i = 0;
	while($res = $query->fetch_array(MYSQLI_ASSOC)){
		if (!isset($data[$res['id']])){
			$i++;
			echo '<option value="'.createValidateKey($res['id'], $salt).'"'.(($data['id_user'] == $res['id'])?' selected="selected"':'').'>'.$res['nom'].', '.$res['prenom'].' ('.$res['email'].')</option>';
		}
	}
	$mysqli->close();

	if ($i == 0){
		echo '<option class="noSelect" value="0" selected="selected">Tous les utilisateurs sont déjà présents dans ce groupe</option>';
	}
?>
	</select>
<?php

/*	<input type="date" class="text specialField" placeholder="Date de fin (YYYY-MM-DD) - laissez vide si sans fin" name="datefin" id="datefin" value="<?php echo $data['datefin']; ?> data-specialType="dateHelper">*/
?>
	<input type="submit" class="submit" value="Sauvegarder"/>
	<a class="cancel" href="#">Annuler</a>
</form>