<?php
	$_param['view'] = "ordre";

//	print_r($_POST);

	$data['req'] = array();
	if ($_SESSION['user_level'] == 'collaborateur'){
		for ($i=0;$i<count($_POST['data']);$i++){
			if (substr($_POST['data'][$i], 0, 6) == "lienId"){
				$id = substr($_POST['data'][$i], 6);

				$req = "UPDATE cours_lecon_fichiers SET ordre='".$i."' WHERE id_lecon='".$_GET['qs'][3]."' AND id='".$id."'";
			}else if (substr($_POST['data'][$i], 0, 6) == "quizId"){
				$id = substr($_POST['data'][$i], 6);

				$req = "UPDATE cours_lecon_quiz SET ordre='".$i."' WHERE id_lecon='".$_GET['qs'][3]."' AND id='".$id."'";
			}else if (substr($_POST['data'][$i], 0, 8) == "devoirId"){
				$id = substr($_POST['data'][$i], 8);

				$req = "UPDATE cours_lecon_fichiers SET ordre='".$i."' WHERE id_lecon='".$_GET['qs'][3]."' AND id='".$id."'";
			}else if (substr($_POST['data'][$i], 0, 4) == "tpId"){
				$id = substr($_POST['data'][$i], 4);

				$req = "UPDATE cours_lecon_fichiers SET ordre='".$i."' WHERE id_lecon='".$_GET['qs'][3]."' AND id='".$id."'";
			}

			$data['req'][] = $req;

			$mysqli->query($req);
		}
		$data['saved'] = true;
	}
?>