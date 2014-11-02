<?php
	$loadCours = true;
	if ($_SESSION['user_level'] == 'collaborateur') {
		$allowCreation = true;
//		$_param['view'] .= "_edit";
//		require(dirname(__FILE__)."/index_edit.php");
	}else if ($_GET['qs'][1] == 'nouveau' && $_SESSION['user_level'] == 'collaborateur') {
		$_param['view'] .= "_new";
//		require(dirname(__FILE__)."/index_edit.php");
		$loadCours = false;

	}

	if ($loadCours){
		//Make sure the user can access this...

		$mysqli = dbconnect();
		$req = "SELECT formations.id, formations.message, formations.id_user, formations.id_groupe FROM cours, formations, groupe_users WHERE groupe_users.id_user='".$_SESSION['user_id']."' AND formations.id_groupe=groupe_users.id_groupe AND cours.id=formations.id_cours AND cours.id='".$_GET['qs'][1]."'";
//		if ($_SESSION['user_level'] == "etudiant"){
			$req .= " AND (formations.datedebut IS NULL OR formations.datedebut >= NOW()) AND (formations.datefin IS NULL OR formations.datefin <= NOW())";
//		}

		$query = $mysqli->query($req);
		$res = $query->fetch_array(MYSQLI_ASSOC);

		$groupeid = $res['id_groupe'];
		$formationid = $res['id'];
		$data['message'] = $res['message'];
		$data['enseignant'] = $res['id_user'];

		if ($res['id']){
			$accessGranted = true;
		}

		$accessGranted = true;

		if ($accessGranted || $allowCreation){
			if ($_GET['qs'][2] == 'lecon'){
				$_param['view'] = "lecon";
				require(dirname(__FILE__)."/lecon.php");
			}else if ($_GET['qs'][2] == 'ponderation'){
				$_param['view'] = "ponderation";
				require(dirname(__FILE__)."/ponderation.php");
			}else if ($_GET['qs'][2] == 'ordre'){
				$_param['view'] = "ordre";
				require(dirname(__FILE__)."/ordre.php");
			}else if ($_GET['qs'][2] == 'groupe'){
				$_param['view'] = "groupe";
				require(dirname(__FILE__)."/groupe.php");
			}else{
				//Load  cours lecons!
	//			$mysqli = dbconnect();

				$req = "SELECT * FROM cours WHERE id='".$_GET['qs'][1]."'";
				$query = $mysqli->query($req);
				$data['cours'] = $query->fetch_array(MYSQLI_ASSOC);

				$data['req1'] = $req;

				$data['lecons'] = array();

				//Load messages
				$data['messages'] = array();
				$req = "SELECT users.prenom, users.nom, groupes_message.* FROM groupes_message, users WHERE groupes_message.id_groupe='".$groupeid."' AND (groupes_message.date_debut IS NULL OR groupes_message.date_debut <= NOW()) AND (groupes_message.date_fin >= NOW() OR groupes_message.date_fin IS NULL) AND users.id=groupes_message.id_user ORDER BY groupes_message.date_debut DESC";

				$query = $mysqli->query($req);
				while($res = $query->fetch_array(MYSQLI_ASSOC)){
					$data['messages'][] = $res;
				}

				//Load infos about teacher
				$req = "SELECT * FROM users WHERE id='".$data['enseignant']."'";
				$query = $mysqli->query($req);
				$data['enseignant'] = $query->fetch_array(MYSQLI_ASSOC);

				//Load groupes
				$req = "SELECT * FROM cours_groupe WHERE id_cours='".$_GET['qs'][1]."' ORDER BY ordre ASC, id ASC";
				$query = $mysqli->query($req);

				$data['groupes'] = array();
				while($res = $query->fetch_array(MYSQLI_ASSOC)){
					$res['cours'] = array();
					$data['groupes'][$res['id']] = $res;
				}
				$data['sansgroupe'] = array();

				//Load leçons
				$req = "SELECT * FROM cours_lecon WHERE id_cours='".$_GET['qs'][1]."' AND etat != 'deleted' ORDER BY ordre ASC, date ASC";
				$query = $mysqli->query($req);

				$data['req2'] = $req;

				$disablenext = false;

				//Get minimum from cours setting
				$minNote = 60;

				while($res = $query->fetch_array(MYSQLI_ASSOC)){
					//Charger toutes les notes de la leçon - Si au moins un complet, noter le nombre d'étoile ici!
/*					$req2 = "SELECT notes.* FROM notes, cours_lecon_fichiers, cours_lecon_quizz WHERE ((cours_lecon_fichiers.id_lecon='".$res['id']."' AND cours_lecon_fichiers.type='devoir' AND notes.ref_type='devoir' AND notes.id_ref=cours_lecon_fichiers.id) OR (cours_lecon_quizz.id_lecon='".$res['id']."' AND notes.ref_type='quiz' AND notes.id_ref=cours_lecon_quizz.id)) AND notes.id_user='".$_SESSION['user_id']."'";

					$query2 = $mysqli->query($req);

					$max = 0;
					$note = 0;

					while($res2 = $query2->fetch_array(MYSQLI_ASSOC)){
						$note += $res2['note'];
						$max += $res2['max'];
					}*/

					$max = 0;
					$note = 0;
					$notfound = false;
					$res['nb'] = 0;

					//Load each quiz in there
					$req2 = "SELECT id FROM cours_lecon_quizz WHERE id_lecon='".$res['id']."'";
					$query2 = $mysqli->query($req2);
					while($res2 = $query2->fetch_array(MYSQLI_ASSOC)){
						$res['nb']++;

/**
 THIS CODE IS ALSO ON lecon.php
**/


						//Get note type from cours setting...

//						$orderby = "datefin ASC"; //pour le résultat le plus vieux
//						$orderby = "datefin DESC"; //pour le résultat le plus récent
						$orderby = "pointage DESC"; //Pour le meilleur résultat

						$req3 = "SELECT * FROM cours_lecon_quizz_session WHERE id_quiz='".$res2['id']."' AND id_user='".$_SESSION['user_id']."' AND id_formation='".$formationid."' ORDER BY ".$orderby." LIMIT 0,1";
						$query3 = $mysqli->query($req3);
						$res3 = $query3->fetch_array(MYSQLI_ASSOC);

						if ($res2['id']){
							$note += $res3['pointage'];
							$max += $res3['valeur'];
						}else{
							$notfound = true;
						}
					}

					$res['max'] = $max;
					$res['note'] = $note;
					$res['notfound'] = $notfound;

					if ($notfound == false) {
						$res['noteFinale'] = $note/$max*100;
					}

/*					if ($disablenext){
						$res['disabled'] = true;
					}else{
						//Check if there is a requirement...
						if ($res['noteFinale'] < $minNote){
							$disablenext = true;
						}
					}*/

					if ($data['groupes'][$res['id_groupe']]){
						$data['groupes'][$res['id_groupe']]['cours'][] = $res['id'];
					}else{
						$data['sansgroupe'][] = $res['id'];
					}

					$data['lecons'][$res['id']] = $res;
				}
			}
		}else{
			//Error time
		}
	}

?>