<?php
	//Load stats
		$mysqli = dbconnect();

		$data['stats'] = array();

	//Nombre de user par type
		$data['stats']['users'] = array();

		$idCie = 0;
		if ($_SESSION['accountType'] == 'cie'){
			$idCie = $_SESSION['id_cie'];
		}

		$niveaux = array('etudiant','enseignant','collaborateur','admin','sadmin');
		foreach($niveaux AS $niv){
			$req = "SELECT count(1) AS nb FROM users WHERE niveau='".$niv."' AND id_cie='".$idCie."'";
			$query = $mysqli->query($req);
			$res = $query->fetch_array(MYSQLI_ASSOC);

			$data['stats']['users'][$niv] = $res['nb'];
		}

	if ($idCie == 0){
	//Nombre de cours
		$req = "SELECT count(1) AS nb FROM cours WHERE etat != 'deleted'";
		$query = $mysqli->query($req);
		$res = $query->fetch_array(MYSQLI_ASSOC);

		$data['stats']['cours'] = $res['nb'];

	//Nombre de leçons
		$req = "SELECT count(1) AS nb FROM cours_lecon WHERE etat != 'deleted'";
		$query = $mysqli->query($req);
		$res = $query->fetch_array(MYSQLI_ASSOC);

		$data['stats']['lecons'] = $res['nb'];

	//Nombre de quiz
		$req = "SELECT count(1) AS nb FROM cours_lecon_quizz WHERE deleted = '0'";
		$query = $mysqli->query($req);
		$res = $query->fetch_array(MYSQLI_ASSOC);

		$data['stats']['quiz'] = $res['nb'];

	//Nombre de devoirs
		$req = "SELECT count(1) AS nb FROM cours_lecon_fichiers WHERE type='devoir' AND deleted = '0'";
		$query = $mysqli->query($req);
		$res = $query->fetch_array(MYSQLI_ASSOC);

		$data['stats']['devoir'] = $res['nb'];

	//Utilisateurs actifs..
		$data['stats']['activity'] = array();
		$deltas = array(1,2,3,7,14,30,60,365);
		foreach($deltas AS $delta){
			$req = "SELECT COUNT(DISTINCT uid) AS nb FROM logs WHERE date >= DATE_SUB(NOW(), INTERVAL ".$delta." DAY)";
			$query = $mysqli->query($req);
			$res = $query->fetch_array(MYSQLI_ASSOC);

			$data['stats']['activity'][$delta] = $res['nb'];
		}
	}
?>