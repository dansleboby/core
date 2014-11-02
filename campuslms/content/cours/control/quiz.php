<?php
	$_param['view'] = "quiz";

	if ($formationetat != 'actif'){
		exit('');
	}

	$_req['js'][] = "campuslms/content/".$_param['module']."/js/quiz.js";		

	//Load data!
	$req = "SELECT * FROM cours_lecon_quizz WHERE id='".$_GET['qs'][5]."' AND id_lecon='".$_GET['qs'][3]."'";
	$query = $mysqli->query($req);
	$data['quiz'] = $query->fetch_array(MYSQLI_ASSOC);

	//Check if already filled

	$req2 = "SELECT pointage, valeur, datefin FROM cours_lecon_quizz_session WHERE id_user='".$_SESSION['user_id']."' AND id_quiz='".$data['quiz']['id']."' ORDER BY id DESC";
	$query2 = $mysqli->query($req2);
	$res2 = $query2->fetch_array(MYSQLI_ASSOC);

    $data['quiz']['pointage'] = $res2['pointage'];
    $data['quiz']['valeur'] = $res2['valeur'];
    $data['quiz']['done'] = $res2['datefin'];

    if ($data['quiz']['done'] && $data['quiz']['refaire'] == 1 && $_GET['qs'][6] == 'redo'){
		$data['quiz']['alert'] = "[Vous avez déjà fait ce quiz dans le passé. Seul votre premier résultat est comptabilisé dans votre note.]";
		$data['quiz']['done'] = null;
	}

	if ($_GET['qs'][6] == 'review' && $data['quiz']['voir']){
		$data['review'] = array();
		$req2 = "SELECT * FROM cours_lecon_quizz_session WHERE id_user='".$_SESSION['user_id']."' AND id_quiz='".$_GET['qs'][5]."' ORDER BY datedebut ASC";
		$query2 = $mysqli->query($req2);
		while($res2 = $query2->fetch_array(MYSQLI_ASSOC)){
			$res2['details'] = array();
			$req3 = "SELECT cours_lecon_quizz_session_reponse.id_question, cours_lecon_quizz_session_reponse.valeur, cours_lecon_quizz_question.question, cours_lecon_quizz_reponse.reponse FROM cours_lecon_quizz_session_reponse, cours_lecon_quizz_question, cours_lecon_quizz_reponse WHERE cours_lecon_quizz_session_reponse.id_session='".$res2['id']."' AND cours_lecon_quizz_question.id=cours_lecon_quizz_session_reponse.id_question AND cours_lecon_quizz_reponse.id=cours_lecon_quizz_session_reponse.id_reponse ORDER BY cours_lecon_quizz_session_reponse.id_question ASC";

			$query3 = $mysqli->query($req3);

			while($res3 = $query3->fetch_array(MYSQLI_ASSOC)){
				$res2['details'][] = $res3;
			}

			$data['review'][] = $res2;
		}

		$_param['view'] .= "_review";
    }else if ($data['quiz']['done'] && 1){
    	//quiz déjà fait.

    }else{
		//Save data
		$data['datestart'] = time();
		if ($_POST['datestart']){
			$data['datestart'] = $_POST['datestart'];
		}


		$data['skey'] = array();
		for ($i=0;$i<3;$i++){
			$time = strtotime(($i*-1)." hours");

			$data['skey'][] = md5(date("HYmd",$time).implode('/',$_GET['qs']).$_SESSION['user_id'].$data['datestart']);
		}




		//Load questions
		$data['questions'] = array();

		$req = "SELECT * FROM cours_lecon_quizz_question WHERE id_quizz='".$_GET['qs'][5]."' AND deleted != 1 ORDER BY id ASC";
		$query = $mysqli->query($req);
		while($res = $query->fetch_array(MYSQLI_ASSOC)){
			//Load reponses
			$res['reponses'] = array();

			$req = "SELECT * FROM cours_lecon_quizz_reponse WHERE id_question='".$res['id']."' ORDER BY id ASC";
			$query2 = $mysqli->query($req);
			while($res2 = $query2->fetch_array(MYSQLI_ASSOC)){
				$res['reponses'][] = $res2;
			}

			//Reorder answer if needed
			if ($res['randomize']){
				$res['reponses'] = shuffle_assoc($res['reponses']);
			}

			$data['questions'][] = $res;
		}







		if ($_POST['datestart']) {
			if (in_array($_POST['skey'],$data['skey'])) {
				$points = array();

				$req = "INSERT INTO cours_lecon_quizz_session SET id_user='".$_SESSION['user_id']."', id_formation='".$formationid."', id_quiz='".$_GET['qs'][5]."', datedebut='".date('Y-m-d H:i:s',$_POST['datestart'])."', datefin=NOW()";
				$mysqli->query($req);
				$sid = $mysqli->insert_id;

				//Arrange answer
				$answers = array();
				foreach($_POST AS $k=>$v){
					if (substr($k, 0,1) == "q"){
						$qid = substr($k, 1);

						if (is_array($v)){
							foreach($v AS $v2){
								$answers[] = array($qid, $v2);
							}
						}else{
							$answers[] = array($qid, $v);
						}
					}
				}

				//Saving answer!
				foreach($answers AS $v){
					$qid = $v[0];
					$v = $v[1];

					$valeur = null;

					//Calculer points (question)
					$q = $data['questions'][$qid];

					foreach($q['reponses'] AS $r){
						if ($r['id'] == $v){
							$valeur = $r['valeur'];
							$points[0] += $valeur;
						}
					}

					if ($valeur !== null){
						$req = "INSERT INTO cours_lecon_quizz_session_reponse SET id_session='".$sid."', id_question='".$q['id']."', id_reponse='".$v."', valeur='".$valeur."'";
						$mysqli->query($req);
					}

				}

				//Calculer max
				foreach($data['questions'] AS $res){
					$points[1] += $res['valeur'];
				}

				saveLog("remiseQuiz", $_GET['qs'][5], $formationid);

	//			$req = "INSERT INTO notes SET id_user='".$_SESSION['user_id']."', id_ref='".$_GET['qs'][5]."', ref_type='quiz', note='".$points[0]."', max='".$points[1]."'";
				$req = "UPDATE cours_lecon_quizz_session SET pointage='".max(0,$points[0])."', valeur='".$points[1]."' where id='".$sid."'";
				$mysqli->query($req);

				//Check if a note has been edited for this
				$req = "SELECT * FROM notes WHERE ref_type='quiz' AND id_user='".$_SESSION['user_id']."' AND id_ref='".$_GET['qs'][5]."'";
				$query2 = $mysqli->query($req);
				$res = $query2->fetch_array(MYSQLI_ASSOC);

				if ($res['id']){
					//If it's smaller than the new one, delete it !
					if ($res['note'] < $points[0]){
						$req = "DELETE FROM notes WHERE id='".$res['id']."'";
						$mysqli->query($req);
					}
				}

			    $data['quiz']['pointage'] = max(0,$points[0]);
			    $data['quiz']['valeur'] = $points[1];
			    $data['quiz']['done'] = 1;

	/*			foreach($_POST AS $k=>$v){
					if (substr($k, 0,1) == "q"){
						$qid = substr($k, 1);
						$valeur = null;

						$q = $data['questions'][$qid];
						foreach($q['reponses'] AS $r){
							if ($r['id'] == $v){
								$valeur = $r['valeur'];
							}
						}

						if ($valeur !== null){
							$req = "INSERT INTO cours_lecon_quizz_session_reponse SET id_session='".$sid."', id_question='".$qid."', id_reponse='".$v."', valeur='".$valeur."'";
							$mysqli->query($req);

							echo $req;
						}
					}
				}*/
				$data['quiz']['saved'] = true;
			}else{
				$data['quiz']['error'] = true;
			}
		}else{
			//Reorder questions if needed
			if ($data['quiz']['randomize']){
				$data['questions'] = shuffle_assoc($data['questions']);
			}

			saveLog("openedQuiz", $_GET['qs'][5], $formationid);
		}
	}
?>