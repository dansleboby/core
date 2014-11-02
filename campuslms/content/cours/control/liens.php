<?php
	$_param['view'] = "liens";

	//Load file here
	$req = "SELECT * FROM cours_lecon_fichiers WHERE cours_lecon_fichiers.id='".$_GET['qs'][5]."' AND cours_lecon_fichiers.id_lecon='".$_GET['qs'][3]."'";
	$query = $mysqli->query($req);
	$data['fichier'] = $query->fetch_array(MYSQLI_ASSOC);
?>