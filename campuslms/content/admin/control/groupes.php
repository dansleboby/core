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
		case '':
		case null:
			//On est sur le «home» - Charger les groupes.
			$mysqli = dbconnect();
			if ($_SESSION['accountType'] == 'cie'){
				$req = "SELECT cours.nom, cie_licenses.nblicenses, cie_licenses.id FROM cours, cie_licenses WHERE cie_licenses.id_cie='".$_SESSION['id_cie']."' AND cours.id=cie_licenses.id_cours AND cie_licenses.etat != 'deleted' AND cours.etat != 'deleted' ORDER BY cours.nom ASC";

				$query = $mysqli->query($req);
				$data = array();
				while($res = $query->fetch_array(MYSQLI_ASSOC)){
					$req2 = "SELECT count(id) AS nb FROM  cie_license_users WHERE id_license='".$res['id']."'";

					$query2 = $mysqli->query($req2);
					$res2 = $query2->fetch_array(MYSQLI_ASSOC);

					$res['nbUser'] = $res2['nb'];

					$data[] = $res;
				}


			}else{
				$req = "SELECT * FROM groupes WHERE etat != 'deleted' ORDER BY nom ASC";

				$query = $mysqli->query($req);
				$data = array();
				while($res = $query->fetch_array(MYSQLI_ASSOC)){
					$req2 = "SELECT count(groupe_users.id) AS nb FROM groupe_users WHERE groupe_users.id_groupe='".$res['id']."' AND groupe_users.etat='actif'";

					$query2 = $mysqli->query($req2);
					$res2 = $query2->fetch_array(MYSQLI_ASSOC);

					$res['nbUser'] = $res2['nb'];

					$req2 = "SELECT count(formations.id) AS nb FROM cours, formations WHERE formations.id_groupe='".$res['id']."' AND cours.id=formations.id_cours AND formations.etat!='deleted'";

					$query2 = $mysqli->query($req2);
					$res2 = $query2->fetch_array(MYSQLI_ASSOC);

					$res['nbFormation'] = $res2['nb'];

					$data[] = $res;
				}

			}

			$mysqli->close();
		break;
		case 'nouveau':
			//On a demandé de créer un nouveau groupe...
			if ($_SESSION['accountType'] != 'campus'){
				exit('Err');
			}

			if (isset($_POST['nom']) && trim($_POST['nom']) != "") {
				$mysqli = dbconnect();

				//S'assurer que le nom n'est pas déjà assigné à un groupe...

				if (1) {
//					$req = "INSERT INTO groupes SET nom='".$_POST['nom']."', etat='".$_POST['etat']."', date=NOW(), datedebut='".$_POST['datedebut']."', datefin='".$_POST['datefin']."'";
					$req = "INSERT INTO groupes SET nom='".$_POST['nom']."', etat='actif', date=NOW(), datedebut='".$_POST['datedebut']."', datefin='".$_POST['datefin']."'";
					$mysqli->query($req);

					$_GET['qs'][2] = $mysqli->insert_id;

					saveLog('newGroup',$_GET['qs'][2]);

					if ($_POST['ajax']){
					//Confirm text
						$data['confirmText'] = "Le groupe a été sauvegardé.";
						$data['refreshContent'] = true;
						exit(json_encode($data));
					}

				}else{
					//Une erreur est survenue.. Indiquer à la vue..
				}
				$mysqli->close();
			}else{
				$_param['view'] .= "_edit";
				break;
			}
		//Le break est dans le else. On ne break pas si on vient de faire une insertion.
		default:
			switch($_GET['qs'][3]){
				case 'users':
					$_param['view'] .= "_users";

					$mysqli = dbconnect();

//					if ($_POST['user'] > 0){
					if (isset($_POST['user']) && $_POST['user'] != '0') {
						$_POST['user'] = checkValidateKey($_POST['user']);

						$datefin = null;
						if ($_POST['datefin']){
							$datefin = strtotime($_POST['datefin']);
							$datefin = date('Y-m-d H:i:s',$datefin);
						}

						if ($_POST['user'] > 0){
							//Lier un nouvel utilisateur
							if ($_GET['qs'][4] > 0){
	//							saveLog('user2group',$_POST['user'], $_GET['qs'][2]);
								if ($_SESSION['accountType'] != 'campus'){
									exit('Err');
								}

								$req = "UPDATE groupe_users SET id_user='".$_POST['user']."', datefin='".$datefin."' WHERE id='".$_GET['qs'][4]."'";
								$mysqli->query($req);

								if ($_POST['ajax']){
								//Confirm text
									$data['confirmText'] = "Mise à jour effectuée.";
									$data['refreshContent'] = true;
									exit(json_encode($data));
								}

							}else{
								if ($_SESSION['accountType'] == 'cie'){
//									saveLog('user2group',$_POST['user'], $_GET['qs'][2]);
									$req = "INSERT INTO cie_license_users SET id_user='".$_POST['user']."', id_license='".$_GET['qs'][2]."', date=NOW()";
								}else{
									saveLog('user2group',$_POST['user'], $_GET['qs'][2]);

									$req = "INSERT INTO groupe_users SET id_user='".$_POST['user']."', id_groupe='".$_GET['qs'][2]."', etat='actif', date=NOW(), datefin='".$datefin."'";
								}
								$mysqli->query($req);

								if ($_POST['ajax']){
								//Confirm text
									$data['confirmText'] = "Sauvegarde effectuée.";
									$data['refreshContent'] = true;
									exit(json_encode($data));
								}

							}
						}else{
							$_param['view'] .= "_edit";
							break;
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
								if ($_SESSION['accountType'] != 'campus'){
									exit('Err');
								}

								$req = "UPDATE groupe_users SET etat='inactif' WHERE id='".$_GET['qs'][4]."' AND id_groupe='".$_GET['qs'][2]."' AND etat='actif'";
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
								if ($_SESSION['accountType'] == 'cie'){
									$req = "SELECT cie_license_users.* FROM cie_license_users, cie_licenses WHERE cie_license_users.id='".$_GET['qs'][4]."' AND cie_licenses.id=cie_license_users.id_license AND cie_licenses.id_cie='".$_SESSION['id_cie']."'";
								}else{
									$req = "SELECT * FROM groupe_users WHERE id='".$_GET['qs'][4]."'";
								}
								$query = $mysqli->query($req);
								$data = $query->fetch_array(MYSQLI_ASSOC);
							}
						}
					}

