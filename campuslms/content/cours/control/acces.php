<?php
	$_param['view'] = "acces";

	$req = "SELECT id FROM demandes WHERE id_cours='".$coursid."' AND id_user='".$_SESSION['user_id']."' AND etat=0";
	$query = $mysqli->query($req);
	$res = $query->fetch_array(MYSQLI_ASSOC);

	$data['action'] = 'none';

	if (!$res['id']){
		$req = "INSERT INTO demandes SET id_cours='".$coursid."', id_user='".$_SESSION['user_id']."', etat=0, date=NOW()";
		$mysqli->query($req);

		$data['action'] = 'saved';
	}else{
		$data['action'] = 'previously';
	}
?>