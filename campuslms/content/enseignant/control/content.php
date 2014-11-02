<?php
	if ($_GET['qs'][1]){
		//Check if group exist and is associated with this teacher

		$mysqli = dbconnect();
		$req = "SELECT groupes.id AS idg, cours.id AS idc, groupes.*, cours.* FROM groupes, cours, formations WHERE formations.id='".$_GET['qs'][1]."' AND groupes.id=formations.id_groupe AND cours.id=formations.id_cours";
		$query = $mysqli->query($req);
		$data = $query->fetch_array(MYSQLI_ASSOC);

		if ($data['id']){
			switch($_GET['qs'][2]){
				case 'message':
					require("message.php");
					$_param['view'] = "message";
					return;
				break;
				case 'tps':
					require("tps.php");
					$_param['view'] = "tps";
					return;
				break;
				case 'tp':
					//Make sure user $_GET['qs'][3] exist and is in this group...

					if (1){
						require("tp.php");
						$_param['view'] = "tp";
					}
				break;
				case 'devoirs':
					require("devoirs.php");
					$_param['view'] = "devoirs";
					return;
				break;
				case 'devoir':
					//Make sure user $_GET['qs'][3] exist and is in this group...

					if (1){
						require("devoir.php");
						$_param['view'] = "devoir";
					}
				break;
				case 'quiz':
					//Make sure user $_GET['qs'][3] exist and is in this group...

					if (1){
						require("quiz.php");
						$_param['view'] = "quiz";
					}
				break;
				case 'user':
					//Make sure user $_GET['qs'][3] exist and is in this group...

					if (1){
						switch($_GET['qs'][4]){
							case 'notes':
								//User's notes.
								require("user_notes.php");
								$_param['view'] = "user_notes";
								return;
							break;
							case 'quiz':
								if ($_GET['qs'][6] == 'reponses'){
									require("user_quiz_review.php");
									$_param['view'] = "user_quiz_review";
								}else{
									require("user_quiz.php");
									$_param['view'] = "user_quiz";
								}
								return;
							break;
							case 'devoir':
								require("user_devoir.php");
								$_param['view'] = "user_devoir";
								return;
							break;
							case 'tp':
								require("user_tp.php");
								$_param['view'] = "user_tp";
								return;
							break;							case 'stats':
								//User's notes.
								require("user_stats.php");
								$_param['view'] = "user_stats";
								return;
							break;
							default:
								require("user.php");
								$_param['view'] = "user";
								return;
							break;
						}
					}else{
						echo "error";
					}
				break;
				default:
					//Show details!



					//Load cours & quiz
					$toLoad = array();
					$toLoad['quiz'] = array();
					$toLoad['devoir'] = array();
					$toLoad['tp'] = array();

					$data['lecons'] = array();

					$data['noteFinaleValeur'] = 0;
					$data['noteFinaleCalcule'] = 0;
					$data['nbNote'] = 0;

					$req = "SELECT cours_lecon.* FROM cours_lecon WHERE cours_lecon.id_cours='".$data['idc']."' ORDER BY date ASC";

					$query = $mysqli->query($req);
					while($res = $query->fetch_array(MYSQLI_ASSOC)){
						$temp = array();
						$temp['quiz'] = array();
						$temp['devoir'] = array();
						$temp['tp'] = array();
						$temp['nom'] = $res['nom'];
						$temp['points'] = 0;
						$temp['valeur'] = 0;
						$temp['nbNote'] = 0;
						$temp['maxNote'] = 0;

						//Load every quiz from there!
						$req = "SELECT * FROM cours_lecon_quizz WHERE id_lecon='".$res['id']."' AND deleted=0 ORDER BY date ASC";
						$query2 = $mysqli->query($req);
						while($res2 = $query2->fetch_array(MYSQLI_ASSOC)){
							//Load every answer from there..
							$temp['quiz'][$res2['id']] = $res2;
							$temp['quiz']['points'] = 0;
							$temp['quiz']['valeur'] = 0;
							$temp['quiz']['nbNote'] = 0;

//							$temp['maxNote']++;

//							$toLoad['quiz'][$res2['id']] = $res2['id'];
							$toLoad['quiz'][$res2['id']] = array('id'=>$res2['id'], 'nom'=>$res2['nom']);
						}

						//Load every tp from there!
						$req = "SELECT * FROM cours_lecon_fichiers WHERE type='tp' AND id_lecon='".$res['id']."' ORDER BY date ASC";
						$query2 = $mysqli->query($req);
						while($res2 = $query2->fetch_array(MYSQLI_ASSOC)){
							$temp['tp'][$res2['id']] = $res2;
							$temp['tp']['points'] = 0;
							$temp['tp']['valeur'] = 0;
							$temp['tp']['nbNote'] = 0;

//							$toLoad['tp'][] = $res2['id'];
							$toLoad['tp'][$res2['id']] = array('id'=>$res2['id'], 'nom'=>$res2['nom']);
						}


						//Load every devoir from there!
						$req = "SELECT * FROM cours_lecon_fichiers WHERE type='devoir' AND id_lecon='".$res['id']."' ORDER BY date ASC";
						$query2 = $mysqli->query($req);
						while($res2 = $query2->fetch_array(MYSQLI_ASSOC)){
							$temp['devoir'][$res2['id']] = $res2;
							$temp['devoir']['points'] = 0;
							$temp['devoir']['valeur'] = 0;
							$temp['devoir']['nbNote'] = 0;

//							$toLoad['devoir'][] = $res2['id'];
							$toLoad['devoir'][$res2['id']] = array('id'=>$res2['id'], 'nom'=>$res2['nom']);
						}

						$data['lecons'][$res['id']] = $temp;						
					}

					//Valeur ajustée
/*					$eachVal = 0;
					foreach($data['lecons'] AS $leconId=>$v){
						foreach($v AS $type=>$v2){
							foreach($v2 AS $res){
								$eachVal++;
							}
						}
					}
					$eachVal = round(100/$eachVal,6);*/

					//Load users
					$req = "SELECT users.*, groupe_users.etat AS uetat, groupe_users.date AS udate FROM users, groupe_users WHERE groupe_users.id_groupe='".$data['idg']."' AND users.id=groupe_users.id_user AND groupe_users.etat='actif' ORDER BY groupe_users.date DESC";

					$query = $mysqli->query($req);
					$data['users'] = array();

					while ($res = $query->fetch_array(MYSQLI_ASSOC)){
						$res['notes'] = array();
						$res['notes']['quiz'] = array();
						$res['notes']['tp'] = array();
						$res['notes']['devoir'] = array();
						$res['noteFinaleCalcule'] = 0;
						$res['noteFinaleValeur'] = 0;

						//Devoir?
						foreach($toLoad['devoir'] AS $v){
							$v = $v['id'];
							$req = "SELECT cours_lecon_fichiers.valeur, cours_lecon_fichiers.id_lecon, cours_lecon_fichiers_remise.* FROM cours_lecon_fichiers_remise,cours_lecon_fichiers WHERE cours_lecon_fichiers_remise.id_user='".$res['id']."' AND cours_lecon_fichiers_remise.id_fichier='".$v."' AND cours_lecon_fichiers.id=cours_lecon_fichiers_remise.id_fichier AND cours_lecon_fichiers_remise.id_formation='".$_GET['qs'][1]."' ORDER BY cours_lecon_fichiers_remise.date DESC LIMIT 0,1";

							$query3 = $mysqli->query($req);
							$res3 = $query3->fetch_array(MYSQLI_ASSOC);

							$res3['note'] = $res3['note'];
							$res3['sur'] = $res3['valeur'];

							//CALCULATE THIS SOMEHOW 
//							$res3['valeur'] = $eachVal;

							if ($res3['id']){
								//Load edited note
								$req = "SELECT * FROM notes WHERE id_user='".$res['id']."' AND id_ref='".$res3['id']."' AND ref_type='devoir' ORDER BY date DESC LIMIT 0, 1";
								$query4 = $mysqli->query($req);
								$res4 = $query4->fetch_array(MYSQLI_ASSOC);
								if ($res4['id']){
									$res3['noteEdit'] = $res4['note'];
								}

								//Calculate lecon moyenne
								$data['lecons'][$res3['id_lecon']]['points'] += $res3['noteEdit']?$res3['noteEdit']:$res3['note'];
								$data['lecons'][$res3['id_lecon']]['valeur'] += $res3['sur'];
								$data['lecons'][$res3['id_lecon']]['nbNote']++;
								$data['lecons'][$res3['id_lecon']]['maxNote']++;

								//Calculate devoir moyenne
								$data['lecons'][$res3['id_lecon']]['devoir'][$v]['points'] += $res3['noteEdit']?$res3['noteEdit']:$res3['note'];
								$data['lecons'][$res3['id_lecon']]['devoir'][$v]['valeur'] += $res3['sur'];
								$data['lecons'][$res3['id_lecon']]['devoir'][$v]['nbNote']++;
							}

							//Continue this thing
							$res['notes']['devoir'][$v] = $res3;

							$res['noteFinaleCalcule'] += ($res3['noteEdit']?$res3['noteEdit']:$res3['note'])/100*$res3['valeur'];
							$res['noteFinaleValeur'] += $res3['valeur'];
							$data['noteFinaleCalcule'] += ($res3['noteEdit']?$res3['noteEdit']:$res3['note'])/100*$res3['valeur'];
							$data['noteFinaleValeur'] += $res3['valeur'];
							$data['nbNote']++;
						}

						//tp?
						foreach($toLoad['tp'] AS $v){
							$v = $v['id'];
							$req = "SELECT cours_lecon_fichiers.valeur, cours_lecon_fichiers.id_lecon, cours_lecon_fichiers_remise.* FROM cours_lecon_fichiers_remise,cours_lecon_fichiers WHERE cours_lecon_fichiers_remise.id_user='".$res['id']."' AND cours_lecon_fichiers_remise.id_fichier='".$v."' AND cours_lecon_fichiers.id=cours_lecon_fichiers_remise.id_fichier AND cours_lecon_fichiers_remise.id_formation='".$_GET['qs'][1]."' ORDER BY cours_lecon_fichiers_remise.date DESC LIMIT 0,1";

							$query3 = $mysqli->query($req);
							$res3 = $query3->fetch_array(MYSQLI_ASSOC);

							$res3['note'] = $res3['note'];
							$res3['sur'] = $res3['valeur'];

							//CALCULATE THIS SOMEHOW
//							$res3['valeur'] = $eachVal;

							if ($res3['id']){
								//Load edited note
								$req = "SELECT * FROM notes WHERE id_user='".$res['id']."' AND id_ref='".$res3['id']."' AND ref_type='tp' ORDER BY date DESC LIMIT 0, 1";
								$query4 = $mysqli->query($req);
								$res4 = $query4->fetch_array(MYSQLI_ASSOC);
								if ($res4['id']){
									$res3['noteEdit'] = $res4['note'];
								}

								//Calculate lecon moyenne
								$data['lecons'][$res3['id_lecon']]['points'] += $res3['noteEdit']?$res3['noteEdit']:$res3['note'];
								$data['lecons'][$res3['id_lecon']]['valeur'] += $res3['sur'];
								$data['lecons'][$res3['id_lecon']]['nbNote']++;
								$data['lecons'][$res3['id_lecon']]['maxNote']++;

								//Calculate tp moyenne
								$data['lecons'][$res3['id_lecon']]['tp'][$v]['points'] += $res3['noteEdit']?$res3['noteEdit']:$res3['note'];
								$data['lecons'][$res3['id_lecon']]['tp'][$v]['valeur'] += $res3['sur'];
								$data['lecons'][$res3['id_lecon']]['tp'][$v]['nbNote']++;
							}

							//Continue this thing
							$res['notes']['tp'][$v] = $res3;

							$res['noteFinaleCalcule'] += ($res3['noteEdit']?$res3['noteEdit']:$res3['note'])/100*$res3['valeur'];
							$res['noteFinaleValeur'] += $res3['valeur'];
							$data['noteFinaleCalcule'] += ($res3['noteEdit']?$res3['noteEdit']:$res3['note'])/100*$res3['valeur'];
							$data['noteFinaleValeur'] += $res3['valeur'];
							$data['nbNote']++;
						}


						//Quiz?
						foreach($toLoad['quiz'] AS $v){
							$v = $v['id'];
							//Load every answer from there..
							$req = "SELECT cours_lecon_quizz.id_lecon, cours_lecon_quizz.valeur AS quizvaleur, cours_lecon_quizz_session.* FROM cours_lecon_quizz, cours_lecon_quizz_session WHERE cours_lecon_quizz.id='".$v."' AND cours_lecon_quizz_session.id_user='".$res['id']."' AND cours_lecon_quizz_session.id_quiz='".$v."' AND cours_lecon_quizz_session.id_formation='".$_GET['qs'][1]."' ORDER BY cours_lecon_quizz_session.datedebut ASC LIMIT 0,1";

							$query3 = $mysqli->query($req);
							$res3 = $query3->fetch_array(MYSQLI_ASSOC);

							$res3['note'] = $res3['pointage'];
							$res3['sur'] = $res3['valeur'];

							$res3['valeur'] = $res3['quizvaleur'];

							if ($res3['id']){
								//Load edited note
								$req = "SELECT * FROM notes WHERE id_user='".$res['id']."' AND id_ref='".$res3['id']."' AND ref_type='quiz' ORDER BY date DESC LIMIT 0, 1";
								$query4 = $mysqli->query($req);
								$res4 = $query4->fetch_array(MYSQLI_ASSOC);
								if ($res4['id']){
									$res3['noteEdit'] = $res4['note'];
								}

								//Calculate lecon moyenne
								$data['lecons'][$res3['id_lecon']]['points'] += $res3['noteEdit']?$res3['noteEdit']:$res3['note'];
								$data['lecons'][$res3['id_lecon']]['valeur'] += $res3['sur'];
								$data['lecons'][$res3['id_lecon']]['nbNote']++;
								$data['lecons'][$res3['id_lecon']]['maxNote']++;

								//Calculate devoir moyenne
								$data['lecons'][$res3['id_lecon']]['quiz'][$v]['points'] += $res3['noteEdit']?$res3['noteEdit']:$res3['note'];
								$data['lecons'][$res3['id_lecon']]['quiz'][$v]['valeur'] += $res3['sur'];
								$data['lecons'][$res3['id_lecon']]['quiz'][$v]['nbNote']++;
							}

							//Continue this thing
							$res['notes']['quiz'][$v] = $res3;

							$res['noteFinaleCalcule'] += ($res3['noteEdit']?$res3['noteEdit']:$res3['note'])/$res3['sur']*$res3['valeur'];
							$res['noteFinaleValeur'] += $res3['valeur'];
							$data['noteFinaleCalcule'] += ($res3['noteEdit']?$res3['noteEdit']:$res3['note'])/$res3['sur']*$res3['valeur'];
							$data['noteFinaleValeur'] += $res3['valeur'];
							$data['nbNote']++;
						}

//						$data['users'][] = $res;
						$data['users'][$res['id']] = $res;
					}

					//Load news
						$data['todo'] = array();

						//Load unread notes
//							$req = "SELECT cours_lecon_quizz_session.*, cours_lecon_quizz.nom AS details, users.prenom AS user_prenom, users.nom AS user_nom, users.email AS user_email FROM cours_lecon_quizz_session, users, cours_lecon_quizz WHERE cours_lecon_quizz_session.id_formation='".$_GET['qs'][1]."' AND cours_lecon_quizz_session.read!=1 AND users.id=cours_lecon_quizz_session.id_user AND cours_lecon_quizz.id=cours_lecon_quizz_session.id_quiz";

							$req = "SELECT cours_lecon_quizz_session.*, cours_lecon_quizz.nom AS details, users.prenom AS user_prenom, users.nom AS user_nom, users.email AS user_email FROM cours_lecon_quizz_session, users, cours_lecon_quizz WHERE cours_lecon_quizz_session.id_formation='".$_GET['qs'][1]."' AND users.id=cours_lecon_quizz_session.id_user AND cours_lecon_quizz.id=cours_lecon_quizz_session.id_quiz";


							$query = $mysqli->query($req);
							while($res = $query->fetch_array(MYSQLI_ASSOC)){
								$res['type'] = 'quiz';
								$k = strtotime($res['datefin']);
								while($data['todo'][$k]){
									$k++;
								}
								$data['todo'][$k] = $res;
							}

						//Load unread homework
//							$req = "SELECT * FROM cours_lecon_fichiers_remise WHERE id_formation='".$_GET['qs'][1]."' AND read!=1";
//						$req = "SELECT cours_lecon_fichiers.* FROM cours_lecon_fichiers, uploadRef WHERE cours_lecon_fichiers.type='devoir' AND cours_lecon_fichiers.id_lecon='".$res['id']."' AND uploadRef.id=cours_lecon_fichiers.id_upload AND uploadRef.etat='recu' ORDER BY date ASC";




//							$req = "SELECT cours_lecon_fichiers_remise.*, users.prenom AS user_prenom, cours_lecon_fichiers.nom AS details, users.nom AS user_nom, users.email AS user_email FROM cours_lecon_fichiers_remise, users, cours_lecon_fichiers, uploadRef WHERE uploadRef.id=cours_lecon_fichiers_remise.id_upload AND uploadRef.etat='recu' AND cours_lecon_fichiers_remise.id_formation='".$_GET['qs'][1]."' AND cours_lecon_fichiers_remise.read!=1 AND users.id=cours_lecon_fichiers_remise.id_user AND cours_lecon_fichiers.id=cours_lecon_fichiers_remise.id_fichier";

							$req = "SELECT cours_lecon_fichiers_remise.*, users.prenom AS user_prenom, cours_lecon_fichiers.nom AS details, users.nom AS user_nom, users.email AS user_email, cours_lecon_fichiers.type FROM cours_lecon_fichiers_remise, users, cours_lecon_fichiers, uploadRef WHERE uploadRef.id=cours_lecon_fichiers_remise.id_upload AND uploadRef.etat='recu' AND cours_lecon_fichiers_remise.id_formation='".$_GET['qs'][1]."' AND users.id=cours_lecon_fichiers_remise.id_user AND cours_lecon_fichiers.id=cours_lecon_fichiers_remise.id_fichier";

							$query = $mysqli->query($req);
							while($res = $query->fetch_array(MYSQLI_ASSOC)){
								//$res['type'] = 'devoir';
								$k = strtotime($res['date']);
								while($data['todo'][$k]){
									$k++;
								}
								$data['todo'][$k] = $res;
							}

						//Load various stats liked to this
							$req = "SELECT logs.*, users.prenom AS user_prenom, users.nom AS user_nom, users.email AS user_email, users.id AS id_user FROM logs, users WHERE (logs.texte='openedLecon' OR logs.texte='openedCours' OR logs.texte='remiseFichier' OR logs.texte='openedQuiz' OR logs.texte='remiseQuiz') AND logs.ref2='".$_GET['qs'][1]."' AND users.id=logs.uid";

							$query = $mysqli->query($req);
							while($res = $query->fetch_array(MYSQLI_ASSOC)){
								$res['type'] = 'stats';

								$res['reftext'] = "N/A";

								switch($res['texte']){
									case 'openedLecon':
										$res['reftext'] = $data['lecons'][$res['ref']]['nom'];
									break;
									case 'remiseFichier':
										$res['reftext'] = $toLoad['devoir'][$res['ref']]['nom'];
									break;
									case 'remiseTP':
										$res['reftext'] = $toLoad['tp'][$res['ref']]['nom'];
									break;
									case 'openedQuiz':
										$res['reftext'] = $toLoad['quiz'][$res['ref']]['nom'];
									break;
									case 'remiseQuiz':
										$res['reftext'] = $toLoad['quiz'][$res['ref']]['nom'];
									break;
								}

								$k = strtotime($res['date']);
								while($data['todo'][$k]){
									$k++;
								}
								$data['todo'][$k] = $res;
							}

					//Reorder them
					krsort($data['todo']);


					//Calculate Médiane / Moyenne
					if ($_GET['qs'][2] == "mediane"){
						//Médiane
						$viewMode = "médiane";
						$notes = array();

						foreach($data['users'] AS $k=>$v){
							$v = $v['noteFinaleCalcule'];
							$notes[] = $v;
						}
						$nb = count($notes);

						if ($nb > 0){
							if ($nb%2){
								//Impair
								$moyenne = $notes[floor(($nb-1)/2)];
							}else{
								//Pair
								$moyenne = ($notes[floor(($nb-1)/2)]+$notes[floor(($nb-1)/2)+1])/2;
							}
						}else{
							$moyenne = 0;
						}
					}else{
						//Moyenne
						$viewMode = "moyenne";
						$moyenne = $data['noteFinaleCalcule']/count($data['users']);
					}
					$superieur = array();
					$inferieur = array();


					foreach($data['users'] AS $k=>$v){
						$v = $v['noteFinaleCalcule'];
						if ($v > $moyenne){
							$superieur[$k] = $v;
						}else if ($v < $moyenne){
							$inferieur[$k] = $v;
						}else{
							$superieur[$k] = $v;
							$inferieur[$k] = $v;
						}
					}

					arsort($superieur);
					arsort($inferieur);

					$moyenne = round($moyenne,4);






					$_param['view'] = "content";
				break;
			}
		}else{
			$_param['view'] = "index";
		}
	}else{
		$_param['view'] = "index";
	}
?>