//					if (!($_GET['qs'][4] > 0)){
						//On veut la liste
						if ($_SESSION['accountType'] == 'cie'){
							$req = "SELECT users.*, cie_license_users.id AS gid FROM users, cie_license_users WHERE cie_license_users.id_license='".$_GET['qs'][2]."' AND users.id=cie_license_users.id_user ORDER BY users.nom ASC";
						}else{
							$req = "SELECT users.*, groupe_users.etat AS uetat, groupe_users.date AS udate, groupe_users.id AS gid FROM users, groupe_users WHERE groupe_users.id_groupe='".$_GET['qs'][2]."' AND users.id=groupe_users.id_user AND groupe_users.etat='actif' ORDER BY groupe_users.date DESC";
						}

						$query = $mysqli->query($req);
						$data2 = array();
						while($res = $query->fetch_array(MYSQLI_ASSOC)){
							$data2[$res['id']] = $res;
						}
//					}

					$data = array_replace($data2, $data);

					$mysqli->close();
				break;
				case 'formations':
					if ($_SESSION['accountType'] != 'campus'){
						exit('Err');
					}

					$_param['view'] .= "_formations";

					$mysqli = dbconnect();

					//Lier un nouveau cours
//					if ($_POST['cours'] > 0){
					if (isset($_POST['cours'])) {
						$_POST['cours'] = checkValidateKey($_POST['cours']);

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
							$req = "UPDATE formations SET id_cours='".$_POST['cours']."', id_user='".$_POST['prof']."', etat='".$_POST['etat']."'";

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
							$req = "INSERT INTO formations SET id_cours='".$_POST['cours']."', id_groupe='".$_GET['qs'][2]."', id_user='".$_POST['prof']."', etat='".$_POST['etat']."', date=NOW()";

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
								$req = "UPDATE formations SET etat='deleted' WHERE id='".$_GET['qs'][4]."' AND id_groupe='".$_GET['qs'][2]."'";
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
					}

//					if (!($_GET['qs'][4] > 0)){
						//On veut la liste
						$req = "SELECT cours.*, formations.date AS fdate, formations.id AS fid, formations.etat FROM cours, formations WHERE formations.id_groupe='".$_GET['qs'][2]."' AND cours.id=formations.id_cours AND formations.etat!='deleted' ORDER BY formations.etat ASC, nom ASC";//fdate DESC

						$query = $mysqli->query($req);
						$data2 = array();
						while($res = $query->fetch_array(MYSQLI_ASSOC)){
							$data2[$res['id']] = $res;
						}
//					}

					$data = array_replace($data2, $data);

					$mysqli->close();
				break;
				case 'delete':
					if ($_SESSION['accountType'] != 'campus'){
						exit('Err');
					}

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
					if ($_SESSION['accountType'] != 'campus'){
						exit('Err');
					}

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
				break;
				default:
					if ($_SESSION['accountType'] != 'campus'){
						exit('Err');
					}

					$mysqli = dbconnect();
					if (isset($_POST['nom'])) {
						//S'assurer que le groupe existe...

						if (1){
//							$req = "UPDATE groupes SET nom='".$_POST['nom']."', etat='".$_POST['etat']."', datedebut='".$_POST['datedebut']."', datefin='".$_POST['datefin']."' WHERE id='".$_GET['qs'][2]."'";
							$req = "UPDATE groupes SET nom='".$_POST['nom']."', etat='actif', datedebut='".$_POST['datedebut']."', datefin='".$_POST['datefin']."' WHERE id='".$_GET['qs'][2]."'";

							$mysqli->query($req);

							if ($_POST['ajax']){
							//Confirm text
								$data['confirmText'] = "Le groupe a été mis à jour.";
							$data['updateDOM'] = array(array(
							    'target'=>'#groupId'.$_GET['qs'][2].">h4",
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
					$req = "SELECT * FROM groupes WHERE id='".$_GET['qs'][2]."'";
					$query = $mysqli->query($req);
					$data = $query->fetch_array(MYSQLI_ASSOC);

					$mysqli->close();

					$_param['view'] .= "_edit";
				break;
			}
		break;
	}
?>