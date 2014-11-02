<?php
	$data['notifications'] = array();

	$onetime = array();

	//Load notifications (non lu)
	$mysqli = dbconnect();
	$req = "SELECT * FROM notifications WHERE id_user='".$_SESSION['user_id']."' ORDER BY lu DESC, date DESC";

	$query = $mysqli->query($req);
	while($res = $query->fetch_array(MYSQLI_ASSOC)){
		switch($res['type']){
			case 'message':
//				$req = "SELECT users.id, users.nom, users.prenom FROM messages, users WHERE messages.id='".$res['id_ref']."' AND users.id=messages.id_from";

				$req = "SELECT users.id, users.nom, users.prenom, messages.id_from FROM messages LEFT JOIN users ON messages.id_from=users.id WHERE messages.id='".$res['id_ref']."'";

				$query2 = $mysqli->query($req);
				$res2 = $query2->fetch_array(MYSQLI_ASSOC);

				if ($res['id_from'] === 0){
					$res['lid'] = 0;
					$res['nom'] = "Direction";
				}else{
					$res['lid'] = $res2['id'];
					$res['nom'] = $res2['nom'];
					$res['prenom'] = $res2['prenom'];
				}
			break;
			default:
				$res['lid'] = $res['id'];
			break;
		}
		if (!in_array($res['lid'].$res['type'], $onetime)){
			$onetime[] = $res['lid'].$res['type'];
			$data['notifications'][] = $res;
		}
	}
	$mysqli->close();
?>