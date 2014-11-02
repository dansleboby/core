<?php
	$_param['view'] = "account_import";

	$data = array();

	if (isset($_POST['csv']) || $_POST['odatai'] > 0) {
//		$odata = explode(PHP_EOL,$_POST['csv']);
//		$odata = preg_split('/[\n\r]+/', $_POST['csv']);
//		$odata = trim($_POST['_csv_']);
		$odata = trim($_ORI['POST']['csv']);
		$odata = explode("\n", $odata);
		$odata = array_filter($odata, 'trim'); // remove any extra \r characters left behind

		$data['min'] = 256;
		$data['max'] = 0;

		$data['data'] = array();
		if ($_POST['odatai']){
			for ($i=0;$i<$_POST['odatai'];$i++){
				$temp = array();
				for ($j=0;$j<$_POST['odataj'];$j++){
					$temp[$j] = $_POST['odata_'.$i.'_'.$j];
				}
				$data['data'][] = $temp;
			}
			$data['min'] = $_POST['odataj'];
			$data['max'] = $_POST['odataj'];
		}else{
			$i = 0;
			foreach($odata AS $line){
				$line = str_getcsv($line, $_POST['delimiter'], $_POST['enclosure'], $_POST['escape']);

				$nb = count($line);

				if ($nb > 1){
					if ($data['min'] > $nb){
						$data['min'] = $nb;
					}

					if ($data['max'] < $nb){
						$data['max'] = $nb;
					}

					$data['data'][] = $line;
					$j = 0;
					foreach($line AS $res){
						$_POST['odata_'.$i.'_'.$j] = $res;
						$j++;
					}
				}
				$i++;
			}
			$_POST['odatai'] = count($data['data']);
			$_POST['odataj'] = count($data['data'][0]);
		}
	}

	if ($_POST['importstep'] == 2 || $_POST['importstep'] == 3){
		$typePos = array();
		$typePos['usercode'] = -1;
		$typePos['email'] = -1;
		$typePos['password'] = -1;
		$typePos['firstname'] = -1;
		$typePos['lastname'] = -1;

		$types = array();
		for ($i=0;$i<$data['max'];$i++){
			$types[$_POST['data'.$i.'type']]++;
			$typePos[$_POST['data'.$i.'type']] = $i;
		}

		//Make sure each type of field is only selected ONCE & required fields are there
		if (($types['usercode'] == 1 || $types['email'] == 1) && $types['password'] == 1 && $types['firstname'] <= 1 && $types['lastname'] <= 1) {


			//Everything is fine. Create new array to hold data
			$data['malformed'] = array();
			$data['exists'] = array();

			if (!$_POST['useUserNb']){
				$_POST['useUserNb'] = 0;				
			}

			$mysqli = dbconnect();
			foreach($data['data'] AS $i=>$res){
				if ($_POST['data'.$i.'Action']){
					switch($_POST['data'.$i.'Action']){
						case 'delete':
							$_POST["line".$i."Enabled"] = 0;
						break;
						case 'fixmail':
							//Save the new mail NOW !
							$_POST['replacementEmaili'.$i] = "TODO";
						break;
						default:
							if (substr($_POST['data'.$i.'Action'], 0,7) == 'useUser'){
								$_POST['useUser'.$_POST['useUserNb']] = substr($_POST['data'.$i.'Action'], 7);
								$_POST['useUserNb'] = $_POST['useUserNb']+1;
								$_POST["line".$i."Enabled"] = 0;
							}
						break;
					}
				}
				//Check every email
				if ($_POST["line".$i."Enabled"] == 1){
					if ($typePos['email'] > -1){
						if (isset($_POST['replacementEmaili'.$i])) {
							$mail = strtolower($_POST['replacementEmaili'.$i]);
						}else{
							$mail = strtolower($res[$typePos['email']]);
						}

						if (strpos($mail, "@") > 0 && strrpos($mail, ".") > 2) {
							$req = "SELECT id FROM users WHERE email='".$mail."'";
							$query = $mysqli->query($req);
							$res = $query->fetch_array(MYSQLI_ASSOC);

							if ($res['id']){
								if (!isset($_POST['itexistsi'.$i])) {
									$data['exists'][$i] = array('email'=>$res['id']);
								}
							}
						}else{
							$data['malformed'][$i] = 'email';
						}
					}

					if ($typePos['usercode'] > -1){
/*						if (isset($_POST['replacementCodei'.$i])){
							$code = strtolower($_POST['replacementCodei'.$i]);
						}else{
							$code = strtolower($res[$typePos['usercode']]);
						}*/

						if (strlen($code) > 0){
							$req = "SELECT id FROM users WHERE usercode='".$code."'";
							$query = $mysqli->query($req);
							$res = $query->fetch_array(MYSQLI_ASSOC);

							if ($res['id']){
								if (!isset($_POST['itexistsi'.$i])){
									if ($data['exists'][$i]) {
										$data['exists'][$i]['usercode'] = $res['id'];
									}else{
										$data['malformed'][$i] = array('usercode'=>$res['id']);
									}
								}
							}
//						}else{
//							$data['malformed'][$i] = 'usercode';
						}
					}
				}
			}

			//Load every groupes
			$data['groupes'] = array();
			$req = "SELECT * FROM groupes WHERE etat='actif' ORDER BY nom ASC";
			$query = $mysqli->query($req);
			while($res = $query->fetch_array(MYSQLI_ASSOC)){
				$data['groupes'][] = $res;
			}

			$mysqli->close();

			//come from step 3. Check if we can go to step 4
			if ($_POST['importstep'] == '3'){
				if (count($data['malformed']) > 0 || count($data['exists']) > 0) {
					$data['error'] = true;
					$_POST['importstep'] = 2;
					$data['step'] = 3;
				}else{
					$data['step'] = 4;
				}
			}
		}else{
			$data['error'] = true;
			//Recheck for everything. If there's an error, go back to previous step (and ask to fix it)
			$data['step'] = 2;
//			$_POST['importstep'] = 2;			
		}
	}

	if ($data['step'] == 4){
		$iMail = -1;
		$iCode = -1;
		$iFirst = -1;
		$iLast = -1;
		$iPass = -1;

		$types = array();
		for ($i=0;$i<$data['max'];$i++){
			$types[$_POST['data'.$i.'type']]++;

			switch($_POST['data'.$i.'type']){
				case 'usercode':
					$iCode = $i;
				break;
				case 'email':
					$iMail = $i;
				break;
				case 'firstname':
					$iFirst = $i;
				break;
				case 'lastname':
					$iLast = $i;
				break;
				case 'password':
					$iPass = $i;
				break;
			}
		}

		$sendtogroup = array();
		for($i=0;$i<$_POST['useUserNb'];$i++){
			$sendtogroup[] = $_POST['useUser'.$i];
		}

		$_POST['importreq'] = array();
		$_POST['groupereq'] = array();

		$mysqli = dbconnect();
		foreach($data['data'] AS $i=>$res){
//			echo "<pre>CHECKING ".$i." (".$_POST["line".$i."Enabled"].")\n\r".print_r($res,1)."</pre>";
/*			if (!isset($_POST['itexistsi'.$i])){
				//It already exists, do as asked 
				switch($_POST['itexistsi'.$i]){
					case 'nothing':
						//Do nothing. No import, nothing.
						$eid = null;
					break;
					default:
						//We received a ID. Use this one instead of a new one.
						$eid = $_POST['itexistsi'.$i];
					break;
				}
			}else{*/
				//Import the new user
			if ($_POST["line".$i."Enabled"] == "1"){
				$req = "INSERT INTO users SET date=NOW(), niveau='etudiant'";
				if ($iMail > -1){
						if (isset($_POST['replacementEmaili'.$i])){
							$req .= ", email='".strtolower($_POST['replacementEmaili'.$i])."'";
						}else{
							$req .= ", email='".strtolower($res[$iMail])."'";
						}
				}

				if ($iCode > -1){
	//					if (isset($_POST['replacementCodei'.$i])){
	//						$req .= ", usercode='".strtolower($_POST['replacementCodei'.$i]);
	//					}else{
						$req .= ", usercode='".strtolower($res[$iCode])."'";
	//					}
				}

				if ($iPass > -1){
					$req .= ", pass='".$res[$iPass]."'";
				}

				if ($iFirst > -1){
					$req .= ", prenom='".$res[$iFirst]."'";
				}

				if ($iLast > -1){
					$req .= ", nom='".$res[$iLast]."'";
				}

				$mysqli->query($req);

				$_POST['importreq'][] = $req;

				$sendtogroup[] = $mysqli->insert_id;
			}
		}

		//Add to group if useful in any way
		if ($_POST['importGroup']){
			$_POST['importGroup'] = checkValidateKey($_POST['importGroup']);

			if ($_POST['importGroup'] > 0){
				foreach($sendtogroup AS $uid){
					$req = "SELECT id FROM groupe_users WHERE id_groupe='".$_POST['importGroup']."' AND id_user='".$uid."' AND etat='actif'";
					$query = $mysqli->query($req);
					$res = $query->fetch_array(MYSQLI_ASSOC);

					if (!$res['id']){
						$req = "INSERT INTO groupe_users SET id_groupe='".$_POST['importGroup']."', id_user='".$uid."', etat='actif', date=NOW()";
	
						$_POST['groupereq'][] = $req;


						$mysqli->query($req);
					}
				}
			}
		}


		if ($_POST['ajax']){
		//Confirm text
			$data['confirmText'] = "Importation terminée.";
			$data['refreshContent'] = true;
			exit(json_encode($data));
		}

		$mysqli->close();

	}else if ($_POST['importstep'] == 2){
		$data['step'] = 3;
	}else if (isset($_POST['csv'])){
		$data['step'] = 2;
	}else{
		$data['step'] = 1;
	}

?>