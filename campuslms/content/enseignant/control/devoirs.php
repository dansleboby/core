<?php
	//Si on a reçu quelque chose
	if ($_POST['message']){

	}

	$data['fichiers'] = array();

	//Load every lecons!
	$req = "SELECT  users.*, cours_lecon_fichiers_remise.*,cours_lecon_fichiers_remise.id AS idRemise, cours_lecon_fichiers.nom AS nomFichier, cours_lecon_fichiers_remise.date AS dateRecu FROM users, cours_lecon_fichiers_remise, cours_lecon_fichiers, cours_lecon WHERE users.id=cours_lecon_fichiers_remise.id_user AND cours_lecon_fichiers.id=cours_lecon_fichiers_remise.id_fichier AND cours_lecon.id=cours_lecon_fichiers.id_lecon AND cours_lecon.id_cours='".$data['idc']."' ORDER BY cours_lecon_fichiers_remise.date DESC";
	$query = $mysqli->query($req);
	while($res = $query->fetch_array(MYSQLI_ASSOC)){
		$data['fichiers'][] = $res;
	}
?>