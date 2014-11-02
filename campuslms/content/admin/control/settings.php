<?php
	if ($_SESSION['accountType'] != 'campus'){
		exit('err');
	}

	$theme = json_decode(file_get_contents("data/settings/template_default.json"),true);

	$data['skey'] = md5(md5_file("data/settings/required.php").INNER_SALT."!".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]);

	if ($_POST['skey'] && $_POST['skey'] = $data['skey']){
		//Prepare template data
		foreach($theme['settings'] AS $k=>$res){
			if (isset($_POST['themeSetting_'.$k])){
				$v = $_POST['themeSetting_'.$k];
				if ($v == "")
					$v = $res['default'];
				$theme['settings'][$k]['value'] = $v;
			}
		}

		//Reset inner salt - We don't want to loose it
		$_POST['s_INNER_SALT'] = INNER_SALT;

		//Check if DB_PASSWORD was sent
		if (strlen($_POST['s_DB_PASSWORD']) == 0){
			$_POST['s_DB_PASSWORD'] = DB_PASSWORD;
		}

		//Prepare site data
		$siteData = "<?php\n\r";
		foreach($_POST AS $k=>$v){
			if (substr($k,0,2) == 's_'){
				$k = substr($k,2);

				if (defined($k)){
					$v = str_replace('"', '\"', $v);
					$siteData .= "define(\"".$k."\", \"".$v."\");\n\r";
				}
			}
		}
		$siteData .= "?>";

		//Save theme template
			$backupfolder = date('Y-m-d');
			$i = 1;
			while(is_dir('data/backups/'.$backupfolder)){
				$i++;
				$backupfolder = date('Y-m-d')." ".$i;
			}
			mkdir('data/backups/'.$backupfolder);

			//Make a backup of the settings file
			rename('data/settings/template_default.json','data/backups/'.$backupfolder.'/template_default.json');

			//Save the new file
			$myFile = "data/settings/template_default.json";
			$fh = fopen($myFile, 'w') or die("Erreur - Impossible d'écrire les règlages du thème.");
			fwrite($fh, json_encode($theme));
			fclose($fh);

		//Update site settings
			//Make a backup of the setting file
			rename('data/settings/required.php','data/backups/'.$backupfolder.'/data_required.php');

			//Save the new file
			$myFile = "data/settings/required.php";
			$fh = fopen($myFile, 'w') or die("Erreur - Impossible d'écrire les règlages de la plateforme");
			fwrite($fh, $siteData);
			fclose($fh);

			header('Location: '.SITE_URL.implode('/',$_GET['qs']));      
			exit('Sauvegarde effectuée. Redirection en cours.');

		$data['skey'] = md5(md5_file("data/settings/required.php").INNER_SALT."!".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]);
	}

?>