<?php
	//Basic security
	if ($_SESSION['user_level'] != 'admin' && $_SESSION['user_level'] != 'sadmin' && $_SESSION['accountType'] == 'campus'){
		$_param['module'] = "error";
		$_param['view'] = "403";
		return;
	}

	$mysqli = dbconnect();

	if ($_GET['qs'][2] > 0){
		$req = "UPDATE demandes SET etat='".$_GET['qs'][3]."' WHERE id='".$_GET['qs'][2]."'";
		$mysqli->query($req);
	}

	//Nouveau, En cours, résolu

	$req = "SELECT demandes.*, cours.nom AS nomCours, users.usercode, users.email, users.prenom AS userPrenom, users.nom AS userNom FROM demandes, cours, users WHERE cours.id=demandes.id_cours AND users.id=demandes.id_user ORDER BY etat ASC, date DESC";
	$query = $mysqli->query($req);
	$data = array();
	while($res = $query->fetch_array(MYSQLI_ASSOC)){
		$data[] = $res;
	}

	$mysqli->close();
?>