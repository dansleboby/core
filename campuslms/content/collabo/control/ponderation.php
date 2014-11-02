<?php
	$_param['view'] = "ponderation";

	if ($_SESSION['user_level'] == 'collaborateur'){
		$mysqli = dbconnect();

		if ($_POST['save'] == 1){
			$approvedLecons = array();

			$req = "SELECT id, nom FROM cours_lecon WHERE cours_lecon.id_cours='".$_GET['qs'][1]."' AND etat != 'deleted' ORDER BY date ASC";
			$query = $mysqli->query($req);
			while($res = $query->fetch_array(MYSQLI_ASSOC)){
				$approvedLecons[] = $res['id'];
			}

			foreach($_POST AS $k=>$v){
				if (substr($k, 0, 10) == "valeurquiz"){
					$id = substr($k, 10);
					$id = explode("_", $id);

					if (in_array($id[1], $approvedLecons)){
						$req = "UPDATE cours_lecon_quizz SET valeur='".$v."' WHERE id_lecon='".$id[1]."' AND id='".$id[0]."'";
						$mysqli->query($req);
					}
				}else if (substr($k, 0, 12) == "valeurdevoir"){
					$id = substr($k, 12);
					$id = explode("_", $id);

					if (in_array($id[1], $approvedLecons)){
						$req = "UPDATE cours_lecon_fichiers SET valeur='".$v."' WHERE id_lecon='".$id[1]."' AND id='".$id[0]."'";
						$mysqli->query($req);
					}
				}
			}

			if ($_POST['ajax']){
			//Confirm text
				$data['confirmText'] = "Les modifications ont été appliquées et sont effectives immédiatement.";
				$data['refreshContent'] = true;
				exit(json_encode($data));
			}else{
				$data['saved'] = true;
			}
		}else{
			$data = array();

			$req = "SELECT cours_lecon.* FROM cours_lecon WHERE cours_lecon.id_cours='".$_GET['qs'][1]."' AND etat != 'deleted' ORDER BY date ASC";

			$query = $mysqli->query($req);
			while($res = $query->fetch_array(MYSQLI_ASSOC)){
				$res['quiz'] = array();
				$res['devoir'] = array();

				//Load every quiz from there!
				$req = "SELECT * FROM cours_lecon_quizz WHERE id_lecon='".$res['id']."' AND deleted=0 ORDER BY date ASC";
				$query2 = $mysqli->query($req);
				while($res2 = $query2->fetch_array(MYSQLI_ASSOC)){
					//Load every answer from there..
					$res['quiz'][] = $res2;
				}

				//Load every devoir from there!
				$req = "SELECT * FROM cours_lecon_fichiers WHERE type='devoir' AND id_lecon='".$res['id']."' ORDER BY date ASC";
				$query2 = $mysqli->query($req);
				while($res2 = $query2->fetch_array(MYSQLI_ASSOC)){
					$res['devoir'][] = $res2;
				}

				$data[] = $res;
			}
		}
	}
?>