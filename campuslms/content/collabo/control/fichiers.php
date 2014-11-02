<?php
	$_param['view'] = "fichiers";

	if ($_SESSION['user_level'] == 'collaborateur'){
		if ($_POST['titre'] == '')
			$_POST['titre'] = 'Fichier';


		if ($_GET['qs'][5] == 'nouveau'){

			if ($_POST['nom']){
				$cid = $_GET['qs'][1];
				$lid = $_GET['qs'][3];

				//Insert file
				$req = "INSERT INTO cours_lecon_fichiers SET id_lecon='".$lid."', id_upload='".$_POST['fileRef'.$_POST['fichieruploadNb']]."', type='fichier', titre='".$_POST['titre']."', nom='".$_POST['nom']."', description='".$_POST['description']."', date=NOW()";
				$mysqli->query($req);

				$fid = $mysqli->insert_id;

				manageUpload("fichier", "data/cours/".$cid."/lecons/".$lid."/fichiers/".$fid, $_POST['fileName'.$_POST['fichieruploadNb']]);

				if ($_POST['ajax']){
				//Confirm text
					$data['confirmText'] = "Le fichier a été sauvegardé.";
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

	//				saveLog('delLecon',$_GET['qs'][2]);

					if ($_POST['ajax']){
					//Confirm text
						$data['confirmText'] = "Le fichier a été supprimé.";
						$data['updateDOM'] = array(array(
						    'target'=>'#lienId'.$_GET['qs'][5],
						    'action'=>'remove')
						);
//						$data['refreshContent'] = true;
						exit(json_encode($data));
					}

				}else{
	//				saveLog('tryDelLecon',$_GET['qs'][2]);
				}

				$_param['view'] .= "_delete";	
			}else{
				if ($_POST['nom']){
					$req = "UPDATE cours_lecon_fichiers SET titre='".$_POST['titre']."', nom='".$_POST['nom']."', description='".$_POST['description']."' WHERE id='".$_GET['qs'][5]."'";
					$mysqli->query($req);

					if ($_POST['ajax']){
					//Confirm text
						$data['confirmText'] = "Le fichier a été mis à jour.";
						$data['updateDOM'] = array(array(
						    'target'=>'#lienId'.$_GET['qs'][5],
						    'action'=>'update',
						    'value'=>"<strong>".$_POST['nom']."</strong>".$_POST['titre'])
						);
//						$data['refreshContent'] = true;
						exit(json_encode($data));
					}
				}

				$req = "SELECT * FROM cours_lecon_fichiers WHERE id='".$_GET['qs'][5]."'";
				$query = $mysqli->query($req);
				$res = $query->fetch_array(MYSQLI_ASSOC);

				if ($_GET['qs'][6] == 'edit' && $_SESSION['user_level'] == 'collaborateur'){
					//Edit file
					$_param['view'] .= "_edit";
				}
			}
		}
	}

	//Load file here
	$req = "SELECT * FROM cours_lecon_fichiers LEFT JOIN uploadRef ON uploadRef.id=cours_lecon_fichiers.id_upload WHERE cours_lecon_fichiers.id='".$_GET['qs'][5]."' AND cours_lecon_fichiers.id_lecon='".$_GET['qs'][3]."'";
	$query = $mysqli->query($req);
	$data['fichier'] = $query->fetch_array(MYSQLI_ASSOC);

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