<?php
	$_param['view'] = "devoirs";

	if ($_SESSION['user_level'] == 'collaborateur'){
		if ($_GET['qs'][5] == 'nouveau'){

			if ($_POST['titre']){
				$cid = $_GET['qs'][1];
				$lid = $_GET['qs'][3];

				//Insert file
				$req = "INSERT INTO cours_lecon_fichiers SET id_lecon='".$lid."', id_upload='".$_POST['fileRef'.$_POST['fichieruploadNb']]."', type='devoir', nom='".$_POST['titre']."', description='".$_POST['description']."', valeur='".$_POST['valeur']."', date=NOW()";
				$mysqli->query($req);

				$fid = $mysqli->insert_id;

				manageUpload("fichier", "data/cours/".$cid."/lecons/".$lid."/devoirs/".$fid."/fichier", $_POST['fileName'.$_POST['fichieruploadNb']]);

				if ($_POST['ajax']){
				//Confirm text
					$data['confirmText'] = "Le devoir a été sauvegardé.";
					$data['refreshContent'] = true;
					exit(json_encode($data));
				}
			}

			//New file
			$_param['view'] .= "_edit";
		}else{
			if ($_GET['qs'][6] == 'delete'){
				$keys = array();
				for($i=0;$i<=2;$i++){
					$key = date('YmdHi',strtotime('-'.$i.' min'));
					$keys[] = md5(INNER_SALT.$_GET['data'].$key.$_SESSION['user_id']);
				}

				$data['confirmkey'] = $keys[0];

				if (in_array($_POST['confirmkey'],$keys) && getpassword($_SESSION['user_id']) == getpassword($_SESSION['user_id'], $_POST['confirm'])){
					$mysqli = dbconnect();
					$req = "UPDATE cours_lecon_fichiers SET deleted='1' WHERE id='".$_GET['qs'][5]."'";
					$mysqli->query($req);

					$data['deleted'] = 'true';

					if ($_POST['ajax']){
					//Confirm text
						$data['confirmText'] = "Le devoir a été supprimé.";
						$data['updateDOM'] = array(array(
						    'target'=>'#devoirId'.$_GET['qs'][5],
						    'action'=>'remove')
						);
//						$data['refreshContent'] = true;
						exit(json_encode($data));
					}

	//				saveLog('delLecon',$_GET['qs'][2]);
				}else{
	//				saveLog('tryDelLecon',$_GET['qs'][2]);
				}

				$_param['view'] .= "_delete";	
			}else{
				if ($_POST['titre']){
					$req = "UPDATE cours_lecon_fichiers SET nom='".$_POST['titre']."', description='".$_POST['description']."', valeur='".$_POST['valeur']."' WHERE id='".$_GET['qs'][5]."'";
					$mysqli->query($req);

					if ($_POST['ajax']){
					//Confirm text
						$data['confirmText'] = "Le devoir a été mis à jour.";
						$data['updateDOM'] = array(array(
						    'target'=>'#devoirId'.$_GET['qs'][5],
						    'action'=>'update',
						    'value'=>"<strong>".$_POST['titre']."</strong>Devoir")
						);
//						$data['refreshContent'] = true;
						exit(json_encode($data));
					}
				}

				$req = "SELECT * FROM cours_lecon_fichiers WHERE id='".$_GET['qs'][5]."'";
				$query = $mysqli->query($req);
				$data = $query->fetch_array(MYSQLI_ASSOC);

				if ($_GET['qs'][6] == 'edit' && $_SESSION['user_level'] == 'collaborateur'){

					//Edit file
					$_param['view'] .= "_edit";
				}
			}
		}
	}

	//Save file!
	$data['skey'] = array();
	for ($i=0;$i<4;$i++){
		$time = strtotime(($i*-10)." minutes");
//		$time = strtotime($i*-10." minutes");

		$data['skey'][] = md5(date("YmdH",$time).implode('/',$_GET['qs']).floor(date("i",$time)/10).$_SESSION['user_id']);

	}

	//Load file here
	$req = "SELECT * FROM cours_lecon_fichiers LEFT JOIN uploadRef ON uploadRef.id=cours_lecon_fichiers.id_upload WHERE cours_lecon_fichiers.id='".$_GET['qs'][5]."' AND cours_lecon_fichiers.id_lecon='".$_GET['qs'][3]."'";
	$query = $mysqli->query($req);
	$data['fichier'] = $query->fetch_array(MYSQLI_ASSOC);

	$req = "SELECT * FROM cours_lecon_fichiers_remise LEFT JOIN uploadRef ON uploadRef.id=cours_lecon_fichiers_remise.id_upload WHERE cours_lecon_fichiers_remise.id_user='".$_SESSION['user_id']."' AND cours_lecon_fichiers_remise.id_fichier='".$_GET['qs'][5]."'";
	$query = $mysqli->query($req);
	$query = $mysqli->query($req);
	while($res = $query->fetch_array(MYSQLI_ASSOC)){
		$data['remise'][] = $query->fetch_array(MYSQLI_ASSOC);
	}

	if ($_GET['qs'][6] == 'download'){

		$file = $data['fichier']['location'];

		if (file_exists($file)) {
		    header('Content-Description: File Transfer');
		    header('Content-Type: application/octet-stream');
		    header('Content-Disposition: attachment; filename='.$data['fichier']['name']);
		    header('Content-Transfer-Encoding: binary');
		    header('Expires: 0');
		    header('Cache-Control: must-revalidate');
		    header('Pragma: public');
		    header('Content-Length: ' . filesize($file));
		    ob_clean();
		    flush();
		    readfile($file);
		    exit;
		}

		exit('ERROR 404!');
	}
?>