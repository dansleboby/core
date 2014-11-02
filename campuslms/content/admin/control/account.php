<?php
	//Basic security
	$canEdit = true;
	if (!in_array($_SESSION['user_level'], array('admin','sadmin'))){
//	if ($_SESSION['user_level'] != 'admin'){
		$forAdmin = array();
		$forAdmin[2] = array('nouveau','new');

		foreach ($_GET['qs'] AS $k=>$qs) {
			if (is_array($forAdmin[$k])){
				if (in_array($qs, $forAdmin[$k])) {
					$_param['module'] = "error";
					$_param['view'] = "403";
					return;
				}
			}
		}
		$canEdit = false;
	}

	switch($_GET['qs'][2]){
		case 'import':
		case 'multiple':
			require(dirname(__FILE__)."/account_import.php");
		break;
		case '':
		case 'cie':
		case null:
			//On est sur le «home» - Charger les comptes.
			$mysqli = dbconnect();
			if ($_SESSION['accountType'] == 'cie'){
				$req = "SELECT * FROM users WHERE id_cie='".$_SESSION['id_cie']."' AND niveau != 'deleted' ORDER BY nom ASC";
			}else{
				if ($_GET['qs'][2] == "cie"){
					$req = "SELECT * FROM users WHERE id_cie!=0 AND niveau != 'deleted' ORDER BY nom ASC";
				}else{
					$req = "SELECT * FROM users WHERE id_cie=0 AND niveau != 'deleted' ORDER BY nom ASC";
				}
			}

			$query = $mysqli->query($req);
			$data = array();
			while($res = $query->fetch_array(MYSQLI_ASSOC)){
				$req2 = "SELECT count(groupes.id) AS nb FROM groupes, groupe_users WHERE groupe_users.id_user='".$res['id']."' AND groupes.id=groupe_users.id_groupe AND groupe_users.etat='actif' ORDER BY groupe_users.date DESC";
				$query2 = $mysqli->query($req2);
				$res2 = $query2->fetch_array(MYSQLI_ASSOC);

				$res['nbGroupe'] = $res2['nb'];

				$req2 = "SELECT count(users_lecons.id) AS nb FROM users_lecons WHERE id_user='".$res['id']."'";
				$query2 = $mysqli->query($req2);
				$res2 = $query2->fetch_array(MYSQLI_ASSOC);

				$res['nbCours'] = $res2['nb'];


				$data[] = $res;
			}

			$mysqli->close();
		break;
		case 'nouveau':
			//On a demandé de créer un nouvel utilisateur...

			if (isset($_POST['email']) && isset($_POST['pass'])) {
				$mysqli = dbconnect();

				$saveit = true;

				if (strlen(trim($_POST['email'])) > 0){
					//S'assurer que le courriel & user ID n'est pas déjà assigné à un compte...
					$req = "SELECT count(1) AS nb FROM users WHERE id_cie=0 AND email='".trim(strtolower($_POST['email']))."' AND niveau != 'deleted'";
					$query = $mysqli->query($req);
					$res = $query->fetch_array(MYSQLI_ASSOC);
				}else{
					$res = array();
					$res['nb'] = 0;
				}

				//Add posted data to $data (to autofill the fields)
				$data = array_merge($data, $_POST);

				if ($res['nb'] > 0){
					$data['error'] = 'emailExists';
					$saveit = false;
				}else if (USE_USERCODE == true && strlen($_POST['usercode']) > 0){
					$req = "SELECT count(1) AS nb FROM users WHERE id_cie=0 AND usercode='".trim(strtolower($_POST['usercode']))."' AND niveau != 'deleted'";
					$query = $mysqli->query($req);
					$res = $query->fetch_array(MYSQLI_ASSOC);
					if ($res['nb'] > 0){
						$data['error'] = 'usercodeExists';
						$saveit = false;
					}
				}

				if ($saveit && strlen($_POST['niveau']) > 1 && (strlen($_POST['usercode']) > 0 || strlen($_POST['email']) > 0)) {
					$req = "INSERT INTO users SET usercode='".trim(strtolower($_POST['usercode']))."', prenom='".$_POST['prenom']."', nom='".$_POST['nom']."', email='".trim(strtolower($_POST['email']))."', pass='temp', niveau='".$_POST['niveau']."', date=NOW()";
					$mysqli->query($req);

					$_GET['qs'][2] = $mysqli->insert_id;
					$req = "UPDATE users SET pass='".getpassword($_GET['qs'][2],$_POST['pass'])."' WHERE id='".$_GET['qs'][2]."'";
					$mysqli->query($req);

					saveLog('newUser',$_GET['qs'][2]);

					if ($_POST['ajax']){
					//Confirm text
						$data['confirmText'] = "L'utilisateur a été sauvegardé.";
						$data['refreshContent'] = true;
						exit(json_encode($data));
					}
				}else{
					//Une erreur est survenue.. Indiquer à la vue..
				}
				$mysqli->close();
			}else{
				$data['error'] = 'incomplete';
				$_param['view'] .= "_edit";
				break;
			}
		//Le break est dans le else. On ne break pas si on vient de faire une insertion.
		default:
			switch($_GET['qs'][3]){
				case 'groupes':
					$_param['view'] .= "_groupes";

					$mysqli = dbconnect();

					//Lier à un nouveau groupe
//					if ($_POST['groupe'] > 0){
					if (isset($_POST['groupe']) && $_POST['groupe'] != '0') {
						$_POST['groupe'] = checkValidateKey($_POST['groupe']);

						$datefin = null;
						if ($_POST['datefin']){
							$datefin = strtotime($_POST['datefin']);
							$datefin = date('Y-m-d H:i:s',$datefin);
						}

						if ($_GET['qs'][4] > 0){
//							saveLog('user2groupUpdated',$_GET['qs'][2],$_POST['groupe']);
							$req = "UPDATE groupe_users SET id_groupe='".$_POST['groupe']."', datefin='".$datefin."' WHERE id='".$_GET['qs'][4]."'";

							$mysqli->query($req);

							if ($_POST['ajax']){
							//Confirm text
								$data['confirmText'] = "Mise à jour effectuée.";
								$data['refreshContent'] = true;
								exit(json_encode($data));
							}
						}else{
							saveLog('user2group',$_GET['qs'][2],$_POST['groupe']);
							$req = "INSERT INTO groupe_users SET id_groupe='".$_POST['groupe']."', id_user='".$_GET['qs'][2]."', etat='actif', date=NOW(), datefin='".$datefin."'";

							$mysqli->query($req);

							if ($_POST['ajax']){
							//Confirm text
								$data['confirmText'] = "Sauvegarde effectuée.";
								$data['refreshContent'] = true;
								exit(json_encode($data));
							}
						}


//						$_GET['qs'][4] = $mysqli->insert_id;
						$_GET['qs'][4] = "";
					}

					if ($_GET['qs'][4] == 'nouveau'){
						$_param['view'] .= "_edit";
					}else{
						if ($_GET['qs'][4] > 0){
							//Asked for specific ID
							if ($_GET['qs'][5] == "delete") {
								//On veut le supprimer
								$req = "UPDATE groupe_users SET etat='inactif' WHERE id_user='".$_GET['qs'][2]."' AND id_groupe='".$_GET['qs'][5]."' AND etat='actif'";
								$mysqli->query($req);

								$_GET['qs'][4] = "";

								if ($_POST['ajax']){
								//Confirm text
									$data['confirmText'] = "Mise à jour effectuée.";
									$data['refreshContent'] = true;
									exit(json_encode($data));
								}

							}else{
								$_param['view'] .= "_edit";

								//On veut l'afficher
								$req = "SELECT * FROM groupe_users WHERE id='".$_GET['qs'][4]."'";
								$query = $mysqli->query($req);
								$data = $query->fetch_array(MYSQLI_ASSOC);
							}
						}
					}

//					if (!($_GET['qs'][4] > 0)){
						//On veut la liste
						$req = "SELECT groupes.*, groupe_users.etat AS uetat, groupe_users.id AS gid, groupe_users.date AS udate FROM groupes, groupe_users WHERE groupe_users.id_user='".$_GET['qs'][2]."' AND groupes.id=groupe_users.id_groupe AND groupe_users.etat='actif' ORDER BY groupe_users.date DESC";

						$query = $mysqli->query($req);
						$data2 = array();
						while($res = $query->fetch_array(MYSQLI_ASSOC)){
							$data2[$res['id']] = $res;
						}
//					}

					$data = array_replace($data2, $data);

					$mysqli->close();
				break;













				case 'cours':
					$_param['view'] .= "_cours";

					$mysqli = dbconnect();

					//Lier à un nouveau groupe
//					if ($_POST['groupe'] > 0){
					if (isset($_POST['cours']) && $_POST['cours'] != '0') {
						$_POST['cours'] = checkValidateKey($_POST['cours']);

/*						$datedebut = null;
						if ($_POST['datedebut']){
							$datedebut = strtotime($_POST['datedebut']);
							$datedebut = date('Y-m-d H:i:s',$datedebut);
						}*/

						$datefin = null;
						if ($_POST['datefin']){
							$datefin = strtotime($_POST['datefin']);
							$datefin = date('Y-m-d H:i:s',$datefin);
						}

						if ($_GET['qs'][4] > 0){
//							save log - TODO
//							saveLog('user2groupUpdated',$_GET['qs'][2],$_POST['groupe']);
							$req = "UPDATE users_lecons SET id_lecon='".$_POST['cours']."', datefin='".$datefin."' WHERE id='".$_GET['qs'][4]."'";

							$mysqli->query($req);

							if ($_POST['ajax']){
							//Confirm text
								$data['confirmText'] = "Mise à jour effectuée.";
								$data['refreshContent'] = true;
								exit(json_encode($data));
							}
						}else{
							saveLog('lecon2user',$_GET['qs'][2],$_POST['groupe']);
							$req = "INSERT INTO users_lecons SET id_lecon='".$_POST['cours']."', id_user='".$_GET['qs'][2]."', datefin='".$datefin."'";

							$mysqli->query($req);

							if ($_POST['ajax']){
							//Confirm text
								$data['confirmText'] = "Sauvegarde effectuée.";
								$data['refreshContent'] = true;
								exit(json_encode($data));
							}
						}


//						$_GET['qs'][4] = $mysqli->insert_id;
						$_GET['qs'][4] = "";
					}

					if ($_GET['qs'][4] == 'nouveau'){
						$_param['view'] .= "_edit";
					}else{
						if ($_GET['qs'][4] > 0){
							//Asked for specific ID
							if ($_GET['qs'][5] == "delete") {
								//On veut le supprimer
								$req = "DELETE FROM users_lecons WHERE id_user='".$_GET['qs'][2]."' AND id_cours='".$_GET['qs'][5]."'";
								$mysqli->query($req);

								$_GET['qs'][4] = "";

								if ($_POST['ajax']){
								//Confirm text
									$data['confirmText'] = "Mise à jour effectuée.";
									$data['refreshContent'] = true;
									exit(json_encode($data));
								}

							}else{
								$_param['view'] .= "_edit";

								//On veut l'afficher
								$req = "SELECT * FROM users_lecons WHERE id='".$_GET['qs'][4]."'";
								$query = $mysqli->query($req);
								$data = $query->fetch_array(MYSQLI_ASSOC);
							}
						}
					}

//					if (!($_GET['qs'][4] > 0)){
						//On veut la liste
						$req = "SELECT users_lecons.*, cours.nom AS cnom, cours_lecon.nom AS lnom, cours_lecon.id AS idl FROM users_lecons, cours, cours_lecon WHERE users_lecons.id_user='".$_GET['qs'][2]."' AND cours_lecon.id=users_lecons.id_lecon AND cours.id=cours_lecon.id_cours ORDER BY cours.nom ASC, cours_lecon.nom ASC";

						$query = $mysqli->query($req);
						$data2 = array();
						while($res = $query->fetch_array(MYSQLI_ASSOC)){
							$data2[$res['idl']] = $res;
						}
//					}

					$data = array_replace($data2, $data);

					$mysqli->close();
				break;
































				case 'delete':
					$keys = array();
					for($i=0;$i<=2;$i++){
						$key = date('YmdHi',strtotime('-'.$i.' min'));
						$keys[] = md5(INNER_SALT.$_GET['data'].$key.$_SESSION['user_id']);
					}

					$data['confirmkey'] = $keys[0];

					if (in_array($_POST['confirmkey'],$keys) && getpassword($_SESSION['user_id']) == getpassword($_SESSION['user_id'], $_POST['confirm'])){
						$mysqli = dbconnect();
						$req = "UPDATE users SET niveau='deleted' WHERE id='".$_GET['qs'][2]."'";
						$mysqli->query($req);

						$data['deleted'] = 'true';
						saveLog('delUser',$_GET['qs'][2]);

						if ($_POST['ajax']){
						//Confirm text
							$data['confirmText'] = "L'utilisateur a été supprimé.";
							$data['updateDOM'] = array(array(
							    'target'=>'#accountId'.$_GET['qs'][2],
							    'action'=>'remove')
							);

//							$data['refreshContent'] = true;
							exit(json_encode($data));
						}						
					}else{
						saveLog('tryDelUser',$_GET['qs'][2]);
					}

					$_param['view'] .= "_delete";
				break;
				default:
					//On a demandé quelque chose qui n'est pas «Nouveau». Charger les détails.
					$mysqli = dbconnect();

					switch($_GET['qs'][3]){
						case 'profile':
							if ($_POST['profile']){
								$req = "UPDATE users SET profile='".$_POST['profile']."' WHERE id='".$_GET['qs'][2]."'";
								$mysqli->query($req);

								$data['confirmText'] = "Le profil de l'enseignant a été mis à jour.";

								if ($_POST['fileName'.$_POST['fichieruploadNb']]) {
									$filename = $_POST['fileName'.$_POST['fichieruploadNb']];

									manageUpload("fichier", "data/profile/".$_GET['qs'][2].".jpg", $filename);

									$data['confirmText'] .= " Le téléversement du fichier est en cours.";
								}

								$data['refreshContent'] = false;
								exit(json_encode($data));
							}else{
								$req = "SELECT profile FROM users WHERE id_cie=0 AND id='".$_GET['qs'][2]."'";
								$query = $mysqli->query($req);
								$data = $query->fetch_array(MYSQLI_ASSOC);
								$_param['view'] .= "_profile";
							}
						break;
						default:
							if (isset($_POST['email'])) {

								$saveit = true;

								//S'assurer que le courriel & user ID n'est pas déjà assigné à un compte...
								if (strlen(trim($_POST['email'])) > 0){
									$req = "SELECT count(id) AS nb FROM users WHERE id_cie=0 AND email='".trim(strtolower($_POST['email']))."' AND id!='".$_GET['qs'][2]."' AND niveau != 'deleted'";
									$query = $mysqli->query($req);
									$res = $query->fetch_array(MYSQLI_ASSOC);
								}else{
									$res = array();
									$res['nb'] = 0;
								}

								if ($res['nb'] > 0){
									$data['error'] = 'emailExists';
									$saveit = false;
								}else if (USE_USERCODE == true && strlen($_POST['usercode']) > 0){
									$req = "SELECT count(id) AS nb FROM users WHERE id_cie=0 AND usercode='".trim(strtolower($_POST['usercode']))."' AND id!='".$_GET['qs'][2]."' AND niveau != 'deleted'";
									$query = $mysqli->query($req);
									$res = $query->fetch_array(MYSQLI_ASSOC);

									if ($res['nb'] > 0){
										$data['error'] = 'usercodeExists';
										$saveit = false;
									}
								}

								if ($saveit){
									$req = "SELECT id FROM users WHERE id_cie=0 AND id='".$_GET['qs'][2]."'";
									$query = $mysqli->query($req);
									$res = $query->fetch_array(MYSQLI_ASSOC);
									if (!$res['id']){
										$data['error'] = 'userNotFound';
										$saveit = false;
									}
								}

//								if ($saveit && strlen($_POST['niveau']) > 1) {
								if ($saveit && strlen($_POST['niveau']) > 1 && (strlen($_POST['usercode']) > 0 || strlen($_POST['email']) > 0)) {
									$passupdate = '';
									if ($_POST['pass'])
										$passupdate = ", pass='".getpassword($_GET['qs'][2],$_POST['pass'])."'";

									$req = "UPDATE users SET usercode='".trim(strtolower($_POST['usercode']))."', prenom='".$_POST['prenom']."', nom='".$_POST['nom']."', email='".trim(strtolower($_POST['email']))."', niveau='".$_POST['niveau']."'".$passupdate." WHERE id='".$_GET['qs'][2]."'";

									$mysqli->query($req);

									if ($_POST['ajax']){
									//Confirm text
										$data['confirmText'] = "L'utilisateur a été mis à jour.";

									$data['updateDOM'] = array(array(
									    'target'=>'#accountId'.$_GET['qs'][2].">h4",
									    'action'=>'update',
									    'value'=>$_POST['nom'].", ".$_POST['prenom'])
									);


		//								$data['refreshContent'] = true;
										exit(json_encode($data));
									}
								}else{
									//Une erreur est survenue.. Indiquer à la vue..
								}
							}

							if (!$data['error']){
								$req = "SELECT * FROM users WHERE id_cie=0 AND id='".$_GET['qs'][2]."'";
								$query = $mysqli->query($req);
								$data = $query->fetch_array(MYSQLI_ASSOC);
							}else{
								//Add posted data to $data (to autofill the fields)
								$data = array_merge($data, $_POST);
							}

							$_param['view'] .= "_edit";
						break;
					}

					$mysqli->close();

				break;
			}
		break;
	}
?>