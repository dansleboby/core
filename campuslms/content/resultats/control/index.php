<?php
	$mysqli = dbconnect();

	$data['cours'] = array();

	if ($_SESSION['accountType'] == 'campus'){
		//Charger les cours de l'utilisateur
		$req = "SELECT formations.id AS fid, cours.id AS idc, groupes.id AS idg, groupes.nom AS nomgroupe, cours.nom AS nomcours, cours.description, users.prenom, users.nom FROM groupe_users, groupes, formations, cours LEFT JOIN users ON cours.id_user=users.id WHERE groupe_users.etat='actif' AND groupe_users.id_user='".$_SESSION['user_id']."' AND groupes.id=groupe_users.id_groupe AND formations.id_groupe=groupes.id AND cours.id=formations.id_cours AND cours.etat!='deleted' AND formations.etat='actif'";
	}else{
		//Charger les cours de l'utilisateur d'entreprise
		$req = "SELECT '0' AS fid, cours.id AS idc, cie_licenses.id AS idg, cours.nom AS nomgroupe, cours.nom AS nomcours, cours.description, users.prenom, users.nom FROM cie_licenses, cie_license_users, cours LEFT JOIN users ON cours.id_user=users.id WHERE cie_license_users.id_user='".$_SESSION['user_id']."' AND cie_licenses.id=cie_license_users.id_license AND cours.id=cie_licenses.id_cours AND cours.etat!='deleted'";
	}
	$oQuery = $mysqli->query($req);
	while($cours = $oQuery->fetch_array(MYSQLI_ASSOC)){
		//Load cours & quiz
		$toLoad = array();
		$toLoad['quiz'] = array();
		$toLoad['devoir'] = array();
		$toLoad['tp'] = array();

		$cours['lecons'] = array();

		//Charger les leçons du cours
		$req = "SELECT cours_lecon.* FROM cours_lecon WHERE cours_lecon.id_cours='".$cours['idc']."' ORDER BY date ASC";

		$query = $mysqli->query($req);
		while($res = $query->fetch_array(MYSQLI_ASSOC)){
			$temp = array();
			$temp['quiz'] = array();
			$temp['devoir'] = array();
			$temp['tp'] = array();
			$temp['nom'] = $res['nom'];

			//Charger les quiz de la leçon
			$req = "SELECT * FROM cours_lecon_quizz WHERE id_lecon='".$res['id']."' AND deleted=0 ORDER BY date ASC";
			$query2 = $mysqli->query($req);
			while($res2 = $query2->fetch_array(MYSQLI_ASSOC)){
				$temp['quiz'][$res2['id']] = $res2;
				$toLoad['quiz'][$res2['id']] = array('id'=>$res2['id'], 'nom'=>$res2['nom'],'valeur'=>$res2['valeur']);
			}

			//Charger les devoirs de la leçon
			$req = "SELECT * FROM cours_lecon_fichiers WHERE deleted=0 AND type='devoir' AND id_lecon='".$res['id']."' ORDER BY date ASC";
			$query2 = $mysqli->query($req);
			while($res2 = $query2->fetch_array(MYSQLI_ASSOC)){
				$temp['devoir'][$res2['id']] = $res2;
				$toLoad['devoir'][$res2['id']] = array('id'=>$res2['id'], 'nom'=>$res2['nom'], 'valeur'=>$res2['valeur']);
			}

			//Charger les tp de la leçon
			$req = "SELECT * FROM cours_lecon_fichiers WHERE deleted=0 AND type='tp' AND id_lecon='".$res['id']."' ORDER BY date ASC";
			$query2 = $mysqli->query($req);
			while($res2 = $query2->fetch_array(MYSQLI_ASSOC)){
				$temp['tp'][$res2['id']] = $res2;
				$toLoad['tp'][$res2['id']] = array('id'=>$res2['id'], 'nom'=>$res2['nom'], 'valeur'=>$res2['valeur']);
			}


			$cours['lecons'][$res['id']] = $temp;
		}

		//Charger tous les utilisateurs du même groupe / groupe d'entreprise
		if ($_SESSION['accountType'] == 'campus'){
			$req = "SELECT users.*, groupe_users.etat AS uetat, groupe_users.date AS udate FROM users, groupe_users WHERE groupe_users.id_groupe='".$cours['idg']."' AND users.id=groupe_users.id_user AND groupe_users.etat='actif' ORDER BY groupe_users.date DESC";
		}else{
			$req = "SELECT users.* FROM users, cie_license_users WHERE cie_license_users.id_license='".$cours['idg']."' AND users.id=cie_license_users.id_user";
		}

		$query = $mysqli->query($req);

		while ($res = $query->fetch_array(MYSQLI_ASSOC)){

			//Charger la note du devoir
			foreach($toLoad['devoir'] AS $k=>$v){
				$v = $v['id'];

				$req = "SELECT cours_lecon_fichiers.id_lecon, cours_lecon_fichiers_remise.* FROM cours_lecon_fichiers_remise,cours_lecon_fichiers WHERE cours_lecon_fichiers_remise.id_user='".$res['id']."' AND cours_lecon_fichiers_remise.id_fichier='".$v."' AND cours_lecon_fichiers.id=cours_lecon_fichiers_remise.id_fichier AND cours_lecon_fichiers_remise.id_formation='".$cours['fid']."' ORDER BY cours_lecon_fichiers_remise.date DESC LIMIT 0,1";

				$query3 = $mysqli->query($req);
				$res3 = $query3->fetch_array(MYSQLI_ASSOC);

				if ($res3['id']){
					//Charger la note modifiée, si elle existe.
					$req = "SELECT * FROM notes WHERE id_user='".$res['id']."' AND id_ref='".$res3['id']."' AND ref_type='devoir' ORDER BY date DESC LIMIT 0, 1";
					$query4 = $mysqli->query($req);
					$res4 = $query4->fetch_array(MYSQLI_ASSOC);
					if ($res4['id']){
						$res3['note'] = $res4['note'];
					}

					$tempnote = $res3['note']/100*$toLoad['devoir'][$k]['valeur'];

					$toLoad['devoir'][$k]['moyenne'] += $tempnote;
					$toLoad['devoir'][$k]['moyenne_nb']++;
//					$cours['moyenne'] += $tempnote;
//					$cours['moyenne_valeur'] += $toLoad['devoir'][$k]['valeur'];
//					$cours['moyenne_nb']++;

					if ($res3['id_user'] == $_SESSION['user_id']){
						$toLoad['devoir'][$k]['note'] = $tempnote;
						$cours['note'] += $tempnote;
						$cours['note_valeur'] += $toLoad['devoir'][$k]['valeur'];
					}
				}
			}

			//Charger la note du TP
			foreach($toLoad['tp'] AS $k=>$v){
				$v = $v['id'];

				$req = "SELECT cours_lecon_fichiers.id_lecon, cours_lecon_fichiers_remise.* FROM cours_lecon_fichiers_remise,cours_lecon_fichiers WHERE cours_lecon_fichiers_remise.id_user='".$res['id']."' AND cours_lecon_fichiers_remise.id_fichier='".$v."' AND cours_lecon_fichiers.id=cours_lecon_fichiers_remise.id_fichier AND cours_lecon_fichiers_remise.id_formation='".$cours['fid']."' ORDER BY cours_lecon_fichiers_remise.date DESC LIMIT 0,1";

				$query3 = $mysqli->query($req);
				$res3 = $query3->fetch_array(MYSQLI_ASSOC);

				if ($res3['id']){
					//Charger la note modifiée, si elle existe.
					$req = "SELECT * FROM notes WHERE id_user='".$res['id']."' AND id_ref='".$res3['id']."' AND ref_type='tp' ORDER BY date DESC LIMIT 0, 1";
					$query4 = $mysqli->query($req);
					$res4 = $query4->fetch_array(MYSQLI_ASSOC);
					if ($res4['id']){
						$res3['note'] = $res4['note'];
					}

					$tempnote = $res3['note']/100*$toLoad['tp'][$k]['valeur'];

					$toLoad['tp'][$k]['moyenne'] += $tempnote;
					$toLoad['tp'][$k]['moyenne_nb']++;
//					$cours['moyenne'] += $tempnote;
//					$cours['moyenne_valeur'] += $toLoad['tp'][$k]['valeur'];
//					$cours['moyenne_nb']++;

					if ($res3['id_user'] == $_SESSION['user_id']){
						$toLoad['tp'][$k]['note'] = $tempnote;
						$cours['note'] += $tempnote;
						$cours['note_valeur'] += $toLoad['tp'][$k]['valeur'];
					}
				}
			}


			//Quiz?
			foreach($toLoad['quiz'] AS $k=>$v){
				$v = $v['id'];

				//Load every answer from there..
				$req = "SELECT cours_lecon_quizz.id_lecon, cours_lecon_quizz_session.* FROM cours_lecon_quizz, cours_lecon_quizz_session WHERE cours_lecon_quizz.id='".$v."' AND cours_lecon_quizz_session.id_user='".$res['id']."' AND cours_lecon_quizz_session.id_quiz='".$v."' AND cours_lecon_quizz_session.id_formation='".$cours['fid']."' ORDER BY cours_lecon_quizz_session.pointage DESC LIMIT 0,1";

				$query3 = $mysqli->query($req);
				$res3 = $query3->fetch_array(MYSQLI_ASSOC);

				if ($res3['id']){
					//Load edited note
					$req = "SELECT * FROM notes WHERE id_user='".$res['id']."' AND id_ref='".$res3['id']."' AND ref_type='quiz' ORDER BY date DESC LIMIT 0, 1";
					$query4 = $mysqli->query($req);
					$res4 = $query4->fetch_array(MYSQLI_ASSOC);
					if ($res4['id']){
						$res3['pointage'] = $res4['note'];
					}

					$tempnote = $res3['pointage']/$res3['valeur']*$toLoad['quiz'][$k]['valeur'];

					//Moyenne
					$toLoad['quiz'][$k]['moyenne'] += $tempnote;
					$toLoad['quiz'][$k]['moyenne_nb']++;
//					$cours['moyenne'] += $tempnote;
//					$cours['moyenne_valeur'] += $toLoad['quiz'][$k]['valeur'];
//					$cours['moyenne_nb']++;

					//User note
					if ($res3['id_user'] == $_SESSION['user_id']){
						$toLoad['quiz'][$k]['note'] = $tempnote;
						$cours['note'] += $tempnote;
						$cours['note_valeur'] += $toLoad['quiz'][$k]['valeur'];
					}
				}
			}
		}

		$cours['data'] = $toLoad;

		$data['cours'][] = $cours;
	}
?>