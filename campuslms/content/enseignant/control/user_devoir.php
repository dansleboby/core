<?php
	$req = "SELECT * FROM cours_lecon_fichiers_remise WHERE id='".$_GET['qs'][5]."' AND id_user='".$_GET['qs'][3]."'";
	$query = $mysqli->query($req);
	$data = $query->fetch_array(MYSQLI_ASSOC);

	if ($data['id']){
		if ($_POST['note']){
//			$req = "UPDATE cours_lecon_fichiers_remise SET `read`=1, note='".$_POST['note']."', dateNote=NOW() WHERE id='".$data['id']."'";
			$req = "UPDATE cours_lecon_fichiers_remise SET `read`=1, note='".$_POST['note']."', dateNote=NOW() WHERE id='".$data['id']."'";

			$mysqli->query($req);

			if ($_POST['ajax']){
			//Confirm text
				$data['confirmText'] = "La note a été sauvegardé.";
				$data['refreshContent'] = true;
				exit(json_encode($data));
			}
		}

		$req = "SELECT * FROM uploadRef WHERE id='".$data['id_upload']."'";
		$query = $mysqli->query($req);
		$data['file'] = $query->fetch_array(MYSQLI_ASSOC);

		if ($_GET['qs'][6] == "download"){
			header('Content-Type: application/octet-stream');
			header("Content-Transfer-Encoding: Binary"); 
			header("Content-disposition: attachment; filename=\"" . $data['file']['name'] . "\""); 


			echo file_get_contents($data['file']['location']);
			exit();
		}
	}
?>