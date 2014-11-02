<?php
	function try2login($mode, $againonerror=true){
		switch($mode){
			case 'perso':
				$mysqli = dbconnect();
				if (USE_USERCODE == "true"){
					$req = "SELECT * FROM users WHERE (email='".$_POST['login_username']."' || usercode='".$_POST['login_username']."') AND niveau != 'deleted' AND id_cie=0";
				}else{
					$req = "SELECT * FROM users WHERE email='".$_POST['login_username']."' AND niveau != 'deleted' AND id_cie=0";
				}

				$query = $mysqli->query($req);
				$res = $query->fetch_array(MYSQLI_ASSOC);

				if ($res['id'] > 0 && $res['pass'] == getpassword($res['id'], $_POST['login_password'])){
					if ($res['niveau'] != 'disabled'){
						$_SESSION['user_id'] = $res['id'];
						$_SESSION['user_nom'] = $res['prenom']." ".$res['nom'];
						$_SESSION['user_level'] = $res['niveau']; 
						$_SESSION['accountType'] = 'campus';
						$_SESSION['id_cie'] = $res['id_cie'];

						switch($res['niveau']){
							case 'admin':
							case 'sadmin':
								$_GET['data'] = "admin/index";
							break;
							case 'etudiant':
								$_GET['data'] = "resultats";
							break;
						}

						saveLog('login');
					}else{
						$logerreur = 2;

						saveLog('logErr', 0,0, $res['id'], "Email : ".$_POST['login_username']);						
					}
				}else{
					$logerreur = 1;

					saveLog('logErr', 0,0, $res['id'], "Email : ".$_POST['login_username']);
				}
			break;
			case 'cie':
				$mysqli = dbconnect();

				//Check if this user exists in the cie accounts
				if (USE_USERCODE == "true"){
					$req = "SELECT users.* FROM users, cie WHERE (users.email='".$_POST['login_username']."' || users.usercode='".$_POST['login_username']."') AND users.niveau != 'deleted' AND users.id_cie!=0 AND cie.id=users.id_cie AND cie.etat='actif'";
				}else{
					$req = "SELECT users.* FROM users, cie WHERE users.email='".$_POST['login_username']."' AND users.niveau != 'deleted' AND users.id_cie!=0 AND cie.id=users.id_cie AND cie.etat='actif'";
				}

				$query = $mysqli->query($req);
				$res = $query->fetch_array(MYSQLI_ASSOC);

				if ($res['id'] > 0 && $res['pass'] == getpassword($res['id'], $_POST['login_password'])){
					if ($res['niveau'] != 'disabled'){
						$_SESSION['user_id'] = $res['id'];
						$_SESSION['user_nom'] = $res['prenom']." ".$res['nom'];
						$_SESSION['user_level'] = $res['niveau']; 
						$_SESSION['accountType'] = 'cie';
						$_SESSION['id_cie'] = $res['id_cie'];

						switch($res['niveau']){
							case 'admin':
								$_GET['data'] = "admin/index";
//								$_GET['data'] = "enseignant/index";
							break;
							case 'etudiant':
								$_GET['data'] = "resultats";
							break;
						}

						saveLog('login');

					}else{
						$logerreur = 2;

						saveLog('cielogErr', 0,0, $res['id'], "Email : ".$_POST['login_username']);						
					}
				}else{
					$logerreur = 1;

					saveLog('cielogErr', 0,0, $res['id'], "Email : ".$_POST['login_username']);
				}
			break;
			default:
				exit('Err 1');
			break;
		}

		if ($logerreur){
			if ($againonerror){
				if ($mode == "perso"){
					return try2login("cie",false);
				}else{
					return try2login("perso",false);
				}
			}else{
				return $logerreur;
			}
		}
	}

	if (!$_SESSION['user_id'] && isset($_POST['login_password']) && isset($_POST['login_username'])) {
		//Check if the user is loggedin

		$logerreur = try2login($_POST['loginmode']);
	}
?>