<?php
	$_param['view'] = "fichiers";

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