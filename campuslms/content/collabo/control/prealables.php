<?php
	$_param['view'] = "prealables";

	$data = array();

	switch($_GET['qs'][5]){
		case '':
			//Nothing - Home screen
			$req = "SELECT cours_lecon_prealable.*, cours_lecon.nom FROM cours_lecon_prealable, cours_lecon WHERE cours_lecon_prealable.id_lecon='".$_GET['qs'][3]."' AND cours_lecon.id=cours_lecon_prealable.id_prealable ORDER BY cours_lecon.id ASC";
			$query = $mysqli->query($req);
			while($res = $query->fetch_array(MYSQLI_ASSOC)){
				$data[] = $res;
			}
		break;
		case 'delete':
			//Supprimer le préalable
			$req = "DELETE FROM cours_lecon_prealable WHERE id='".$_GET['qs'][6]."' AND id_lecon='".$_GET['qs'][3]."'";
			$mysqli->query($req);

			if ($_POST['ajax']){
			//Confirm text
				$data['confirmText'] = "Mise à jour effectuée.";
				$data['refreshContent'] = true;
				exit(json_encode($data));
			}
		break;
		case 'nouveau':
			//Nouveau - Show form!
		default:
			//we got something. An ID ?

			if ($_POST['prealableid']){
				//Save in DB
				if ($_POST['prealableid'] > 0){
					$data['confirmText'] = "Sauvegarde effectuée.";
					$req = "UPDATE cours_lecon_prealable SET id_prealable='".$_POST['leconid']."', cond='".$_POST['cond']."', date=NOW() WHERE id='".$_POST['prealableid']."' AND id_lecon='".$_GET['qs'][3]."'";
				}else{
					$data['confirmText'] = "Mise à jour effectuée.";
					$req = "INSERT INTO cours_lecon_prealable SET id_lecon='".$_GET['qs'][3]."', id_prealable='".$_POST['leconid']."', cond='".$_POST['cond']."', date=NOW()";
				}
				$mysqli->query($req);

				if ($_POST['ajax']){
					$data['refreshContent'] = true;
					exit(json_encode($data));
				}
			}

			if ($_GET['qs'][5] > 0){
				$req = "SELECT *, id_lecon AS leconid FROM cours_lecon_prealable WHERE id='".$_GET['qs'][5]."'";
				$query = $mysqli->query($req);
				$data = $query->fetch_array(MYSQLI_ASSOC);
			}

			//Load all leçons
			$data['lecons'] = array();
			$req = "SELECT * FROM cours_lecon WHERE id_cours='".$_GET['qs'][1]."' AND etat != 'deleted' ORDER BY id ASC";
			$query = $mysqli->query($req);
			$i = 0;
			while($res = $query->fetch_array(MYSQLI_ASSOC)){
				$i++;
				if ($res['id'] != $_GET['qs'][3]){
					$res['nb'] = $i;
					$data['lecons'][] = $res;
				}
			}

			$_param['view'] = "prealables_edit";
		break;
	}
?>