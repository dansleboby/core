<?php
	$data['review'] = array();
	$req = "SELECT * FROM cours_lecon_quizz_session WHERE id_user='".$_GET['qs'][3]."' AND id_quiz='".$_GET['qs'][5]."' ORDER BY datedebut ASC";
	$query = $mysqli->query($req);
	while($res = $query->fetch_array(MYSQLI_ASSOC)){
		$reponseRecues = array();

		$req2 = "SELECT * FROM cours_lecon_quizz_session_reponse WHERE id_session='".$res['id']."'";
		$query2 = $mysqli->query($req2);
		while($res2 = $query2->fetch_array(MYSQLI_ASSOC)){
			$reponseRecues[$res2['id_reponse']] = $res2;
		}

		$res['questions'] = array();

		$req2 = "SELECT * FROM cours_lecon_quizz_question WHERE id_quizz='".$_GET['qs'][5]."' ORDER BY id ASC";
		$query2 = $mysqli->query($req2);
		while($res2 = $query2->fetch_array(MYSQLI_ASSOC)){
			$res2['reponses'] = array();

			$req3 = "SELECT * FROM cours_lecon_quizz_reponse WHERE id_question='".$res2['id']."' ORDER BY id ASC";

			$query3 = $mysqli->query($req3);
			while($res3 = $query3->fetch_array(MYSQLI_ASSOC)){
				if ($reponseRecues[$res3['id']]){
					$res3['checked'] = 'checked';
					$res3['valeur'] = $reponseRecues[$res3['id']]['valeur'];
				}
				$res2['reponses'][] = $res3;
			}

			$res['questions'][] = $res2;
		}

		$data['review'][] = $res;
	}
?>