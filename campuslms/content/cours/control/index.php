<?php
	if ($accessGranted || $allowCreation){
		if ($_GET['qs'][2] == 'lecon'){
			$_param['view'] = "lecon";
			require(dirname(__FILE__)."/lecon.php");
		}else if ($_GET['qs'][2] == 'ponderation'){
			$_param['view'] = "ponderation";
			require(dirname(__FILE__)."/ponderation.php");
		}else if ($_GET['qs'][2] == 'acces' && $data['locked']){
			$_param['view'] = "acces";
			require(dirname(__FILE__)."/acces.php");
		}else{
			//Load  cours lecons!
//			$mysqli = dbconnect();

			saveLog("openedCours", $coursid, $formationid);

			$req = "SELECT * FROM cours WHERE id='".$coursid."'";
			$query = $mysqli->query($req);
			$data['cours'] = $query->fetch_array(MYSQLI_ASSOC);

			$data['req1'] = $req;

			$data['lecons'] = array();

			//Load messages
			$data['messages'] = array();
			if ($_SESSION['accountType'] == 'campus'){
				$req = "SELECT users.prenom, users.nom, groupes_message.* FROM groupes_message, users WHERE groupes_message.id_groupe='".$groupeid."' AND (groupes_message.date_debut IS NULL OR groupes_message.date_debut <= NOW()) AND (groupes_message.date_fin >= NOW() OR groupes_message.date_fin IS NULL) AND users.id=groupes_message.id_user ORDER BY groupes_message.date_debut DESC";

				$query = $mysqli->query($req);
				while($res = $query->fetch_array(MYSQLI_ASSOC)){
					$data['messages'][] = $res;
				}

				//Load infos about teacher
				$req = "SELECT * FROM users WHERE id='".$data['enseignant']."'";
				$query = $mysqli->query($req);
				$data['enseignant'] = $query->fetch_array(MYSQLI_ASSOC);
			}else{
				$data['enseignant'] = array();
			}

			//Load groupes
			$req = "SELECT * FROM cours_groupe WHERE id_cours='".$coursid."' ORDER BY ordre ASC, id ASC";
			$query = $mysqli->query($req);

			$data['groupes'] = array();
			while($res = $query->fetch_array(MYSQLI_ASSOC)){
				$res['cours'] = array();
				$data['groupes'][$res['id']] = $res;
			}
			$data['sansgroupe'] = array();

			//Load leçons
//			$req = "SELECT * FROM cours_lecon WHERE id_cours='".$coursid."' AND etat != 'deleted' ORDER BY date ASC";
			$req = "SELECT * FROM cours_lecon WHERE id_cours='".$coursid."' AND etat != 'deleted' ORDER BY ordre ASC, date ASC";
			$query = $mysqli->query($req);

			$data['req2'] = $req;

			while($res = $query->fetch_array(MYSQLI_ASSOC)){
				$max = 0;
				$note = 0;
				$notfound = false;
				$res['nb'] = 0;

				//Charger les préalables de la leçon
				$res['prealables'] = array();

				$req2 = "SELECT * FROM cours_lecon_prealable WHERE id_lecon='".$res['id']."'";
				$query2 = $mysqli->query($req2);
				while($res2 = $query2->fetch_array(MYSQLI_ASSOC)){
					$res['prealables'][] = $res2;
				}

				//Vérifier si la leçon a été vue
				$req2 = "SELECT id FROM logs WHERE texte='openedLecon' AND ref='".$res['id']."' AND uid='".$_SESSION['user_id']."' LIMIT 0,1";
				$query2 = $mysqli->query($req2);
				$res2 = $query2->fetch_array(MYSQLI_ASSOC);

				if ($res2['id'] > 0){
					$res['lu'] = true;					
				}else{
					$res['lu'] = false;
				}

				//Load each quiz in there
				$req2 = "SELECT id FROM cours_lecon_quizz WHERE id_lecon='".$res['id']."'";
				$query2 = $mysqli->query($req2);
				while($res2 = $query2->fetch_array(MYSQLI_ASSOC)){
					$res['nb']++;

					//Get note type from cours setting...

//					$orderby = "datefin ASC"; //pour le résultat le plus vieux
//					$orderby = "datefin DESC"; //pour le résultat le plus récent
					$orderby = "pointage DESC"; //Pour le meilleur résultat

					$req3 = "SELECT * FROM cours_lecon_quizz_session WHERE id_quiz='".$res2['id']."' AND id_user='".$_SESSION['user_id']."' AND id_formation='".$formationid."' ORDER BY ".$orderby." LIMIT 0,1";

//					print_r($req3);

					$query3 = $mysqli->query($req3);
					$res3 = $query3->fetch_array(MYSQLI_ASSOC);

					$req4 = "SELECT * FROM notes WHERE id_user='".$_SESSION['user_id']."' AND ref_type='quiz' AND id_ref='".$res3['id']."' ORDER BY id DESC LIMIT 0,1";

//					exit($req4);

					$query4 = $mysqli->query($req4);
					$res4 = $query4->fetch_array(MYSQLI_ASSOC);

					if ($res4['note']){
						$res3['pointage'] = $res4['note'];
//						$res3['valeur'] = $res4['valeur'];
					}

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

//				$res['disabled'] = true;

//				$data['lecons'][] = $res;


				if ($data['groupes'][$res['id_groupe']]){
					$data['groupes'][$res['id_groupe']]['cours'][] = $res['id'];
				}else{
					$data['sansgroupe'][] = $res['id'];
				}

				$data['lecons'][$res['id']] = $res;
			}

			//Vérifier les préalables de chaque leçons
			if ($formationetat == 'actif'){
				foreach($data['lecons'] AS $k=>$res){
					$disableit = false;

					foreach($res['prealables'] AS $prealable){
						switch ($prealable['cond']){
							case 'read':
								if ($data['lecons'][$prealable['id_prealable']]['lu'] == false){
									$disableit = true;
									break 2;
								}
							break;
							default:
								if ($prealable['cond'] == "1star"){
									$minnote = NOTE_1STAR;
								}else if ($prealable['cond'] == "2star"){
									$minnote = NOTE_2STAR;
								}else if ($prealable['cond'] == "3star"){
									$minnote = NOTE_3STAR;
								}else if ($prealable['cond'] == "4star"){
									$minnote = NOTE_4STAR;
								}else if ($prealable['cond'] == "5star"){
									$minnote = NOTE_5STAR;
								}else if ($prealable['cond'] == "10"){
									$minnote = 10;
								}else if ($prealable['cond'] == "20"){
									$minnote = 20;
								}else if ($prealable['cond'] == "30"){
									$minnote = 30;
								}else if ($prealable['cond'] == "40"){
									$minnote = 40;
								}else if ($prealable['cond'] == "50"){
									$minnote = 50;
								}else if ($prealable['cond'] == "60"){
									$minnote = 60;
								}else if ($prealable['cond'] == "70"){
									$minnote = 70;
								}else if ($prealable['cond'] == "80"){
									$minnote = 80;
								}else if ($prealable['cond'] == "90"){
									$minnote = 90;
								}else if ($prealable['cond'] == "100"){
									$minnote = 100;
								}

								if ($data['lecons'][$prealable['id_prealable']]['noteFinale'] < $minnote){
									$disableit = true;
									break 2;
								}
							break;
						}					
					}
					if ($disableit){
						$data['lecons'][$k]['disabled'] = true;					
					}
				}
			}
		}
	}else{
		//Error time
	}

?>