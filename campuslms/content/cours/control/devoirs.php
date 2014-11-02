<?php
	$_param['view'] = "devoirs";

	if ($formationetat != 'actif'){
		exit('');
	}

	//Save file!
	$data['skey'] = array();
	for ($i=0;$i<4;$i++){
		$time = strtotime(($i*-10)." minutes");
//		$time = strtotime($i*-10." minutes");

		$data['skey'][] = md5(date("YmdH",$time).implode('/',$_GET['qs']).floor(date("i",$time)/10).$_SESSION['user_id']);

	}

	if ($_POST['fileName'.$_POST['fichieruploadNb']] && ($_GET['qs'][6] == "upload")){
		if (in_array($_POST['skey'],$data['skey'])){
			$cid = $coursid;
			$lid = $_GET['qs'][3];
			$fid = $_GET['qs'][5];

			//Insert file
			$req = "INSERT INTO cours_lecon_fichiers_remise SET id_user='".$_SESSION['user_id']."', id_formation='".$formationid."', id_fichier='".$fid."', id_upload='".$_POST['fileRef'.$_POST['fichieruploadNb']]."', description='".$_POST['description']."', note=NULL, date=NOW()";
			$mysqli->query($req);

			$sid = $mysqli->insert_id;

			manageUpload("fichier", "data/cours/".$cid."/lecons/".$lid."/devoirs/".$fid."/".$sid, $_POST['fileName'.$_POST['fichieruploadNb']]);

			saveLog("remiseFichier", $fid, $formationid);

			$data['saved'] = true;
		}else{
			$data['error'] = true;
		}
	}

	//Load file here
	$req = "SELECT * FROM cours_lecon_fichiers LEFT JOIN uploadRef ON uploadRef.id=cours_lecon_fichiers.id_upload WHERE cours_lecon_fichiers.id='".$_GET['qs'][5]."' AND cours_lecon_fichiers.id_lecon='".$_GET['qs'][3]."'";
	$query = $mysqli->query($req);
	$data['fichier'] = $query->fetch_array(MYSQLI_ASSOC);

	$req = "SELECT * FROM cours_lecon_fichiers_remise LEFT JOIN uploadRef ON uploadRef.id=cours_lecon_fichiers_remise.id_upload WHERE cours_lecon_fichiers_remise.id_user='".$_SESSION['user_id']."' AND cours_lecon_fichiers_remise.id_fichier='".$_GET['qs'][5]."' ORDER BY cours_lecon_fichiers_remise.id DESC";

//	echo $req;
	$query = $mysqli->query($req);
	$query = $mysqli->query($req);
	while($res = $query->fetch_array(MYSQLI_ASSOC)){
		$data['remise'][] = $res;
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