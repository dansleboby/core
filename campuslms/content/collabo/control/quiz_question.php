<?php
	$_param['view'] = "quiz_question";

	if ($allowCreation){
		if ($_GET['qs'][7] == 'nouvelle'){
			$_param['view'] .= "_new";

			if ($_POST['description']){
				$valeur = 0;
				if ($_POST['checkbox']){
					for($i=1;$i<=$_POST['nbRep'];$i++){
						if ($_POST['r'.$i."v"] > 0){
							$valeur += $_POST['r'.$i."v"];
						}
					}
				}else{
					$max = 0;
					for($i=1;$i<=$_POST['nbRep'];$i++){
						if ($_POST['r'.$i."v"] > $max){
							$max = $_POST['r'.$i."v"];
						}
					}

					$valeur += $max;
				}

				$nbR = 0;
				for($i=1;$i<=$_POST['nbRep'];$i++){
					if (strlen(trim($_POST['r'.$i])) > 0){
						$nbR++;
					}
				}

				if ($nbR > 0){
					$req = "INSERT INTO cours_lecon_quizz_question SET id_quizz='".$_GET['qs'][5]."', question='".$_POST['description']."', valeur='".$valeur."', date=NOW()";

					if ($_POST['melangerreponse']){
						$req .= ", randomize=1";
					}

					if ($_POST['checkbox']){
						$req .= ", multi=1";
					}

					$mysqli->query($req);
					$quid = $mysqli->insert_id;

					$_GET['qs'][7] = $mysqli->insert_id;

					//Insérer les réponses

					for($i=1;$i<=$_POST['nbRep'];$i++){
						if (strlen(trim($_POST['r'.$i])) > 0){
							$req = "INSERT INTO cours_lecon_quizz_reponse SET id_question='".$_GET['qs'][7]."', reponse='".$_POST['r'.$i]."', valeur='".$_POST['r'.$i."v"]."'";
							$mysqli->query($req);
						}
					}

					if ($_POST['fileName'.$_POST['fichieruploadNb']]){
						$cid = $_GET['qs'][1];
						$lid = $_GET['qs'][3];
						$qzid = $_GET['qs'][5];

						manageUpload("fichier", "data/cours/".$cid."/lecons/".$lid."/fichiers/quiz/".$qzid."/".$quid.".jpg", $_POST['fileName'.$_POST['fichieruploadNb']]);
					}

					$_GET['qs'][8] = 'edit';

					if ($_POST['ajax']){
					//Confirm text
						$data['confirmText'] = "La question a été sauvegardée.";
						$data['refreshContent'] = true;
						exit(json_encode($data));
					}		
				}else{
					$data['confirmText'] = "Assurez-vous d'ajouter un minimum de deux réponses à votre question.";
//					$data['refreshContent'] = true;
					exit(json_encode($data));
				}	
			}
		}else if ($_GET['qs'][7] > 0 && $_GET['qs'][8] == 'delete'){
			$keys = array();
			for($i=0;$i<=2;$i++){
				$key = date('YmdHi',strtotime('-'.$i.' min'));
				$keys[] = md5(INNER_SALT.$_GET['data'].$key.$_SESSION['user_id']);
			}

			$data['confirmkey'] = $keys[0];

			if (in_array($_POST['confirmkey'],$keys) && getpassword($_SESSION['user_id']) == getpassword($_SESSION['user_id'], $_POST['confirm'])){
				$mysqli = dbconnect();
				$req = "UPDATE cours_lecon_quizz_question SET deleted='1' WHERE id='".$_GET['qs'][7]."'";
				$mysqli->query($req);

				$data['deleted'] = 'true';

				if ($_POST['ajax']){
				//Confirm text
					$data['confirmText'] = "La question a été supprimé.";
					$data['refreshContent'] = true;
/*					$data['updateDOM'] = array(array(
					    'target'=>'#questionId'.$_GET['qs'][7],
					    'action'=>'remove'),
						array(
					    'target'=>'#quizQuestionHr'.$_GET['qs'][7],
					    'action'=>'remove')
					);*/
//						$data['refreshContent'] = true;
					exit(json_encode($data));
				}

//				saveLog('delLecon',$_GET['qs'][2]);
			}else{
//				saveLog('tryDelLecon',$_GET['qs'][2]);
			}

			$_param['view'] .= "_delete";
		}
//	if ($_GET['qs'][7] == 'nouvelle' && $_SESSION['user_level'] == 'collaborateur') {

	}

	if ($_GET['qs'][6] == 'edit' && $_SESSION['user_level'] == 'collaborateur'){
		//Edit file
		$_param['view'] .= "_edit";

		if ($_POST['titre']){
			//Update it NOW!
		}
	}

	//Load data!
?>