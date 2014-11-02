<?php
	$mysqli = dbconnect();

	if (isset($_POST['pass'])) {
		if (getpassword($_SESSION['user_id']) == getpassword($_SESSION['user_id'], $_POST['pass'])) {
			switch($_GET['qs'][2] == 'password'){
				case 'password':
					if (strlen($_POST['pass2']) >= 8){
						if ($_POST['pass2'] == $_POST['pass1']){
							$req = "UPDATE users SET pass='".getpassword($_SESSION['user_id'], $_POST['pass2'])."' WHERE id='".$_SESSION['user_id']."'";
						}else{
							$data['err'] = "passConfirmError";
						}
					}else{
						$data['err'] = "passLengthError";
					}
				break;
				default:
					$_POST['email'] = trim(strtolower($_POST['email']));

					//Check if email changed
					$req = "SELECT email FROM users WHERE id='".$_SESSION['user_id']."'";
					$query = $mysqli->query($req);
					$res = $query->fetch_array(MYSQLI_ASSOC);

					if ($res['email'] != $_POST['email']){
						//Make sure email make sense
						$atpos = strpos($_POST['email'], "@");
						if ($atpos > 0 && strrpos($_POST['email'], ".") > $atpos){
							$req = "SELECT count(1) AS nb FROM users WHERE email='".$_POST['email']."' AND id!='".$_SESSION['user_id']."'";
							$query = $mysqli->query($req);
							$res = $query->fetch_array(MYSQLI_ASSOC);

							if ($res['nb'] == 0){
								$req = "UPDATE users SET email='".$_POST['email']."', skype='".$_POST['skype']."' WHERE id='".$_SESSION['user_id']."'";
							}else{
								$data['err'] = "emailUsed";
							}
						}else{
							$data['err'] = "emailWeird";
						}
					}else{
						$req = "UPDATE users SET skype='".$_POST['skype']."' WHERE id='".$_SESSION['user_id']."'";						
					}

//					$req = "UPDATE users SET prenom='".$_POST['prenom']."', nom='".$_POST['nom']."', email='".$_POST['email']."', skype='".$_POST['skype']."' WHERE id='".$_SESSION['user_id']."'";
				break;
			}

			if (!$data['err']){
				$mysqli->query($req);
				$data['updated'] = true;
			}
		}else{
			//Une erreur est survenue.. Indiquer à la vue..
			$data['err'] = "wrongPassword";
		}
	}

	$req = "SELECT * FROM users WHERE id='".$_SESSION['user_id']."'";
	$query = $mysqli->query($req);
	$data['user'] = $query->fetch_array(MYSQLI_ASSOC);

	$mysqli->close();
?>