<?php
	$_param['view'] = "ordre";

//	print_r($_POST);

	$data['req'] = array();
	if ($_SESSION['user_level'] == 'collaborateur'){
		//Reorder groupes
		for ($i=0;$i<count($_POST['data']['groupes']);$i++){
			$req = "UPDATE cours_groupe SET ordre='".$i."' WHERE id_cours='".$_GET['qs'][1]."' AND id='".$_POST['data']['groupes'][$i]."'";

			$data['req'][] = $req;

			$mysqli->query($req);
		}

		//Reorder lecons
		for ($i=0;$i<count($_POST['data']['lecons']);$i++){
			$req = "UPDATE cours_lecon SET ordre='".$i."', id_groupe='".$_POST['data']['lecons'][$i][1]."' WHERE id_cours='".$_GET['qs'][1]."' AND etat != 'deleted' AND id='".$_POST['data']['lecons'][$i][0]."'";

			$data['req'][] = $req;

			$mysqli->query($req);
		}

		if ($_POST['ajax']){
		//Confirm text
			$data['confirmText'] = "Les modifications ont été appliquées et sont effectives immédiatement.";
			$data['refreshContent'] = true;
			exit(json_encode($data));
		}else{
			$data['saved'] = true;
		}
	}
?>