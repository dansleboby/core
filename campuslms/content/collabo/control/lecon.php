<?php
	$mysqli = dbconnect();

	if (!$allowCreation){
		//Check note of previous lecon
		$req = "SELECT * FROM cours_lecon WHERE id < '".$_GET['qs'][3]."' AND id_cours='".$_GET['qs'][1]."' ORDER BY id DESC LIMIT 0,1";
		$query = $mysqli->query($req);
		$res = $query->fetch_array(MYSQLI_ASSOC);

		//If we got an ID, it's not the first lecon!
		if ($res['id']){
			$max = 0;
			$note = 0;

			//Get minimum from cours setting
			$minNote = 60;

			//Load each quiz in there
			$req2 = "SELECT id FROM cours_lecon_quizz WHERE id_lecon='".$res['id']."'";
			$query2 = $mysqli->query($req2);
			while($res2 = $query2->fetch_array(MYSQLI_ASSOC)){
				$res['nb']++;

/**
 THIS CODE IS ALSO ON index.php
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
				}
			}

			$res['max'] = $max;
			$res['note'] = $note;

			if ($max > 0) {
				$prevNote = $note/$max*100;

				if ($prevNote < $minNote){
					$_param['view'] = "lecon_incomplete";
				}
			}
		}
	}

	if ($_GET['qs'][4] == 'liens') {
		require(dirname(__FILE__)."/liens.php");
	}else if ($_GET['qs'][4] == 'ordre') {
		require(dirname(__FILE__)."/lecon_ordre.php");
	}else if ($_GET['qs'][4] == 'fichiers') {
		require(dirname(__FILE__)."/fichiers.php");
	}else if ($_GET['qs'][4] == 'prealables') {
		require(dirname(__FILE__)."/prealables.php");
	}else if ($_GET['qs'][4] == 'media' && $_SESSION['user_level'] == 'collaborateur') {
		require(dirname(__FILE__)."/media.php");
	}else if ($_GET['qs'][4] == 'devoirs') {
		require(dirname(__FILE__)."/devoirs.php");
	}else if ($_GET['qs'][4] == 'tp') {
		require(dirname(__FILE__)."/tp.php");
	}else if ($_GET['qs'][4] == 'quiz') {
		require(dirname(__FILE__)."/quiz.php");
	}else if (($_GET['qs'][4] == 'edit' || $_GET['qs'][3] == 'nouveau') && $allowCreation){
		if ($_GET['qs'][5] == 'delete'){
			$keys = array();
			for($i=0;$i<=2;$i++){
				$key = date('YmdHi',strtotime('-'.$i.' min'));
				$keys[] = md5(INNER_SALT.$_GET['data'].$key.$_SESSION['user_id']);
			}

			$data['confirmkey'] = $keys[0];

			if (in_array($_POST['confirmkey'],$keys) && getpassword($_SESSION['user_id']) == getpassword($_SESSION['user_id'], $_POST['confirm'])){
				$mysqli = dbconnect();
				$req = "UPDATE cours_lecon SET etat='deleted' WHERE id='".$_GET['qs'][3]."'";
				$mysqli->query($req);

				$data['deleted'] = 'true';
//				saveLog('delLecon',$_GET['qs'][2]);

				if ($_POST['ajax']){
				//Confirm text
					$data['confirmText'] = "La leçon a été supprimée.";
					$data['goTo'] = 'collabo/'.$_GET['qs'][1];
//					$data['refreshContent'] = true;
					exit(json_encode($data));
				}				
			}else{
//				saveLog('tryDelLecon',$_GET['qs'][2]);
			}

			$_param['view'] .= "_delete";	
		}else{
			if (isset($_POST['titre'])){
				if (strlen($_POST['titre']) > 0){
					//Save in DB

					if ($_GET['qs'][4] == "edit"){
						$req = "UPDATE cours_lecon SET nom='".$_POST['titre']."', description='".$_POST['description']."' WHERE id='".$_GET['qs'][3]."'";
					}else{
						$req = "INSERT INTO cours_lecon SET id_cours='".$_GET['qs'][1]."', nom='".$_POST['titre']."', description='".$_POST['description']."', date=NOW()";
					}
					$mysqli->query($req);

		//			$_GET['qs'][3] = $mysqli->insert_db();

					$_param['view'] .= "_saved";

					if ($_POST['ajax']){
					//Confirm text
						$data['confirmText'] = "La leçon a été sauvegardée.";
	/*					$data['updateDOM'] = array(array(
						    'target'=>'#sidebarLeconCtn'.$_GET['qs'][1]." span",
						    'action'=>'update',
						    'value'=>'Count this'
						    )
						);*/
						$data['refreshContent'] = true;
						exit(json_encode($data));
					}
				}else{
					$data['titre'] = $_POST['titre'];
					$_param['view'] .= "_new";
					$data['error'] = 'noName';
				}
			}else{
				$req = "SELECT * FROM cours_lecon WHERE id='".$_GET['qs'][3]."'";
				$query = $mysqli->query($req);
				$data = $query->fetch_array(MYSQLI_ASSOC);

				$_param['view'] .= "_new";
			}
		}
	}else{
		$req = "SELECT * FROM cours_lecon WHERE id='".$_GET['qs'][3]."' AND id_cours='".$_GET['qs'][1]."' ORDER BY ordre ASC, id ASC";
		$query = $mysqli->query($req);
		$data['lecon'] = $query->fetch_array(MYSQLI_ASSOC);

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

		//Charger les tp
		$data['tp'] = array();
		$req = "SELECT * FROM cours_lecon_fichiers WHERE id_lecon='".$data['lecon']['id']."' AND type='tp' AND deleted != '1' ORDER BY ordre ASC, date ASC";
		$query = $mysqli->query($req);
		while($res = $query->fetch_array(MYSQLI_ASSOC)){
			$data['tp'][] = $res;
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
		$data['devoirs'] = array();
		$req = "SELECT * FROM cours_lecon_fichiers WHERE id_lecon='".$data['lecon']['id']."' AND type='devoir' AND deleted != '1' ORDER BY ordre ASC, date ASC";
		$query = $mysqli->query($req);
		while($res = $query->fetch_array(MYSQLI_ASSOC)){
			$data['devoirs'][] = $res;
		}

		//Charger les informations de l'enseignant
		$data['enseignant'] = array();
		$req = "SELECT users.* FROM users, formations, groupe_users WHERE groupe_users.id_user='".$_SESSION['user_id']."' AND formations.id_groupe=groupe_users.id_groupe AND formations.id_cours='".$_GET['qs'][1]."' AND users.id=formations.id_user";
		$query = $mysqli->query($req);
		$data['enseignant'] = $query->fetch_array(MYSQLI_ASSOC);

		if ($allowCreation){
			$_req['js'][] = "campuslms/content/".$_param['module']."/js/lecon_edit.js";		
		}else{
			//Vérifier l'état de la leçon 
		}
	}
?>