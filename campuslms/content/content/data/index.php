<?php
	GLOBAL $_req;
	$_req['css'][] = "campuslms/lib/spectrum/spectrum.css";
	$_req['js'][] = "campuslms/lib/spectrum/spectrum.js";

	$mysqli = dbconnect();

	$res = array();
	$res['notifications'] = array();

	if ($_SESSION['user_id']){
		//Load notifications data (always)
		include(dirname(__FILE__)."/../../../core/ajax/notifications.php");
		$res['notifications'] = $res;

		//Load current chat data (if we have it)
		if ($_POST['chatId']){
			include(dirname(__FILE__)."/../../../core/ajax/chatUpdate.php");
			$res['chat'] = $res;
		}

		//Load sidebar data (not on ajax)
		$res['sidebar'] = array();

		if (!$_POST['ajax']){
			$res['sidebar'] = array();
			switch($_SESSION['user_level']){
				case 'sadmin':
					$res['sidebar'][] = array('admin/index','Accueil','Aperçu des activités');

					$res['sidebar'][] = array('admin/settings','Réglages','Modifier les paramètres');
					$res['sidebar'][] = array('admin/backups','Sauvegardes','Sauvegarder ou Restaurer');
					$res['sidebar'][] = array('admin/updates','Mise à jour','Mettre à jour la plateforme');

					$res['sidebar'][] = array('admin/cours','Cours','Affichage des cours et leçons');
					$res['sidebar'][] = array('admin/account','Utilisateurs','Création et organisation des comptes');
					$res['sidebar'][] = array('admin/entreprise','Entreprises','Création et organisation des comptes');
					$res['sidebar'][] = array('admin/groupes','Groupes','Création et organisation des groupes');
					$res['sidebar'][] = array('admin/demandes','Demandes','Demande d\'accès aux formations');
					$res['sidebar'][] = array('admin/historique','Historique','Logs et informations');
				break;
				case 'admin':
					if ($_SESSION['accountType'] == 'campus'){
						$res['sidebar'][] = array('admin/index','Accueil','Aperçu des activités');
						$res['sidebar'][] = array('admin/cours','Cours','Affichage des cours et leçons');
						$res['sidebar'][] = array('admin/account','Utilisateurs','Création et organisation des comptes');
						$res['sidebar'][] = array('admin/entreprise','Entreprises','Création et organisation des comptes');
						$res['sidebar'][] = array('admin/groupes','Groupes','Création et organisation des groupes');
						$res['sidebar'][] = array('admin/demandes','Demandes','Demande d\'accès aux formations');
						$res['sidebar'][] = array('admin/historique','Historique','Logs et informations');

						$_req['css'][] = "campuslms/lib/spectrum/spectrum.css";
						$_req['js'][] = "campuslms/lib/spectrum/spectrum.js";

					}else{
						$res['sidebar'][] = array('admin/index','Accueil','Aperçu des activités');
						$res['sidebar'][] = array('admin/account','Utilisateurs','Création et organisation des comptes');
//						$res['sidebar'][] = array('admin/cours','Cours','Affichage des cours et leçons');
						$res['sidebar'][] = array('admin/groupes','Groupes','Création et organisation des groupes');
						$res['sidebar'][] = array('admin/historique','Historique','Logs et informations');
//						$res['sidebar'][] = array('admin/account','Utilisateurs','Création et organisation des comptes');
//						$res['sidebar'][] = array('admin/historique','Historique','Logs et informations');

/*						$req = "SELECT formations.id AS idf, cours.nom AS nomc, groupes.nom AS nomg FROM formations, cours, groupes WHERE formations.id_user='".$_SESSION['user_id']."' AND cours.id=formations.id_cours AND groupes.id=formations.id_groupe";

						$query = $mysqli->query($req);
						while($result = $query->fetch_array(MYSQLI_ASSOC)){

							$res['sidebar'][] = array("enseignant/".$result['idf']."/edit",$result['nomg'],$result['nomc']);
						}*/
					}
				break;
				case 'collaborateur':
					//collaborateur

					$mysqli = dbconnect();
					$req = "SELECT * FROM cours WHERE etat != 'deleted' AND id_user='".$_SESSION['user_id']."' ORDER BY date DESC";
					$query = $mysqli->query($req);
					while($result = $query->fetch_array(MYSQLI_ASSOC)){
						$req2 = "SELECT count(id) AS nb FROM cours_lecon WHERE id_cours='".$result['id']."' AND etat != 'deleted'";
						$query2 = $mysqli->query($req2);
						$res2 = $query2->fetch_array(MYSQLI_ASSOC);

//						if ($result['type'] == 'standard'){
							$res['sidebar'][] = array("collabo/".$result['id']."",$result['nom'],$res2['nb']." leçon".(($res2['nb'] > 1)?'s':''), false, 'sidebarLeconCtn'.$result['id']);
//						}else{
//							$res['sidebar'][] = array("collabo/".$result['id']."",$result['nom'],$res2['nb']." cours", false, 'sidebarLeconCtn'.$result['id']);
//						}

					}
				break;
				case 'enseignant':
					$req = "SELECT formations.id AS idf, cours.nom AS nomc, groupes.nom AS nomg FROM formations, cours, groupes WHERE formations.id_user='".$_SESSION['user_id']."' AND cours.id=formations.id_cours AND groupes.id=formations.id_groupe";

					$query = $mysqli->query($req);
					while($result = $query->fetch_array(MYSQLI_ASSOC)){

						$res['sidebar'][] = array("enseignant/".$result['idf']."/edit",$result['nomg'],$result['nomc']);
					}
				break;
				case 'etudiant':
					//Normal user
					if ($_SESSION['accountType'] == 'campus'){
						$req = "SELECT groupes.nom AS nomg, cours.*, formations.id AS fid, formations.etat FROM cours, formations, groupe_users, groupes WHERE cours.etat != 'deleted' AND groupe_users.id_user='".$_SESSION['user_id']."' AND groupe_users.etat='actif' AND formations.id_groupe=groupe_users.id_groupe AND cours.id=formations.id_cours AND groupes.id=formations.id_groupe AND (formations.datedebut IS NULL OR formations.datedebut <= NOW()) AND formations.etat!='deleted' GROUP BY cours.id ORDER BY formations.etat ASC, cours.nom ASC"; //formations.date DESC, cours.date DESC
					}else{
						$req = "SELECT cours.*, cie_licenses.id AS fid, 'actif' AS etat FROM cours, cie_licenses, cie_license_users WHERE cours.etat != 'deleted' AND cie_license_users.id_user='".$_SESSION['user_id']."' AND cie_licenses.id=cie_license_users.id_license AND cours.id=cie_licenses.id_cours ORDER BY cours.nom ASC";
					}

					$query = $mysqli->query($req);
					$res = array();

					$found = array();

					while($result = $query->fetch_array(MYSQLI_ASSOC)){
						switch($result['etat']){
							case 'actif':
							case 'partiel':
								$res['sidebar'][] = array("cours/".$result['fid']."/",$result['nom'],"Groupe ".$result['nomg'], true);
							break;
							case 'inactif':
								if (!in_array($result['id'], $found)){
									$res['sidebar'][] = array("cours/".$result['fid']."/",$result['nom'].'<div class="locked"></div>',"Proposition", true);
								}
							break;
						}
						$found[] = $result['id'];
					}

					$res['sidebar'][] = array("resultats/","Résultats","Voir mes résultats", true);

				break;
			}
		}
	}

	$mysqli->close();

//	print_r($res['sidebar']);
?>