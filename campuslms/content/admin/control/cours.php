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
			//On est sur le «home» - Charger les cours.
			$mysqli = dbconnect();
//			$req = "UPDATE cours SET etat = 'inactif'";
			if ($_SESSION['accountType'] == 'campus'){
				$req = "SELECT * FROM cours WHERE etat != 'deleted' ORDER BY nom ASC";
			}else{
				$req = "SELECT cours.*, cie_licenses.nblicenses FROM cours, cie_licenses WHERE cie_licenses.id_cie='".$_SESSION['id_cie']."' AND cours.id=cie_licenses.id_cours AND cie_licenses.etat != 'deleted' AND cours.etat != 'deleted' ORDER BY cours.nom ASC";
			}
			$query = $mysqli->query($req);
			$data = array();

			while($res = $query->fetch_array(MYSQLI_ASSOC)){
				$req2 = "SELECT count(groupes.id) AS nb FROM groupes, formations WHERE formations.id_cours='".$res['id']."' AND groupes.id=formations.id_groupe AND formations.etat!='deleted'";
				$query2 = $mysqli->query($req2);
				$res2 = $query2->fetch_array(MYSQLI_ASSOC);

				$res['nbGroupe'] = $res2['nb'];

				$data[] = $res;
			}

			$mysqli->close();
		break;
		case 'nouveau':
			//On a demandé de créer un nouveau cours...
			if ($_SESSION['accountType'] != 'campus'){
				exit('Err');
			}

			if (isset($_POST['titre']) && trim($_POST['titre'] != "")) {
				$_POST['user'] = checkValidateKey($_POST['user']);

				if ($_POST['user'] > 0){
					$mysqli = dbconnect();
					$req = "INSERT INTO cours SET nom='".$_POST['titre']."', description='".$_POST['description']."', id_user='".$_POST['user']."', date=NOW(), type='".$_POST['type']."'";
					$mysqli->query($req);

					$_GET['qs'][2] = $mysqli->insert_id;
					$mysqli->close();

					saveLog('newCours',$_GET['qs'][2]);

					if ($_POST['ajax']){
					//Confirm text
						$data['confirmText'] = "Le cours a été sauvegardé.";
						$data['refreshContent'] = true;
						exit(json_encode($data));
					}
				}else{
					$_param['view'] .= "_edit";
					break;
				}
			}else{
				$_param['view'] .= "_edit";
				break;
			}
		//Le break est dans le else. On ne break pas si on vient de faire une insertion.
		default:
			switch($_GET['qs'][3]){
				case 'groupes':
					if ($_SESSION['accountType'] != 'campus'){
						exit('Err');
					}

					$_param['view'] .= "_groupes";

					$mysqli = dbconnect();

//					if ($_POST['groupe'] > 0){
					if (isset($_POST['groupe'])) {
						$_POST['groupe'] = checkValidateKey($_POST['groupe']);

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
//								saveLog('formation2groupUpdated', $_GET['qs'][2], $_POST['groupe']);

							$req = "UPDATE formations SET id_groupe='".$_POST['groupe']."', id_user='".$_POST['prof']."', etat='".$_POST['etat']."'";

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
								$data['confirmText'] = "La formation a été mise à jour.";
								$data['refreshContent'] = true;
								exit(json_encode($data));
							}
						}else{
							saveLog('formation2group', $_GET['qs'][2], $_POST['groupe']);

							$req = "INSERT INTO formations SET id_cours='".$_GET['qs'][2]."', id_groupe='".$_POST['groupe']."', id_user='".$_POST['prof']."', etat='".$_POST['etat']."', date=NOW()";
							if ($datedebut){
								$req .= ", datedebut='".$datedebut."'";
							}
							if ($datefin){
								$req .= ", datefin='".$datefin."'";
							}

							$mysqli->query($req);

							if ($_POST['ajax']){
							//Confirm text
								$data['confirmText'] = "La formation a été sauvegardée.";
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
								$req = "UPDATE formations SET etat='deleted' WHERE id_cours='".$_GET['qs'][2]."' AND id='".$_GET['qs'][4]."'";
								$mysqli->query($req);

								if ($_POST['ajax']){
								//Confirm text
									$data['confirmText'] = "La formation a été supprimée.";
									$data['refreshContent'] = true;
									exit(json_encode($data));
								}

								$_GET['qs'][4] = "";
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
						$req = "SELECT groupes.*, formations.date AS fdate, formations.id AS fid, formations.etat FROM groupes, formations WHERE formations.id_cours='".$_GET['qs'][2]."' AND groupes.id=formations.id_groupe AND formations.etat!='deleted' ORDER BY formations.etat ASC, nom ASC";	//fdate DESC

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
						$req = "UPDATE cours SET etat='deleted' WHERE id='".$_GET['qs'][2]."'";
						$mysqli->query($req);

						$data['deleted'] = 'true';
						saveLog('delCours',$_GET['qs'][2]);

						if ($_POST['ajax']){
						//Confirm text
							$data['confirmText'] = "Le cours a été supprimé.";

							$data['updateDOM'] = array(array(
							    'target'=>'#courId'.$_GET['qs'][2],
							    'action'=>'remove')
							);
//							$data['refreshContent'] = true;
							exit(json_encode($data));
						}

					}else{
						saveLog('tryDelCours',$_GET['qs'][2]);
					}

					$_param['view'] .= "_delete";
				break;
				default:
					if ($_SESSION['accountType'] != 'campus'){
						exit('Err');
					}

					$mysqli = dbconnect();
					if (isset($_POST['titre'])) {
						//S'assurer que le cours existe...

						$_POST['user'] = checkValidateKey($_POST['user']);

						if (1){
							$req = "UPDATE cours SET nom='".$_POST['titre']."', id_user='".$_POST['user']."', type='".$_POST['type']."' WHERE id='".$_GET['qs'][2]."'";

							$mysqli->query($req);

							if ($_POST['ajax']){
							//Confirm text
								$data['confirmText'] = "Le cours a été mis à jour.";
								$data['updateDOM'] = array(array(
								    'target'=>'#courId'.$_GET['qs'][2].">h4",
								    'action'=>'update',
								    'value'=>$_POST['titre'])
								);
//								$data['refreshContent'] = true;
								exit(json_encode($data));
							}
						}else{
							//Une erreur est survenue.. Indiquer à la vue..
						}
					}

					//On a demandé quelque chose qui n'est pas «Nouveau». Charger les détails.
					$req = "SELECT * FROM cours WHERE id='".$_GET['qs'][2]."'";
					$query = $mysqli->query($req);
					$data = $query->fetch_array(MYSQLI_ASSOC);

					$mysqli->close();

					$_param['view'] .= "_edit";
				break;
			}
		break;
	}
?>