<?php
	$loadCours = true;
/*	if ($_GET['qs'][1] == 'nouveau' && $_SESSION['user_level'] == 'collaborateur') {
		$_param['view'] .= "_new";
//		require(dirname(__FILE__)."/index_edit.php");
		$loadCours = false;

	}

	if ($loadCours){*/
		//Make sure the user can access this...

		$mysqli = dbconnect();

		if ($_SESSION['accountType'] == 'campus'){
			$req = "SELECT formations.id AS fid, formations.message, formations.id_user, formations.id_groupe, cours.id AS cid, formations.etat, formations.datefin FROM cours, formations, groupe_users WHERE groupe_users.id_user='".$_SESSION['user_id']."' AND formations.id_groupe=groupe_users.id_groupe AND cours.id=formations.id_cours AND formations.id='".$_GET['qs'][1]."' AND formations.etat!='deleted'";
		}else{
			$req = "SELECT '0' AS fid, cie_licenses.id AS id_groupe, cours.id AS cid FROM cours, cie_licenses, cie_license_users WHERE cie_license_users.id_user='".$_SESSION['user_id']."' AND cie_licenses.id=cie_license_users.id_license AND cours.id=cie_licenses.id_cours AND cie_licenses.id='".$_GET['qs'][1]."'";
		}
//		$req .= " AND (formations.datedebut IS NULL OR formations.datedebut >= NOW()) AND (formations.datefin IS NULL OR formations.datefin <= NOW())";

		$query = $mysqli->query($req);
		$res = $query->fetch_array(MYSQLI_ASSOC);

		if (!$res['cid']){
			$_param['module'] = 'error';
			$_param['view'] = $mod['defaultView'];
			$_param['404'] = true;
		}

		$data['locked'] = false;
		$locked = false;

		$formationetat = $res['etat'];

		if ($_SESSION['accountType'] == 'campus' && $res['etat'] == 'inactif'){
			$data['locked'] = true;
			$locked = true;
		}

		$groupeid = $res['id_groupe'];
		$formationid = $res['fid'];
		$coursid = $res['cid'];
		$data['formationid'] = $res['fid'];
		$data['coursid'] = $res['cid'];
//		$data['message'] = $res['message'];
		$data['enseignant'] = $res['id_user'];
		$data['datefin'] = $res['datefin'];

		if ($res['cid']){
			$accessGranted = true;
		}

		//Forced for now!
		$accessGranted = true;
/*	}else{
		exit('XX');
		$_param['module'] = 'error';
		require(dirname(__FILE__)."/../content/".$_param['module']."/required.php");
		$_param['view'] = $mod['defaultView'];
		$_param['404'] = true;
	}*/
?>