<?php
	$_param['view'] = "liens";

	if ($_SESSION['user_level'] == 'collaborateur'){
		if ($_GET['qs'][5] == 'nouveau'){
			if ($_POST['titre']){
				$cid = $_GET['qs'][1];
				$lid = $_GET['qs'][3];

				//Insert file
				$req = "INSERT INTO cours_lecon_fichiers SET id_lecon='".$lid."', type='lien', nom='".$_POST['titre']."', description='".$_POST['url']."', date=NOW()";
				$mysqli->query($req);

				$fid = $mysqli->insert_id;

				if ($_POST['ajax']){
				//Confirm text
					$data['confirmText'] = "Le lien a été sauvegardée.";
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
						$data['confirmText'] = "Le lien a été supprimé.";
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
				if ($_POST['titre']){
					$req = "UPDATE cours_lecon_fichiers SET nom='".$_POST['titre']."', description='".$_POST['url']."' WHERE id='".$_GET['qs'][5]."'";
					$mysqli->query($req);

					if ($_POST['ajax']){
					//Confirm text
						$data['confirmText'] = "Le lien a été mis à jour.";
						$data['updateDOM'] = array(array(
						    'target'=>'#lienId'.$_GET['qs'][5],
						    'action'=>'update',
						    'value'=>"<strong>".$_POST['titre']."</strong>Lien externe")
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

	//Load file here
	$req = "SELECT * FROM cours_lecon_fichiers WHERE cours_lecon_fichiers.id='".$_GET['qs'][5]."' AND cours_lecon_fichiers.id_lecon='".$_GET['qs'][3]."'";
	$query = $mysqli->query($req);
	$data['fichier'] = $query->fetch_array(MYSQLI_ASSOC);
?>