<?php
//exit('entreprise');

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

	if ($_SESSION['accountType'] == 'cie' && $_GET['qs'][2] != $_SESSION['id_cie']){
		exit('ERR3');
	}
		

	switch($_GET['qs'][2]){
		case '':
		case null:
			if ($_SESSION['accountType'] != 'campus'){
				exit('err2');
			}

			//On est sur le «home» - Charger les comptes.
			$mysqli = dbconnect();
			$req = "SELECT * FROM cie WHERE etat = 'actif' ORDER BY nom ASC";

			$query = $mysqli->query($req);
			$data = array();
			while($res = $query->fetch_array(MYSQLI_ASSOC)){
				$req2 = "SELECT count(1) AS nb FROM users WHERE id_cie='".$res['id']."'";
				$query2 = $mysqli->query($req2);
				$res2 = $query2->fetch_array(MYSQLI_ASSOC);
				$res['nbUser'] = $res2['nb'];

				$req2 = "SELECT count(1) AS nb FROM cie_licenses WHERE id_cie='".$res['id']."' AND etat='active'";
				$query2 = $mysqli->query($req2);
				$res2 = $query2->fetch_array(MYSQLI_ASSOC);
				$res['nbCours'] = $res2['nb'];

				$data[] = $res;
			}

			$mysqli->close();
		break;
		case 'nouveau':
			//On a demandé de créer un nouvel cie...
			if ($_SESSION['accountType'] != 'campus'){
				exit('err1');
			}

			if (isset($_POST['nom'])) {
				if (strlen($_POST['nom']) > 0) {
					$mysqli = dbconnect();

					$req = "INSERT INTO cie SET nom='".trim($_POST['nom'])."', datedebut=NOW()";
					$mysqli->query($req);
					$cieid = $mysqli->insert_id;

					saveLog('newCie',$_GET['qs'][2]);

					if ($_POST['alsoAddUser'] == '1'){
						//Mark it as 'user need saving' and let the script go !
						$_GET['qs'][2] = $cieid;
						$_GET['qs'][3] = 'users';
						$_GET['qs'][4] = 'nouveau';
						$_POST['niveau'] = 'admin';
						$_POST['nom'] = $_POST['userNom'];
					}else{
						if ($_POST['ajax']){
						//Confirm text
							$data['confirmText'] = "L'entreprise a été sauvegardé.";
							$data['refreshContent'] = true;
							exit(json_encode($data));
						}
					}

					$mysqli->close();
				}else{
					$data['nom'] = $_POST['nom'];
					$_param['view'] .= "_edit";
					$data['error'] = 'noName';
					break;
				}
			}else{
				$_param['view'] .= "_edit";
				break;
			}
		//Le break est dans le else. On ne break pas si on vient de faire une insertion.
		default:
			switch($_GET['qs'][3]){
				case 'users':
					if ($_SESSION['accountType'] != 'campus' && $_GET['qs'][2] != $_SESSION['id_cie']) {
						exit('err6');
					}


					$_param['view'] .= "_users";

					$mysqli = dbconnect();

					if ($_GET['qs'][4] > 0){
						//Delete it
						if ($_GET['qs'][5] == "delete"){
							$keys = array();
							for($i=0;$i<=2;$i++){
								$key = date('YmdHi',strtotime('-'.$i.' min'));
								$keys[] = md5(INNER_SALT.$_GET['data'].$key.$_SESSION['user_id']);
							}

							$data['confirmkey'] = $keys[0];

							if (in_array($_POST['confirmkey'],$keys) && getpassword($_SESSION['user_id']) == getpassword($_SESSION['user_id'], $_POST['confirm'])){
								$mysqli = dbconnect();
								$req = "UPDATE users SET niveau='deleted' WHERE id='".$_GET['qs'][4]."' AND id_cie='".$_GET['qs'][2]."'";
								$mysqli->query($req);

								$data['deleted'] = 'true';
								saveLog('delUser',$_GET['qs'][2]);

								if ($_POST['ajax']){
								//Confirm text
									$data['confirmText'] = "L'utilisateur a été supprimé.";
									$data['updateDOM'] = array(array(
									    'target'=>'#accountId'.$_GET['qs'][4],
									    'action'=>'remove')
									);

		//							$data['refreshContent'] = true;
									exit(json_encode($data));
								}						
							}else{
								saveLog('tryDelUser',$_GET['qs'][2]);
							}

							$_param['view'] = "account_delete";						
						}else{
							//Edit
							$_param['view'] = "account_edit";

							if (isset($_POST['email'])) {
								$saveit = true;

								//S'assurer que le courriel & user ID n'est pas déjà assigné à un compte...
								if (strlen(trim($_POST['email'])) > 0){
									$req = "SELECT count(id) AS nb FROM users WHERE id_cie!=0 AND email='".trim(strtolower($_POST['email']))."' AND id!='".$_GET['qs'][4]."' AND niveau != 'deleted'";
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
									$req = "SELECT count(id) AS nb FROM users WHERE id_cie!=0 AND usercode='".trim(strtolower($_POST['usercode']))."' AND id!='".$_GET['qs'][4]."' AND niveau != 'deleted'";
									$query = $mysqli->query($req);
									$res = $query->fetch_array(MYSQLI_ASSOC);

									if ($res['nb'] > 0){
										$data['error'] = 'usercodeExists';
										$saveit = false;
									}
								}

								if ($saveit){
									$req = "SELECT id FROM users WHERE id_cie='".$_GET['qs'][2]."' AND id='".$_GET['qs'][4]."'";
									$query = $mysqli->query($req);
									$res = $query->fetch_array(MYSQLI_ASSOC);
									if (!$res['id']){
										$data['error'] = 'userNotFound';
										$saveit = false;
									}
								}

								if ($saveit){
									$passupdate = '';
									if ($_POST['pass'])
										$passupdate = ", pass='".getpassword($_GET['qs'][4],$_POST['pass'])."'";

									$req = "UPDATE users SET usercode='".trim(strtolower($_POST['usercode']))."', prenom='".$_POST['prenom']."', nom='".$_POST['nom']."', email='".trim(strtolower($_POST['email']))."', niveau='".$_POST['niveau']."'".$passupdate." WHERE id='".$_GET['qs'][4]."' AND id_cie='".$_GET['qs'][2]."'";

									$mysqli->query($req);

									if ($_POST['ajax']){
									//Confirm text
										$data['confirmText'] = "L'utilisateur a été mis à jour.";

										$data['refreshContent'] = true;

										exit(json_encode($data));
									}
								}else{
									//Une erreur est survenue.. Indiquer à la vue..
								}
							}

							$req = "SELECT * FROM users WHERE id_cie='".$_GET['qs'][2]."' AND id='".$_GET['qs'][4]."'";
							$query = $mysqli->query($req);
							$data = $query->fetch_array(MYSQLI_ASSOC);

						}
					}else if($_GET['qs'][4] == 'nouveau'){
						//New account
						$_param['view'] = "account_edit";

						if (isset($_POST['email'])) {
							$saveit = true;

							//S'assurer que le courriel & user ID n'est pas déjà assigné à un compte...
							if (strlen(trim($_POST['email'])) > 0){
								$req = "SELECT count(1) AS nb FROM users WHERE id_cie!=0 AND email='".trim(strtolower($_POST['email']))."' AND niveau != 'deleted'";
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
								$req = "SELECT count(1) AS nb FROM users WHERE id_cie!=0 AND usercode='".trim(strtolower($_POST['usercode']))."' AND niveau != 'deleted'";
								$query = $mysqli->query($req);
								$res = $query->fetch_array(MYSQLI_ASSOC);
								if ($res['nb'] > 0){
									$data['error'] = 'usercodeExists';
									$saveit = false;
								}
							}

//							exit('Ready to insert');

							if ($saveit){
//								echo "INSERT HERE";
								$req = "INSERT INTO users SET id_cie='".$_GET['qs'][2]."', usercode='".trim(strtolower($_POST['usercode']))."', prenom='".$_POST['prenom']."', nom='".$_POST['nom']."', email='".trim(strtolower($_POST['email']))."', pass='temp', niveau='".$_POST['niveau']."', date=NOW()";
								$mysqli->query($req);

								$_GET['qs'][2] = $mysqli->insert_id;
								$req = "UPDATE users SET pass='".getpassword($_GET['qs'][2],$_POST['pass'])."' WHERE id='".$_GET['qs'][2]."'";
								$mysqli->query($req);

								saveLog('newUser',$_GET['qs'][2]);

								if ($_POST['ajax']){
								//Confirm text
									if ($_POST['alsoAddUser'] == 1){
										$data['confirmText'] = "L'entreprise et l'utilisateur ont étés sauvegardés.";
									}else{
										$data['confirmText'] = "L'utilisateur a été sauvegardé.";
									}
									$data['refreshContent'] = true;
									exit(json_encode($data));
								}
							}else{
								//Une erreur est survenue.. Indiquer à la vue..
							}
						}
					}else{
						//We just want to see the list
						$req = "SELECT * FROM users WHERE id_cie='".$_GET['qs'][2]."' ORDER BY niveau DESC, nom DESC";

						$query = $mysqli->query($req);
						$data = array();
						while($res = $query->fetch_array(MYSQLI_ASSOC)){
							$data[$res['id']] = $res;
						}
					}

					$mysqli->close();
				break;
				case 'cours':
					if ($_GET['qs'][2] != $_SESSION['id_cie'] && $_SESSION['accountType'] != 'campus'){
						exit('err5');
					}

					$_param['view'] .= "_cours";

					$mysqli = dbconnect();

					//Load every formations
					$req = "SELECT cours.nom, cours.id AS cid, cie_licenses.id, cie_licenses.nblicenses FROM cie_licenses, cours WHERE cours.id=cie_licenses.id_cours AND cie_licenses.id_cie='".$_GET['qs'][2]."' ORDER BY nom ASC";

					$query = $mysqli->query($req);
					$data = array();
					while($res = $query->fetch_array(MYSQLI_ASSOC)){
						$data[$res['cid']] = $res;
					}

					if ($_GET['qs'][4] > 0) {
						if ($_GET['qs'][5] == "delete") {
							//Delete it
							$keys = array();
							for($i=0;$i<=2;$i++){
								$key = date('YmdHi',strtotime('-'.$i.' min'));
								$keys[] = md5(INNER_SALT.$_GET['data'].$key.$_SESSION['user_id']);
							}

							$data['confirmkey'] = $keys[0];

							if (in_array($_POST['confirmkey'],$keys) && getpassword($_SESSION['user_id']) == getpassword($_SESSION['user_id'], $_POST['confirm'])){
								$mysqli = dbconnect();
								$req = "UPDATE cie_licenses SET etat='deleted' WHERE id='".$_GET['qs'][4]."' AND id_cie='".$_GET['qs'][2]."'";
								$mysqli->query($req);

								$data['deleted'] = 'true';
								saveLog('delCieLicense',$_GET['qs'][2]);

								if ($_POST['ajax']){
								//Confirm text
									$data['confirmText'] = "La license a été supprimé.";

									$data['refreshContent'] = true;
									exit(json_encode($data));
								}						
							}else{
								saveLog('tryDelCieLicense',$_GET['qs'][2]);
							}

							$_param['view'] .= "_delete";						
						}else{
							//Edit
							if (isset($_POST['idCours'])) {
								//Update it !
								$_POST['idCours'] = checkValidateKey($_POST['idCours']);

								$date = strtotime($_POST['datedebut']);
								if ($date > 0){
									$datedebut = date('Y-m-d H:i:s',$date);
								}

								$date = strtotime($_POST['datefin']);
								if ($date > 0){
									$datefin = date('Y-m-d H:i:s',$date);
								}

								$req = "UPDATE cie_licenses SET id_cours='".$_POST['idCours']."', nblicenses='".$_POST['nblicenses']."', datedebut='".$datedebut."', datefin='".$datefin."' WHERE id_cie='".$_GET['qs'][2]."' AND id='".$_GET['qs'][4]."'";
								$mysqli->query($req);

//								return ?
								if ($_POST['ajax']){
								//Confirm text
									$data['confirmText'] = "La license a été mise à jour";

									$data['refreshContent'] = true;
									exit(json_encode($data));
								}						
							}

							$_param['view'] .= "_edit";

							$exdata = $data;
							$data = array();

							//Load data
							$req = "SELECT * FROM cie_licenses WHERE id='".$_GET['qs'][4]."'";
							$query = $mysqli->query($req);
							$data = $query->fetch_array(MYSQLI_ASSOC);

							//Load every cours (not already selected)
							$req = "SELECT * FROM cours WHERE etat='actif' ORDER BY nom ASC";
							$query = $mysqli->query($req);
							$data['cours'] = array();
							while($res = $query->fetch_array(MYSQLI_ASSOC)){
								if (!is_array($exdata[$res['id']]) || $res['id'] == $data['id_cours']){
									$data['cours'][$res['id']] = $res['nom'];
								}
							}
						}
					}else if($_GET['qs'][4] == 'nouveau'){
						//New account
						$_param['view'] .= "_edit";

						$exdata = $data;
						$data = array();

						//Load every cours (not already selected)
						$req = "SELECT * FROM cours WHERE etat='actif' ORDER BY nom ASC";
						$query = $mysqli->query($req);
						$data['cours'] = array();
						while($res = $query->fetch_array(MYSQLI_ASSOC)){
							if (!is_array($exdata[$res['id']])){
								$data['cours'][$res['id']] = $res['nom'];
							}
						}

						if (isset($_POST['idCours'])) {
							$_POST['idCours'] = checkValidateKey($_POST['idCours']);
							$saveit = true;

							if ($saveit){
								//SAVE IT !
								$date = strtotime($_POST['datedebut']);
								if ($date > 0){
									$datedebut = date('Y-m-d H:i:s',$date);
								}

								$date = strtotime($_POST['datefin']);
								if ($date > 0){
									$datefin = date('Y-m-d H:i:s',$date);
								}

								$req = "INSERT INTO cie_licenses SET id_cie='".$_GET['qs'][2]."', id_cours='".$_POST['idCours']."', nblicenses='".$_POST['nblicenses']."', datedebut='".$datedebut."', datefin='".$datefin."'";
								$mysqli->query($req);

								if ($_POST['ajax']){
								//Confirm text
									$data['confirmText'] = "La license a été sauvegardée";

									$data['refreshContent'] = true;
									exit(json_encode($data));
								}						

							}else{
								//Une erreur est survenue.. Indiquer à la vue..
							}
						}
					}else{
						//We just want to see the list

						//It's already loaded from up there (not to show double).

						//Maybe go through it and check the number of licenses still available ?


/*							ALREADY LOADED!
						$req = "SELECT cours.nom, cie_licenses.id FROM cie_licenses, cours WHERE cours.id=cie_licenses.id_cours AND cie_licenses.id_cie='".$_GET['qs'][2]."' ORDER BY nom ASC";

						$query = $mysqli->query($req);
						$data = array();
						while($res = $query->fetch_array(MYSQLI_ASSOC)){
							$data[$res['id']] = $res;
						}*/
					}

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
						$req = "UPDATE cie SET etat='inactif' WHERE id='".$_GET['qs'][2]."'";
						$mysqli->query($req);

						$data['deleted'] = 'true';
						saveLog('delUser',$_GET['qs'][2]);

						if ($_POST['ajax']){
						//Confirm text
							$data['confirmText'] = "L'entreprise a été supprimée.";
							$data['updateDOM'] = array(array(
							    'target'=>'#cieId'.$_GET['qs'][2],
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
/*				case 'formations':
					$_param['view'] .= "_formations";

					$mysqli = dbconnect();

					//Lier un nouveau cours
					if ($_POST['cours'] > 0){
						$datefin = null;
						if ($_POST['datefin'] != ""){
							$date = strtotime($_POST['datefin']);
							if ($date > 0){
								$datefin = date('Y-m-d H:i:s',$date);
							}
						}

						$datedebut = null;
						if ($_POST['datedebut'] != ""){
							$date = strtotime($_POST['datedebut']);
							if ($date > 0){
								$datedebut = date('Y-m-d H:i:s',$date);
							}
						}

						if ($_GET['qs'][4] > 0){
//						saveLog('formation2group',$_POST['cours'], $_GET['qs'][2]);
							$req = "UPDATE formations SET id_cours='".$_POST['cours']."', id_user='".$_POST['prof']."'";

							if ($datedebut){
								$req .= ", datedebut='".$datedebut."'";
							}
							if ($datefin){
								$req .= ", datefin='".$datefin."'";
							}

							$req .= " WHERE id='".$_GET['qs'][4]."'";

							$mysqli->query($req);

							if ($_POST['ajax']){
							//Confirm text
								$data['confirmText'] = "Mise à jour effectuée.";
								$data['refreshContent'] = true;
								exit(json_encode($data));
							}
						}else{
							saveLog('formation2group',$_POST['cours'], $_GET['qs'][2]);
							$req = "INSERT INTO formations SET id_cours='".$_POST['cours']."', id_groupe='".$_GET['qs'][2]."', id_user='".$_POST['prof']."', date=NOW()";

							if ($datedebut){
								$req .= ", datedebut='".$datedebut."'";
							}
							if ($datefin){
								$req .= ", datefin='".$datefin."'";
							}

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
								$req = "UPDATE formations SET etat='inactif' WHERE id='".$_GET['qs'][4]."' AND id_groupe='".$_GET['qs'][2]."' AND etat='actif'";
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
								$req = "SELECT * FROM formations WHERE id='".$_GET['qs'][4]."'";
								$query = $mysqli->query($req);
								$data = $query->fetch_array(MYSQLI_ASSOC);
							}
						}

						if (!($_GET['qs'][4] > 0)){
							//On veut la liste
							$req = "SELECT cours.*, formations.date AS fdate, formations.id AS fid FROM cours, formations WHERE formations.id_groupe='".$_GET['qs'][2]."' AND cours.id=formations.id_cours AND formations.etat='actif' ORDER BY fdate DESC";

							$query = $mysqli->query($req);
							$data = array();
							while($res = $query->fetch_array(MYSQLI_ASSOC)){
								$data[$res['id']] = $res;
							}
						}

						$mysqli->close();
					}
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
						$req = "UPDATE groupes SET etat='deleted' WHERE id='".$_GET['qs'][2]."'";
						$mysqli->query($req);

						$data['deleted'] = 'true';
						saveLog('delGroup',$_GET['qs'][2]);

						if ($_POST['ajax']){
						//Confirm text
							$data['confirmText'] = "Le groupe a été supprimé.";
							$data['updateDOM'] = array(array(
							    'target'=>'#groupId'.$_GET['qs'][2],
							    'action'=>'remove')
							);
//							$data['refreshContent'] = true;
							exit(json_encode($data));
						}

					}else{
						saveLog('tryDelGroup',$_GET['qs'][2]);
					}

					$_param['view'] .= "_delete";	
				break;
				case 'message':
					$mysqli = dbconnect();
					//Si on a reçu quelque chose
					if (isset($_POST['message'])){
						//Delete old message from same user
						$req = "DELETE FROM groupes_message WHERE id_groupe='".$_GET['qs'][2]."' AND id_user='".$_SESSION['user_id']."'";
						$mysqli->query($req);

						//Add new message

						if (strtotime($_POST['date_fin']) > 0){
							$_POST['date_fin'] = "'".$_POST['date_fin']."'";
						}else{
							$_POST['date_fin'] = "NULL";
						}

						if (strtotime($_POST['date_debut']) > 0){
							$_POST['date_debut'] = "'".$_POST['date_debut']."'";
						}else{
							$_POST['date_debut'] = "NULL";
						}

						$req = "INSERT INTO groupes_message SET id_groupe='".$_GET['qs'][2]."', id_user='".$_SESSION['user_id']."', message='".$_POST['message']."', date_debut=".$_POST['date_debut'].", date_fin=".$_POST['date_fin']."";

						$mysqli->query($req);

						$data['confirmText'] = "Le message est sauvegardé.";
						$data['refreshContent'] = false;
						exit(json_encode($data));
					}else if ($_GET['qs'][4] == "delete"){
						$req = "DELETE FROM groupes_message WHERE id_groupe='".$_GET['qs'][2]."' AND id_user='".$_SESSION['user_id']."'";
						$mysqli->query($req);

						$data['confirmText'] = "Le message est supprimé.";
						$data['refreshContent'] = false;
						exit(json_encode($data));
					}

					$req = "SELECT * FROM groupes_message WHERE id_groupe='".$_GET['qs'][2]."' AND id_user='".$_SESSION['user_id']."'";
					$query = $mysqli->query($req);
					$res = $query->fetch_array(MYSQLI_ASSOC);

					$data['message'] = $res['message'];
					$data['date_debut'] = $res['date_debut'];
					$data['date_fin'] = $res['date_fin'];

					$mysqli->close();

					$_param['view'] .= "_message";	
				break;*/
				default:
					if ($_GET['qs'][2] != $_SESSION['id_cie'] && $_SESSION['accountType'] != 'campus'){
						exit('err4');
					}

					$mysqli = dbconnect();
					if (isset($_POST['nom'])) {

						if (1){
							$req = "UPDATE cie SET nom='".trim($_POST['nom'])."' WHERE id='".$_GET['qs'][2]."'";

							$mysqli->query($req);

							if ($_POST['ajax']){
							//Confirm text
								$data['confirmText'] = "L'entreprise a été mise à jour.";
								$data['updateDOM'] = array(array(
								    'target'=>'#cieId'.$_GET['qs'][2].">h4",
								    'action'=>'update',
								    'value'=>$_POST['nom'])
								);
//								$data['refreshContent'] = true;
								exit(json_encode($data));
							}
						}else{
							//Une erreur est survenue.. Indiquer à la vue..
						}
					}

					//On a demandé quelque chose qui n'est pas «Nouveau». Charger les détails.
					$req = "SELECT * FROM cie WHERE id='".$_GET['qs'][2]."'";
					$query = $mysqli->query($req);
					$data = $query->fetch_array(MYSQLI_ASSOC);

					$mysqli->close();

					$_param['view'] .= "_edit";
				break;
			}
		break;
	}
?>