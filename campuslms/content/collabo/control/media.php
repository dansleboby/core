<?php
	if ($_POST['doSave'] == "true"){
		if ($_GET['qs'][5] == "image"){
			$cid = $_GET['qs'][1];
			$lid = $_GET['qs'][3];

			manageUpload("fichier", "data/cours/".$cid."/lecons/".$lid."/main.jpg", $_POST['fileName'.$_POST['fichieruploadNb']]);

			if ($_POST['ajax']){
			//Confirm text
				$data['confirmText'] = "Mise à jour effectuée.";
				$data['refreshContent'] = true;
				exit(json_encode($data));
			}
		}else{
			$_POST['url'];

			//Make sure it's supported, somehow!
			$good = false;
			switch($_GET['qs'][6]){
				case 'embed';
					$_POST['url'] = trim($_POST['url']);
					if (substr($_POST['url'], 0,1) == "<"){
						$good = true;
					}
				break;
				case 'heberge':
					$good = true;
				break;
			}

			if ($good){
				$req = "UPDATE cours_lecon SET media='".$_POST['url']."' WHERE id='".$_GET['qs'][3]."' AND id_cours='".$_GET['qs'][1]."'";
				$mysqli->query($req);

				if ($_POST['ajax']){
				//Confirm text
					$data['confirmText'] = "Mise à jour effectuée.";
					$data['refreshContent'] = true;
					exit(json_encode($data));
				}
			}else{
				//Err = 1?
				echo "ERR";
			}
		}
		$_param['view'] = "media";
	}else{
		$_param['view'] = "media";
	}
?>