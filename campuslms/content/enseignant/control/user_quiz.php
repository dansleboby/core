<?php
	$req = "SELECT * FROM cours_lecon_quizz_session WHERE id='".$_GET['qs'][5]."' AND id_user='".$_GET['qs'][3]."'";
	$query = $mysqli->query($req);
	$data = $query->fetch_array(MYSQLI_ASSOC);

	if ($req['id']){
		$res = "UPDATE cours_lecon_quizz_session SET read=1 WHERE id='".$data['id']."'";
		$mysqli->query($res);
	}
?>