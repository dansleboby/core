<?php
	$mysqli = dbconnect();

	function loadLecon($leconid, $coursid){
		GLOBAL $mysqli, $formationid, $formationetat;

		$req = "SELECT * FROM cours_lecon WHERE id_cours='".$coursid."' AND id='".$leconid."' AND etat != 'deleted' ORDER BY date ASC";

		$query = $mysqli->query($req);

		$res = $query->fetch_array(MYSQLI_ASSOC);

//		$res['req'] = $req;

		$max = 0;
		$note = 0;
		$notfound = false;
		$res['nb'] = 0;

		//Charger les préalables de la leçon
		$res['prealables'] = array();

		if ($formationetat == 'actif'){
			$req2 = "SELECT * FROM cours_lecon_prealable WHERE id_lecon='".$res['id']."'";
			$query2 = $mysqli->query($req2);
			while($res2 = $query2->fetch_array(MYSQLI_ASSOC)){
				$res['prealables'][] = $res2;
			}
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

	//			$orderby = "datefin ASC"; //pour le résultat le plus vieux
	//			$orderby = "datefin DESC"; //pour le résultat le plus récent
			$orderby = "pointage DESC"; //Pour le meilleur résultat

			$req3 = "SELECT cours_lecon_quizz_session.pointage, cours_lecon_quizz_session.valeur AS sur, cours_lecon_quizz.valeur FROM cours_lecon_quizz_session, cours_lecon_quizz WHERE cours_lecon_quizz_session.id_quiz='".$res2['id']."' AND cours_lecon_quizz_session.id_user='".$_SESSION['user_id']."' AND cours_lecon_quizz_session.id_formation='".$formationid."' AND cours_lecon_quizz.id=cours_lecon_quizz_session.id_quiz ORDER BY ".$orderby." LIMIT 0,1";

//			$res['req3'] = $req3;

			$query3 = $mysqli->query($req3);
			$res3 = $query3->fetch_array(MYSQLI_ASSOC);

			$req4 = "SELECT * FROM notes WHERE id_user='".$_SESSION['user_id']."' AND ref_type='quiz' AND id_ref='".$res3['id']."' ORDER BY id DESC LIMIT 0,1";
			$query4 = $mysqli->query($req4);
			$res4 = $query4->fetch_array(MYSQLI_ASSOC);

			if ($res4['note']){
				$res3['pointage'] = $res4['note'];
//				$res3['valeur'] = $res4['valeur'];
			}

			if ($res2['id']){
				$note += $res3['pointage']/$res3['sur']*$res3['valeur'];
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

		return $res;
	}

	$data = loadLecon($_GET['qs'][3], $coursid);

	$data['todo'] = array();

	$lecons = array();

	$disableit = true;
	if ($locked){
		$_param['view'] = "lecon_incomplete";
		$data['todo'][] = "locked";
	}else if (count($data['prealables']) > 0){
		$disableit = false;

		foreach($data['prealables'] AS $prealable){
			$lecon = loadLecon($prealable['id_prealable'], $coursid);
			$lecons[] = $lecon;

//			print_r($lecon);

			$temparray = array();
			$temparray['complet'] = false;

			switch ($prealable['cond']){
				case 'read':
					$temparray['action'] = "Consultation de la leçon «<em>".$lecon['nom']."</em>»";
					if ($lecon['lu'] == false){
						$_param['view'] = "lecon_incomplete";
					}else{
						$temparray['complet'] = true;
					}
				break;
				default:
					$temparray['action'] = "Obtention d'une note de ";

					switch($prealable['cond']){
						case '1star':
							$minnote = NOTE_1STAR;
							$temparray['action'] .= "1 étoile ";
						break;
						case '2star':
							$minnote = NOTE_2STAR;
							$temparray['action'] .= "2 étoile ";
						break;
						case '3star':
							$minnote = NOTE_3STAR;
							$temparray['action'] .= "3 étoile ";
						break;
						case '4star':
							$minnote = NOTE_4STAR;
							$temparray['action'] .= "4 étoile ";
						break;
						case '5star':
							$minnote = NOTE_5STAR;
							$temparray['action'] .= "5 étoile ";
						break;
						case '10':
							$minnote = 10;
							$temparray['action'] .= "½ étoile ";
						break;
						case '20':
							$minnote = 20;
							$temparray['action'] .= "1 étoile ";
						break;
						case '30':
							$minnote = 30;
							$temparray['action'] .= "1½ étoiles ";
						break;
						case '40':
							$minnote = 40;
							$temparray['action'] .= "2 étoiles ";
						break;
						case '50':
							$minnote = 50;
							$temparray['action'] .= "2½ étoiles ";
						break;
						case '60':
							$minnote = 60;
							$temparray['action'] .= "3 étoiles ";
						break;
						case '70':
							$minnote = 70;
							$temparray['action'] .= "3½ étoiles ";
						break;
						case '80':
							$minnote = 80;
							$temparray['action'] .= "4 étoiles ";
						break;
						case '90':
							$minnote = 90;
							$temparray['action'] .= "4½ étoiles ";
						break;
						case '100':
							$minnote = 100;
							$temparray['action'] .= "5 étoiles ";
						break;
					}

					$temparray['action'] .= "dans la leçon «<em>".$lecon['nom']."</em>»";//" (".$lecon['noteFinale']." VS ".$minnote.")";

					if ($lecon['noteFinale'] < $minnote){
						$_param['view'] = "lecon_incomplete";
					}else{
						$temparray['complet'] = true;
					}
				break;
			}

			$data['todo'][] = $temparray;				
		}
	}

	if ($_GET['qs'][4] == 'liens') {
		require(dirname(__FILE__)."/liens.php");
	}else if ($_GET['qs'][4] == 'fichiers') {
		require(dirname(__FILE__)."/fichiers.php");
	}else if ($_GET['qs'][4] == 'media' && $_SESSION['user_level'] == 'collaborateur') {
		require(dirname(__FILE__)."/media.php");
	}else if ($_GET['qs'][4] == 'devoirs') {
		require(dirname(__FILE__)."/devoirs.php");
	}else if ($_GET['qs'][4] == 'tp') {
		require(dirname(__FILE__)."/tp.php");
	}else if ($_GET['qs'][4] == 'quiz') {
		require(dirname(__FILE__)."/quiz.php");
	}else{
		$req = "SELECT * FROM cours_lecon WHERE id='".$_GET['qs'][3]."' AND id_cours='".$coursid."'";
		$query = $mysqli->query($req);
		$data['lecon'] = $query->fetch_array(MYSQLI_ASSOC);

		saveLog("openedLecon", $_GET['qs'][3], $_GET['qs'][3]);

		//Charger les informations du cours
		$req = "SELECT * FROM cours WHERE id='".$data['lecon']['id_cours']."'";
		$query = $mysqli->query($req);
		$data['cours'] = $query->fetch_array(MYSQLI_ASSOC);

		//Charger les informations du collaborateur
		$req = "SELECT * FROM users WHERE id='".$data['cours']['id_user']."'";
		$query = $mysqli->query($req);
		$data['cours']['collabrateur'] = $query->fetch_array(MYSQLI_ASSOC);

		//Charger les fichiers
		$data['fichiers'] = array();
		$req = "SELECT * FROM cours_lecon_fichiers WHERE id_lecon='".$data['lecon']['id']."' AND (type='fichier' OR type='lien') AND deleted != '1' ORDER BY ordre ASC, date ASC";
		$query = $mysqli->query($req);
		while($res = $query->fetch_array(MYSQLI_ASSOC)){
			$data['fichiers'][] = $res;
		}

		//Charger les quizz
		$data['quiz'] = array();
		$req = "SELECT * FROM cours_lecon_quizz WHERE id_lecon='".$data['lecon']['id']."' AND deleted != '1' ORDER BY ordre ASC, date ASC";
		$query = $mysqli->query($req);
		while($res = $query->fetch_array(MYSQLI_ASSOC)){
			$req2 = "SELECT id FROM cours_lecon_quizz_session WHERE id_user='".$_SESSION['user_id']."' AND id_quiz='".$res['id']."'";

			$query2 = $mysqli->query($req2);
			$res2 = $query2->fetch_array(MYSQLI_ASSOC);

		    $res['done'] = $res2['id'];

			$data['quiz'][] = $res;
		}

		//Charger les exercices
		$data['tp'] = array();
		$req = "SELECT * FROM cours_lecon_fichiers WHERE id_lecon='".$data['lecon']['id']."' AND type='tp' AND deleted != '1' ORDER BY ordre ASC, date ASC";
		$query = $mysqli->query($req);
		while($res = $query->fetch_array(MYSQLI_ASSOC)){
			$req = "SELECT * FROM cours_lecon_fichiers_remise WHERE id_fichier='".$res['id']."' AND id_user='".$_SESSION['user_id']."' ORDER BY id DESC LIMIT 0,1";
			$query2 = $mysqli->query($req);
			$res2 = $query2->fetch_array(MYSQLI_ASSOC);

			if ($res2['id']){
				$res['remis'] = true;

				if ($res2['note'] != ''){
					$res['corrige'] = true;
				}
			}

			$data['tp'][] = $res;
		}

		//Charger les exercices
		$data['devoirs'] = array();
		$req = "SELECT * FROM cours_lecon_fichiers WHERE id_lecon='".$data['lecon']['id']."' AND type='devoir' AND deleted != '1' ORDER BY ordre ASC, date ASC";
		$query = $mysqli->query($req);
		while($res = $query->fetch_array(MYSQLI_ASSOC)){
			$req = "SELECT * FROM cours_lecon_fichiers_remise WHERE id_fichier='".$res['id']."' AND id_user='".$_SESSION['user_id']."' ORDER BY id DESC LIMIT 0,1";
			$query2 = $mysqli->query($req);
			$res2 = $query2->fetch_array(MYSQLI_ASSOC);

			if ($res2['id']){
				$res['remis'] = true;

				if ($res2['note'] != ''){
					$res['corrige'] = true;
				}
			}

			$data['devoirs'][] = $res;
		}

		//Charger les informations de l'enseignant
		$data['enseignant'] = array();
		if ($_SESSION['accountType'] == 'campus'){
			$req = "SELECT users.* FROM users, formations, groupe_users WHERE groupe_users.id_user='".$_SESSION['user_id']."' AND formations.id_groupe=groupe_users.id_groupe AND formations.id_cours='".$coursid."' AND users.id=formations.id_user";
			$query = $mysqli->query($req);
			$data['enseignant'] = $query->fetch_array(MYSQLI_ASSOC);
		}

		if ($allowCreation){
			$_req['js'][] = "campuslms/content/".$_param['module']."/js/lecon_edit.js";		
		}else{
			//Vérifier l'état de la leçon 
		}
	}
?>