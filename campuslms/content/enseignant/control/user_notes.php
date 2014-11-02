<?php
	//Load info about user ?

	//Save new things
	if ($_POST['action'] == 'saveNotes'){
		foreach($_POST AS $k=>$v){
			if (substr($k, 0, 5) == "note/"){
				$originalk = $k;
				$k = explode("/", $k);
				$lecon_id = $k[1];
				$type = $k[2];
				$elem_id = $k[3];
				$elem_refid = $k[4];

				if (strlen($v) > 0 && $v > 0 && $_POST['prev'.$originalk] != $v){
					switch($type){
						case 'quiz':
							$req = "SELECT cours_lecon_quizz_session.valeur, cours_lecon_quizz_session.id FROM cours_lecon_quizz, cours_lecon_quizz_session WHERE cours_lecon_quizz.id_lecon='".$lecon_id."' AND cours_lecon_quizz_session.id_quiz=cours_lecon_quizz.id AND cours_lecon_quizz_session.id_formation='".$_GET['qs'][1]."' AND cours_lecon_quizz_session.id_quiz='".$elem_id."' AND cours_lecon_quizz_session.id_user='".$_GET['qs'][3]."'";
								$query = $mysqli->query($req);
								$res = $query->fetch_array(MYSQLI_ASSOC);

								if ($res['id'] == $elem_refid){
									if ($v > $res['valeur']){
										$v = $res['valeur'];
									}

									//Add new note
									$req = "INSERT INTO notes SET id_user='".$_GET['qs'][3]."', id_prof='".$_SESSION['uid']."', id_ref='".$elem_refid."', ref_type='".$type."', note='".$v."', date=NOW()";
									$mysqli->query($req);
								}
						break;
						case 'devoir':
							$req = "SELECT cours_lecon_fichiers_remise.id FROM cours_lecon_fichiers, cours_lecon_fichiers_remise WHERE cours_lecon_fichiers.type='devoir' AND cours_lecon_fichiers.id_lecon='".$lecon_id."' AND cours_lecon_fichiers_remise.id_fichier=cours_lecon_fichiers.id AND cours_lecon_fichiers_remise.id_formation='".$_GET['qs'][1]."' AND cours_lecon_fichiers_remise.id_fichier='".$elem_id."' AND cours_lecon_fichiers_remise.id_user='".$_GET['qs'][3]."' ORDER BY id DESC";

//							exit($req);
								$query = $mysqli->query($req);
								$res = $query->fetch_array(MYSQLI_ASSOC);

								if ($res['id'] == $elem_refid){
									if ($v > 100){
										$v = 100;
									}
									//Add new note
									$req = "INSERT INTO notes SET id_user='".$_GET['qs'][3]."', id_prof='".$_SESSION['uid']."', id_ref='".$elem_refid."', ref_type='".$type."', note='".$v."', date=NOW()";

//									exit($req);
									$mysqli->query($req);
								}

						break;
					}
				}
			}
		}
	}

	//Load every lecons!
	$data['notes'] = array();
	$data['noteFinale']['calcule'] = 0;
	$data['noteFinale']['valeur'] = 0;
	$data['noteFinale']['edited'] = 0;

//	$req = "SELECT * FROM cours_lecon WHERE id_cours='".$_GET['qs'][1]."' ORDER BY date ASC";
	$req = "SELECT cours_lecon.* FROM cours_lecon, formations WHERE formations.id='".$_GET['qs'][1]."' AND cours_lecon.id_cours=formations.id_cours ORDER BY date ASC";
	$query = $mysqli->query($req);
	while($res = $query->fetch_array(MYSQLI_ASSOC)){
		$data['notes'][$res['id']] = array();
		$data['notes'][$res['id']]['quiz'] = array();
		$data['notes'][$res['id']]['devoir'] = array();

		//Load every quiz from there!
		$req = "SELECT * FROM cours_lecon_quizz WHERE id_lecon='".$res['id']."' ORDER BY date ASC";
		$query2 = $mysqli->query($req);
		while($res2 = $query2->fetch_array(MYSQLI_ASSOC)){
			//Load every answer from there..
			$req = "SELECT * FROM cours_lecon_quizz_session WHERE id_user='".$_GET['qs'][3]."' AND id_quiz='".$res2['id']."' AND id_formation='".$_GET['qs'][1]."' ORDER BY datedebut ASC LIMIT 0,1";

			$query3 = $mysqli->query($req);
			$res3 = $query3->fetch_array(MYSQLI_ASSOC);

			$res3['nom'] = $res2['nom'];

			//Load edited note
			$req = "SELECT * FROM notes WHERE id_user='".$_GET['qs'][3]."' AND id_ref='".$res3['id']."' AND ref_type='quiz' ORDER BY date DESC LIMIT 0, 1";
			$query4 = $mysqli->query($req);
			$res4 = $query4->fetch_array(MYSQLI_ASSOC);
			if ($res4['id']){
				$res3['noteEdited'] = $res4['note'];
			}

			//Get quiz ID
			$res3['refid'] = $res3['id'];
			$res3['id'] = $res2['id'];


			$data['notes'][$res['id']]['quiz'][] = $res3;

			$data['noteFinale']['edited'] += $res3['noteEdited']?$res3['noteEdited']:$res3['pointage'];
			$data['noteFinale']['calcule'] += $res3['pointage'];
			$data['noteFinale']['valeur'] += $res3['valeur'];
		}

		//Load every devoir from there!
		$req = "SELECT * FROM cours_lecon_fichiers WHERE type='devoir' AND id_lecon='".$res['id']."' ORDER BY date ASC";
		$query2 = $mysqli->query($req);
		while($res2 = $query2->fetch_array(MYSQLI_ASSOC)){
			//Load every devoirs from the user..
			$req = "SELECT * FROM cours_lecon_fichiers_remise WHERE id_user='".$_GET['qs'][3]."' AND id_fichier='".$res2['id']."' AND id_formation='".$_GET['qs'][1]."' ORDER BY date DESC LIMIT 0,1";
			$query3 = $mysqli->query($req);
			$res3 = $query3->fetch_array(MYSQLI_ASSOC);

			$res3['nom'] = $res2['nom'];

			$res3['valeur'] = 100;

			//Load edited note
			$req = "SELECT * FROM notes WHERE id_user='".$_GET['qs'][3]."' AND id_ref='".$res3['id']."' AND ref_type='devoir' ORDER BY date DESC LIMIT 0, 1";
			$query4 = $mysqli->query($req);
			$res4 = $query4->fetch_array(MYSQLI_ASSOC);
			if ($res4['id']){
				$res3['noteEdited'] = $res4['note'];
			}

			//Get devoir ID
			$res3['refid'] = $res3['id'];
			$res3['id'] = $res2['id'];

			$data['notes'][$res['id']]['devoir'][] = $res3;

			$data['noteFinale']['edited'] += $res3['noteEdited']?$res3['noteEdited']:$res3['pointage'];
			$data['noteFinale']['calcule'] += $res3['pointage'];
			$data['noteFinale']['valeur'] += $res3['valeur'];
		}
	}
?>