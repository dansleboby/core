<?php
	$_param['view'] = "quiz";

	$_req['js'][] = "campuslms/content/".$_param['module']."/js/quiz.js";		

	if ($allowCreation){
		$_req['js'][] = "campuslms/content/".$_param['module']."/js/quiz_edit.js";		

		if ($_GET['qs'][6] == 'question'){
			require(dirname(__FILE__)."/quiz_question.php");
		}

		if ($_GET['qs'][5] == 'nouveau'){
			//New file

			if ($_POST['titre']){
				$req = "INSERT INTO cours_lecon_quizz SET id_lecon='".$_GET['qs'][3]."', nom='".$_POST['titre']."', description='".$_POST['description']."', valeur='".$_POST['valeur']."', date=NOW()";
				if ($_POST['melangerquestion']){
					$req .= ", randomize=1";
				}
				if ($_POST['voirreponses']){
					$req .= ", voir=1";
				}
				if ($_POST['refaire']){
					$req .= ", refaire=1";
				}

				$mysqli->query($req);

				$_GET['qs'][5] = $mysqli->insert_id;
				$_GET['qs'][6] = 'edit';

				if ($_POST['ajax']){
				//Confirm text
					$data['confirmText'] = "Le quiz a été sauvegardé.";
					$data['refreshContent'] = true;
					exit(json_encode($data));
				}

			}else{
				$_param['view'] .= "_edit";
			}
		}

		if ($_GET['qs'][6] == 'delete'){
			$keys = array();
			for($i=0;$i<=2;$i++){
				$key = date('YmdHi',strtotime('-'.$i.' min'));
				$keys[] = md5(INNER_SALT.$_GET['data'].$key.$_SESSION['user_id']);
			}

			$data['confirmkey'] = $keys[0];

			if (in_array($_POST['confirmkey'],$keys) && getpassword($_SESSION['user_id']) == getpassword($_SESSION['user_id'], $_POST['confirm'])){

				$mysqli = dbconnect();
				$req = "UPDATE cours_lecon_quizz SET deleted='1' WHERE id='".$_GET['qs'][5]."'";
				$mysqli->query($req);

				$data['deleted'] = 'true';
//				saveLog('delLecon',$_GET['qs'][2]);

				if ($_POST['ajax']){
				//Confirm text
					$data['confirmText'] = "Le quiz a été supprimé.";
					$data['goTo'] = 'cours/'.$_GET['qs'][1]."/lecon/".$_GET['qs'][3];
//					$data['refreshContent'] = true;
					exit(json_encode($data));
				}
			}else{
//				saveLog('tryDelLecon',$_GET['qs'][2]);
			}

			$_param['view'] .= "_delete";	
		}else if ($_GET['qs'][6] == 'edit'){
			//Edit file
			$_param['view'] .= "_edit";

			if ($_POST['titre']){
				//Update it NOW!
				$randomize = 0;
				if ($_POST['melangerquestion']){
					$randomize = 1;
				}
				$voir = 0;
				if ($_POST['voirreponses']){
					$voir = 1;
				}
				$refaire = 0;
				if ($_POST['refaire']){
					$refaire = 1;
				}

				$req = "UPDATE cours_lecon_quizz SET nom='".$_POST['titre']."', description='".$_POST['description']."', valeur='".$_POST['valeur']."', randomize='".$randomize."', voir='".$voir."', refaire='".$refaire."' WHERE id='".$_GET['qs'][5]."'";

				$mysqli->query($req);

				$data['saved'] = true;

				if ($_POST['ajax']){
				//Confirm text
					$data['confirmText'] = "Le quiz a été mis à jour.";
					$data['refreshContent'] = true;
					exit(json_encode($data));
				}
			}
		}
	}

	//Load data!
	$req = "SELECT * FROM cours_lecon_quizz WHERE id='".$_GET['qs'][5]."' AND id_lecon='".$_GET['qs'][3]."'";
	$query = $mysqli->query($req);
	$data['quiz'] = $query->fetch_array(MYSQLI_ASSOC);

	if ($_GET['qs'][6] == 'review' && $data['quiz']['voir']){
		$data['review'] = array();
		$req2 = "SELECT * FROM cours_lecon_quizz_session WHERE id_user='".$_SESSION['user_id']."' AND id_quiz='".$_GET['qs'][5]."' ORDER BY datedebut ASC";
		$query2 = $mysqli->query($req2);
		while($res2 = $query2->fetch_array(MYSQLI_ASSOC)){
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
//			if ($res['randomize']){
//				$res['reponses'] = shuffle_assoc($res['reponses']);
//			}

			$data['questions'][] = $res;
		}


		//Reorder questions if needed
//		if ($data['quiz']['randomize']){
//			$data['questions'] = shuffle_assoc($data['questions']);
//		}
	}
?>