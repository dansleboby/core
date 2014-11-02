<?php
	$_param['view'] = "quiz";

	if ($_GET['qs'][6] == 'question' && $_SESSION['user_level'] == 'collaborateur') {
		require(dirname(__FILE__)."/question.php");
	}else if ($_GET['qs'][5] == 'nouveau' && $_SESSION['user_level'] == 'collaborateur'){
		//New file
		$_param['view'] .= "_new";

		if ($_POST['titre']){
			$req = "INSERT INTO cours_lecon_quizz SET id_lecon='".$_GET['qs'][3]."', nom='".$_POST['titre']."', description='".$_POST['description']."', date=NOW()";
			$mysqli->query($req);

			$_GET['qs'][5] = $mysqli->insert_db();
			$_GET['qs'][6] = 'edit';

			if ($_POST['ajax']){
			//Confirm text
				$data['confirmText'] = "Mise à jour effectuée.";
				$data['refreshContent'] = true;
				exit(json_encode($data));
			}			
		}
	}

	if ($_GET['qs'][6] == 'edit' && $_SESSION['user_level'] == 'collaborateur'){
		//Edit file
		$_param['view'] .= "_edit";

		if ($_POST['titre']){
			//Update it NOW!
		}
	}

	//Load data!

	if ($_SESSION['user_level'] == 'collaborateur'){
		$allowCreation = true;
		$_req['js'][] = "campuslms/content/".$_param['module']."/js/quiz_edit.js";		
	}else{
		//Vérifier l'état de la leçon 
	}

?>