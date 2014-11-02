<!--<header>
	<h1>Administration</h1>
	<h2>Groupes de l'utilisateur</h2>
</header>-->

<form method="post" action="<?php echo implode('/',$_GET['qs']); ?>" class="openInMenuBar">
	<h1>Assigner un cours au groupe</h1>
	<div class="clr"></div>

<?php
	$salt = uniqid();
	echo '<input type="hidden" name="salt" id="salt" value="'.$salt.'"/>';
?>

	<select name="groupe" id="groupe" class="specialField" data-specialType="selectHelper" data-placeholder='Pour ajouter un groupe, sélectionnez-le ici.'>
<?php
	if (!($_GET['qs']['4'] > 0)){
		echo '<option class="noSelect" value="0">Pour ajouter un groupe, sélectionnez-le ici.</option>';
	}
	$mysqli = dbconnect();
	$req = "SELECT groupes.* FROM groupes WHERE groupes.etat!='inactif' ORDER BY nom ASC";
	$query = $mysqli->query($req);
	$i = 0;
	while($res = $query->fetch_array(MYSQLI_ASSOC)){
		if (!isset($data[$res['id']])){
			$i++;
			echo '<option value="'.createValidateKey($res['id'], $salt).'"'.(($data['id_groupe'] == $res['id'])?' selected="selected"':'').'>'.$res['nom'].'</option>';
		}
	}
	$mysqli->close();

	if ($i == 0){
		echo '<option class="noSelect" value="0" selected="selected">Cette formation est déjà liée à l\'ensemble des groupes</option>';
	}
?>
	</select>

	<select name="prof" id="prof" class="specialField" data-specialType="selectHelper" data-placeholder='Sélectionnez un enseignant ici.'>
<?php
	if (!($_GET['qs']['4'] > 0)){
		echo '<option class="noSelect" value="0">Sélectionnez un enseignant ici.</option>';
	}
	echo '<option value="0">Aucun</option>';
	$mysqli = dbconnect();
	$req = "SELECT * FROM users WHERE niveau='enseignant' ORDER BY nom ASC";
	$query = $mysqli->query($req);
	while($res = $query->fetch_array(MYSQLI_ASSOC)){
		if (!isset($data[$res['id']]))
			echo '<option value="'.$res['id'].'"'.(($data['id_user'] == $res['id'])?' selected="selected"':'').'>'.strtoupper($res['nom']).', '.$res['prenom'].'</option>';
	}
	$mysqli->close();
?>
	</select>

	<select name="etat" id="etat">
		<option value="actif"<?php echo (($data['etat'] == 'actif')?' selected="selected"':''); ?>>Accès complet à la formation</option>
		<option value="partiel"<?php echo (($data['etat'] == 'partiel')?' selected="selected"':''); ?>>Accès aux capsules uniquement (sans quiz/devoir/TP)</option>
		<option value="inactif"<?php echo (($data['etat'] == 'inactif')?' selected="selected"':''); ?>>Aucun accès à la formation (publicité)</option>
	</select>


<!--	<input type="date" class="text specialField" placeholder="Date de début (YYYY-MM-DD) - laissez vide si immédiat" name="datedebut" id="datedebut" value="<?php echo $data['datedebut']; ?>" data-specialType="dateHelper">

	<input type="date" class="text specialField" placeholder="Date de fin (YYYY-MM-DD) - laissez vide si sans fin" name="datefin" id="datefin" value="<?php echo $data['datefin']; ?>" data-specialType="dateHelper">!-->

	<input type="submit" class="submit" value="Sauvegarder"/>
	<a class="cancel" href="#">Annuler</a>
</form>