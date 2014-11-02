<?php
	$_param['view'] = "groupe";

	if ($_SESSION['user_level'] == 'collaborateur'){
		$mysqli = dbconnect();

		if ($_POST['save'] == 1){
			$approvedLecons = array();

			if (strlen($_POST['nom']) > 0){
				if ($_GET['qs'][3]){
					$req = "UPDATE cours_groupe SET nom='".$_POST['nom']."' WHERE id='".$_GET['qs'][3]."' AND id_cours='".$_GET['qs'][1]."'";
				}else{
					$req = "INSERT INTO cours_groupe SET id_cours='".$_GET['qs'][1]."', nom='".$_POST['nom']."', ordre='99'";
				}

				$mysqli->query($req);

				if ($_POST['ajax']){
				//Confirm text
					$data['confirmText'] = "Les modifications ont été appliquées et sont effectives immédiatement.";
					$data['refreshContent'] = true;
					exit(json_encode($data));
				}else{
					$data['saved'] = true;
				}
			}else{
				$data['error'] = 'noName';
			}
		}else if($_GET['qs'][4] == "delete"){
			$req = "DELETE FROM cours_groupe WHERE id='".$_GET['qs'][3]."' AND id_cours='".$_GET['qs'][1]."'";

			$query = $mysqli->query($req);

			if ($_POST['ajax']){
			//Confirm text
				$data['confirmText'] = "Mise à jour effectuée.";
				$data['refreshContent'] = true;
				exit(json_encode($data));
			}
		}else{
			$data = array();

			$req = "SELECT * FROM cours_groupe WHERE id='".$_GET['qs'][3]."' AND id_cours='".$_GET['qs'][1]."'";

			$query = $mysqli->query($req);
			$data = $query->fetch_array(MYSQLI_ASSOC);
		}
	}
?>