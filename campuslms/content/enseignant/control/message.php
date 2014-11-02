<?php
	//Si on a reçu quelque chose
	if (isset($_POST['message'])){
		$req = "SELECT id_groupe FROM formations WHERE id='".$_GET['qs'][1]."'";
		$query = $mysqli->query($req);
		$res = $query->fetch_array(MYSQLI_ASSOC);

		//Delete old message from same user
		$req = "DELETE FROM groupes_message WHERE id_groupe='".$res['id_groupe']."' AND id_user='".$_SESSION['user_id']."'";
		$mysqli->query($req);

		//Add new message

		if (strtotime($_POST['date_fin']) > 0){
			$_POST['date_fin'] = "'".$_POST['date_fin']."'";
		}else{
			$_POST['date_fin'] = "NULL";
		}

		if (strtotime($_POST['date_debut']) > 0){
			$_POST['date_debut'] = "'".$_POST['date_debut']."'";
		}else{
			$_POST['date_debut'] = "NULL";
		}

		$req = "INSERT INTO groupes_message SET id_groupe='".$res['id_groupe']."', id_user='".$_SESSION['user_id']."', message='".$_POST['message']."', date_debut=".$_POST['date_debut'].", date_fin=".$_POST['date_fin']."";

		$mysqli->query($req);


/*		$req = "UPDATE formations SET message='".$_POST['message']."' WHERE id='".$_GET['qs'][1]."'";
		$mysqli->query($req);
		$data['updated'] = true;*/

//		$data['refreshContent'] = true;
		$data['confirmText'] = "Le message est sauvegardé.";
		$data['refreshContent'] = false;
		exit(json_encode($data));
	}else if ($_GET['qs'][3] == "delete"){
		$req = "SELECT id_groupe FROM formations WHERE id='".$_GET['qs'][1]."'";
		$query = $mysqli->query($req);
		$res = $query->fetch_array(MYSQLI_ASSOC);

		$req = "DELETE FROM groupes_message WHERE id_groupe='".$res['id_groupe']."' AND id_user='".$_SESSION['user_id']."'";
		$mysqli->query($req);

		$data['confirmText'] = "Le message est supprimé.";
		$data['refreshContent'] = false;
		exit(json_encode($data));
	}

	$req = "SELECT groupes_message.* FROM groupes_message, formations WHERE groupes_message.id_groupe=formations.id_groupe AND formations.id='".$_GET['qs'][1]."' AND groupes_message.id_user='".$_SESSION['user_id']."'";
	$query = $mysqli->query($req);
	$res = $query->fetch_array(MYSQLI_ASSOC);

	$data['message'] = $res['message'];
?>