<?php
	//Basic security
	if ($_SESSION['user_level'] != 'admin' && $_SESSION['user_level'] != 'sadmin'){
		$_param['module'] = "error";
		$_param['view'] = "403";
		return;
	}

	$mysqli = dbconnect();
	if ($_SESSION['accountType'] == 'campus'){
		$req = "SELECT * FROM logs ORDER BY date DESC LIMIT 0,1000";
	}else{
		$req = "SELECT logs.* FROM logs, users WHERE users.id_cie='".$_SESSION['id_cie']."' AND logs.uid=users.id ORDER BY logs.date DESC LIMIT 0,1000";
	}
	$query = $mysqli->query($req);
	$data = array();
	while($res = $query->fetch_array(MYSQLI_ASSOC)){

		if ($res['uid'] > 0){
			$req2 = "SELECT id, email FROM users WHERE id='".$res['uid']."'";
			$query2 = $mysqli->query($req2);
			$res2 = $query2->fetch_array(MYSQLI_ASSOC);

			$res['uid'] = $res['id'];

			if ($res['uid'] > 0){
				$res['uid_text'] = $res2['email'];
				$res['uid_link'] = "admin/account#filtrer=".$res2['email'];
			}else{
				$res['uid'] = '-';
			}
		}

		switch($res['texte']){
			case 'newUser':
				$req2 = "SELECT id, email FROM users WHERE id='".$res['ref']."'";
				$query2 = $mysqli->query($req2);
				$res2 = $query2->fetch_array(MYSQLI_ASSOC);

				$res['ref'] = $res['id'];

				if ($res['ref'] > 0){
					$res['ref_text'] = $res2['email'];
					$res['ref_link'] = "admin/account#filtrer=".$res2['email'];
				}else{
					$res['ref'] = '-';
				}
			break;
			case 'newGroup':
				$req2 = "SELECT id, nom FROM groupes WHERE id='".$res['ref']."'";
				$query2 = $mysqli->query($req2);
				$res2 = $query2->fetch_array(MYSQLI_ASSOC);

				$res['ref'] = $res['id'];

				if ($res['ref'] > 0){
					$res['ref_text'] = $res2['nom'];
					$res['ref_link'] = "admin/groupes#filtrer=".$res2['nom'];
				}else{
					$res['ref'] = '-';
				}
			break;
			case 'newCours':
				$req2 = "SELECT id, nom FROM cours WHERE id='".$res['ref']."'";
				$query2 = $mysqli->query($req2);
				$res2 = $query2->fetch_array(MYSQLI_ASSOC);

				$res['ref'] = $res['id'];

				if ($res['ref'] > 0){
					$res['ref_text'] = $res2['nom'];
					$res['ref_link'] = "admin/cours#filtrer=".$res2['nom'];
				}else{
					$res['ref'] = '-';
				}
			break;
			case 'user2group':
				$req2 = "SELECT id, nom FROM groupes WHERE id='".$res['ref']."'";
				$query2 = $mysqli->query($req2);
				$res2 = $query2->fetch_array(MYSQLI_ASSOC);

				$res['ref'] = $res['id'];

				if ($res['ref'] > 0){
					$res['ref_text'] = $res2['nom'];
					$res['ref_link'] = "admin/groupes#filtrer=".$res2['nom'];
				}else{
					$res['ref'] = '-';
				}



				$req2 = "SELECT id, nom FROM groupes WHERE id='".$res['ref2']."'";
				$query2 = $mysqli->query($req2);
				$res2 = $query2->fetch_array(MYSQLI_ASSOC);

				$res['ref2'] = $res['id'];

				if ($res['ref2'] > 0){
					$res['ref2_text'] = $res2['nom'];
					$res['ref2_link'] = "admin/groupes#filtrer=".$res2['nom'];
				}else{
					$res['ref2'] = '-';
				}
			break;
			case 'formation2group':
				$req2 = "SELECT id, nom FROM cours WHERE id='".$res['ref']."'";
				$query2 = $mysqli->query($req2);
				$res2 = $query2->fetch_array(MYSQLI_ASSOC);

				$res['ref'] = $res['id'];

				if ($res['ref'] > 0){
					$res['ref_text'] = $res2['nom'];
					$res['ref_link'] = "admin/cours#filtrer=".$res2['nom'];
				}else{
					$res['ref'] = '-';
				}



				$req2 = "SELECT id, nom FROM groupes WHERE id='".$res['ref2']."'";
				$query2 = $mysqli->query($req2);
				$res2 = $query2->fetch_array(MYSQLI_ASSOC);

				$res['ref2'] = $res['id'];

				if ($res['ref2'] > 0){
					$res['ref2_text'] = $res2['nom'];
					$res['ref2_link'] = "admin/groupes#filtrer=".$res2['nom'];
				}else{
					$res['ref2'] = '-';
				}
			break;
		}

		$data[] = $res;
	}

	$mysqli->close();

	function writeLine($line, $key){
		if ($line[$key] > 0){
			if ($line[$key."_text"]){
				if ($line[$key."_link"]){
					return "<a href='".$line[$key."_link"]."'>".$line[$key."_text"]."</a>";
				}else{
					return $line[$key."_text"];
				}
			}else{
				return $line[$key];
			}
		}
	}
?>