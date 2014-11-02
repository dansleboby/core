<?php
	//Load info about quiz ?

	//Load every users
	$req = "SELECT users.*, groupe_users.etat AS uetat, groupe_users.date AS udate FROM users, groupe_users WHERE groupe_users.id_groupe='".$data['idg']."' AND users.id=groupe_users.id_user AND groupe_users.etat='actif' ORDER BY groupe_users.date DESC";

	$query = $mysqli->query($req);
	$data['users'] = array();

	while ($res = $query->fetch_array(MYSQLI_ASSOC)){
		$req = "SELECT * FROM cours_lecon_quizz_session WHERE id_user='".$res['id']."' AND id_quiz='".$_GET['qs'][3]."' AND id_formation='".$_GET['qs'][1]."' ORDER BY datedebut ASC LIMIT 0,1";

		$query3 = $mysqli->query($req);
		$res3 = $query3->fetch_array(MYSQLI_ASSOC);

		$res3['note'] = $res3['pointage'];
		$res3['sur'] = $res3['valeur'];

		//Load edited note
		$req = "SELECT * FROM notes WHERE id_user='".$res['id']."' AND id_ref='".$res3['id']."' AND ref_type='quiz' ORDER BY date DESC LIMIT 0, 1";
		$query4 = $mysqli->query($req);
		$res4 = $query4->fetch_array(MYSQLI_ASSOC);
		if ($res4['id']){
			$res3['noteEdit'] = $res4['note'];
		}

		$res['note'] = $res3;

		$data['users'][] = $res;
	}
?>