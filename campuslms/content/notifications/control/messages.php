<?php
if ($_GET['qs'][2] == "new"){
	//Load user list
	$data['user'] = array();

	$mysqli = dbconnect();
	$req = "SELECT DISTINCT formations.id_user, users.* FROM users, formations, groupe_users WHERE groupe_users.id_user='".$_SESSION['user_id']."' AND formations.id_groupe=groupe_users.id_groupe AND users.id=formations.id_user ORDER BY users.nom ASC";
	$query = $mysqli->query($req);
	while($res = $query->fetch_array(MYSQLI_ASSOC)){
		$data['user'][] = $res;
	}
	$mysqli->close();

	$_param['view'] = "messages_new";
}else if ($_GET['qs'][2] >= 0){
	$data['messages'] = array();

	$mysqli = dbconnect();

	//Make sure the user CAN contact this user
	if ($_GET['qs'][2] == '0'){
		$data['user'] = array();
		$data['user']['nom'] = "Direction";
	}else{
		$req = "SELECT * FROM users WHERE id='".$_GET['qs'][2]."'";
		$query = $mysqli->query($req);
		$res = $query->fetch_array(MYSQLI_ASSOC);		

		$data['user'] = $res;
	}

	if (1){
		if ($_POST['theMessage']){
			//Save message
			$req = "INSERT INTO messages SET id_from='".$_SESSION['user_id']."', id_to='".$_GET['qs'][2]."', text='".$_POST['theMessage']."', date=NOW()";
			$mysqli->query($req);
			$iid = $mysqli->insert_id;

			//Save notification
			$req = "INSERT INTO notifications SET id_user='".$_GET['qs'][2]."', id_ref='".$iid."', type='message', date=NOW()";
			$mysqli->query($req);
		}

		$_POST['id_user'] = $_GET['qs'][2];
		require(dirname(__FILE__).'/../../../core/ajax/chatUpdate.php');
		$data['messages'] = $res;
	}
}
?>