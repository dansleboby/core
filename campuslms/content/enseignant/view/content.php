	<header class="color_text">
		<h1>Groupe #<?php echo $data['id']; ?></h1>
		<h2><?php echo $data['nom']; ?></h2>
	</header>

	<ul id="headerOptions">
		<li>
			<a class="btn color_background openInMenuBar" id="messageButton" href="enseignant/<?php echo $_GET['qs'][1]; ?>/message"><strong>Message</strong>Envoyer un message à tous</a>
		</li>
		<li>
			<a class="btn color_background" id="notesButton" onclick="return toggleNotes();" href="javascript:toggleNotes();"><strong>Résultats</strong>Afficher tous les résultats</a>
		</li>
		<?php
			if ($viewMode == "moyenne"){
		?>
		<li>
			<a class="btn color_background" id="messageButton" href="enseignant/<?php echo $_GET['qs'][1]; ?>/mediane"><strong>Afficher médiane</strong>plutôt que moyenne</a>
		</li>
		<?php
			}else{
		?>
		<li>
			<a class="btn color_background" id="messageButton" href="enseignant/<?php echo $_GET['qs'][1]; ?>"><strong>Afficher moyenne</strong>plutôt que médiane</a>
		</li>
		<?php
			}
		?>

<!--		<li>
			<a class="btn color_background openInMenuBar" href="enseignant/<?php echo $_GET['qs'][1]; ?>/devoirs">Devoirs</a>
		</li>
		<li>
			<a class="btn color_background" href="enseignant/<?php echo $_GET['qs'][1]; ?>/ponderation" class="openInMenuBar">Pondération</a>
		</li>!-->
	</ul>
	<div class="topzone zone1 color_text">
		<div>
			<h3>Supérieur à la <?php echo $viewMode; ?> (<?php echo $moyenne; ?> et plus)</h3>
			<div class="">
				<?php
					if (count($superieur) == 0){
						echo '	<div class="empty">&mdash; Rien à afficher &mdash;</div>';
					}else{
				?>
				<table class="white color_border">
					<thead>
						<tr>
							<th><div class="large">Utilisateur</div></th>
							<th>Note</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$i = 1;
							foreach($superieur AS $k=>$v){
								$u = $data['users'][$k];

								echo "<tr>";
									echo "<th><a href=\"enseignant/".$_GET['qs'][1]."/user/".$u['id']."\" class=\"openInMenuBar\">".$u['nom'].", ".$u['prenom']."</a><a class=\"small block\" href=\"mailto:".$u['email']."\">".$u['email']."</a></th>";
									echo "<td>".$v."<span class=\"small\">/".$data['noteFinaleValeur']."</td>";
								echo "</tr>";
							}
						?>
					</tbody>
				</table>
				<?php
					}
				?>
			</div>
		</div>
	</div>

	<div class="topzone zone2 color_text">
		<div>
			<h3>Inférieur à la <?php echo $viewMode; ?> (<?php echo $moyenne; ?> et moins)</h3>
			<div class="">
				<?php
					if (count($inferieur) == 0){
						echo '	<div class="empty">&mdash; Rien à afficher &mdash;</div>';
					}else{
				?>
				<table class="white color_border">
					<thead>
						<tr>
							<th><div class="large">Utilisateur</div></th>
							<th>Note</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$i = 1;
							foreach($inferieur AS $k=>$v){
								$u = $data['users'][$k];

								echo "<tr>";
									echo "<th><a href=\"enseignant/".$_GET['qs'][1]."/user/".$u['id']."\" class=\"openInMenuBar\">".$u['nom'].", ".$u['prenom']."</a><a class=\"small block\" href=\"mailto:".$u['email']."\">".$u['email']."</a></th>";
									echo "<td>".$v."<span class=\"small\">/".$data['noteFinaleValeur']."</td>";
								echo "</tr>";
							}
						?>
					</tbody>
				</table>
				<?php
					}
				?>
			</div>
		</div>
	</div>





























	<div class="statsContainer">
		<h3 style="float:left;">Quoi de neuf</h3>

		<?php
			if (count($data['todo']) > 0){
		?>

		<div class="radioContainer">
			<div class="radioButtonLabel">Affichage : </div>
			<div class="color_background radioButton active" data-control="newsRadioControlled" data-show="newsRadioControlled">Tous</div>
			<div class="color_background radioButton" data-control="newsRadioControlled" data-show="newsRadioControlledDevoir">Devoirs</div>
			<div class="color_background radioButton" data-control="newsRadioControlled" data-show="newsRadioControlledTP">TP</div>
			<div class="color_background radioButton" data-control="newsRadioControlled" data-show="newsRadioControlledQuiz">Quiz</div>
			<div class="color_background radioButton" data-control="newsRadioControlled" data-show="newsRadioControlledStats">Activité</div>
		</div>

		<div class="settings color_background" style="clear:both;margin-bottom:-17px;padding:4px;">
			<select id="logTriUser" class="specialField" data-specialType="selectHelper" placeholder="Utilisateur" multiple="multiple">
				<?php
					foreach($data['users'] AS $u){
						echo "<option value=\"".$u['id']."\">".$u['prenom']." ".$u['nom']." (".$u['id']."".($usercode?' / '.$usercode:'').")</option>";
					}
				?>
			</select>
			<select id="logTriSujet" class="specialField" data-specialType="selectHelper" placeholder="Sujet (devoir/quiz)" multiple="multiple">
				<?php
					foreach($data['lecons'] AS $k=>$lecon){
						$j = 0;

						foreach($lecon['quiz'] AS $quiz){
							if (is_array($quiz)){
								$i++;
								$j++;

								$id = "quiz".$quiz['id'];
								$nom = $quiz['nom'];
								$nom = (($nom)?$nom:"Aucun nom associé à ce quiz");
								$nom = "L".($k+1)." Q".$j.") ".$nom;

								echo "<option value=\"".$id."\">".$nom."</option>";
							}
						}

						$j = 0;
						foreach($lecon['devoir'] AS $devoir){
							if (is_array($devoir)){
								$i++;
								$j++;

								$id = "devoir".$devoir['id'];
								$nom = $devoir['nom'];
								$nom = (($nom)?$nom:"Aucun nom associé à ce devoir");

								$nom = "L".($k+1)." D".$j.") ".$nom;

								echo "<option value=\"".$id."\">".$nom."</option>";
							}
						}

						$j = 0;
						foreach($lecon['tp'] AS $tp){
							if (is_array($tp)){
								$i++;
								$j++;

								$id = "tp".$tp['id'];
								$nom = $tp['nom'];
								$nom = (($nom)?$nom:"Aucun nom associé à ce tp");

								$nom = "L".($k+1)." D".$j.") ".$nom;

								echo "<option value=\"".$id."\">".$nom."</option>";
							}
						}

					}
				?>
			</select>
			<div class="clr"></div>
		</div>

		<table class="fullWidth white color_border">
			<thead>
				<tr>
					<th class=""><div class="large">Nom de l'utilisateur</div></th>
					<th class="">Date</th>
					<th class="">Action</th>
					<th class="">Détails</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$stats2human = array();
						$stats2human['openedCours'] = "Consultation du cours";
						$stats2human['openedLecon'] = "Consultation d'une leçon";
						$stats2human['remiseFichier'] = "Remise d'un devoir";
						$stats2human['remiseTP'] = "Remise d'un TP";
						$stats2human['openedQuiz'] = "Ouverture d'un quiz";
						$stats2human['remiseQuiz'] = "Soumission d'un quiz";

					foreach($data['todo'] AS $v){
						switch($v['type']){
							case 'quiz':
								$class = " sujetquiz".$v['id_quiz']." newsRadioControlledQuiz";
							break;
							case 'devoir':
								$class = " sujetdevoir".$v['id_fichier']." newsRadioControlledDevoir";
							break;
							case 'tp':
								$class = " sujetTP".$v['id_fichier']." newsRadioControlledTP";
							break;
							case 'stats':
								$class = " newsRadioControlledStats";

								if ($v['texte'] == 'remiseFichier'){
									$class .= " newsRadioControlledDevoir";
								}else if ($v['texte'] == 'remiseTP'){
									$class .= " newsRadioControlledTP";
								}else if ($v['texte'] == 'voirQuiz' || $v['texte'] == 'finirQuiz'){
									$class .= " newsRadioControlledQuiz";
								}

							break;
							default:
								$class = "";
							break;
						}

						if ($v['read'] === "0"){
							$class .= " unread";
						}

						echo "<tr class=\"newsRadioControlled uid".$v['id_user'].$class."\">";
							echo "<th class=\"\"><a href=\"enseignant/".$_GET['qs'][1]."/user/".$v['id_user']."\" class=\"openInMenuBar\">".$v['user_nom'].", ".$v['user_prenom']."</a><a class=\"small block\" href=\"mailto:".$v['user_email']."\">".$v['user_email']."</a></th>";

							switch($v['type']){
								case 'devoir':
									$lien = "enseignant/".$_GET['qs'][1]."/user/".$v['id_user']."/devoir/".$v['id'];

									echo '<td class="">'.$v['date'].'</td>';
									echo '<td class=""><a href="'.$lien.'" class="openInMenuBar">Devoir remis</a></td>';
									echo '<td class="">'.$v['details'].'</td>';
								break;
								case 'tp':
									$lien = "enseignant/".$_GET['qs'][1]."/user/".$v['id_user']."/tp/".$v['id'];

									echo '<td class="">'.$v['date'].'</td>';
									echo '<td class=""><a href="'.$lien.'" class="openInMenuBar">TP remis</a></td>';
									echo '<td class="">'.$v['details'].'</td>';
								break;
								case 'quiz':
									$lien = "enseignant/".$_GET['qs'][1]."/user/".$v['id_user']."/quiz/".$v['id'];

									echo '<td class="">'.$v['datefin'].'</td>';
									echo '<td class=""><a href="'.$lien.'" class="openInMenuBar">Quiz reçu</a></td>';
									echo '<td class="">'.$v['details'].'</td>';
								break;
								case 'stats':
									echo '<td class="">'.$v['date'].'</td>';
									echo '<td class="">'.$stats2human[$v['texte']].'</td>';
									echo '<td class="">'.$v['reftext'].'</td>';
								break;
/*								default:
									echo '<td colspan="4">'.print_r($v,1).'</td>';
								break;*/
							}
						echo "</tr>";
					}

				?>
			</tbody>
		</table>

		<div class="color_background" style="padding:4px;margin-top:-19px;">Charger plus de résultats</div>

		<?php
			}else{
				echo '	<div class="empty">&mdash; Rien à afficher &mdash;</div>';
			}
		?>

































































































	<div class="notecontainer over color_background">
		<h3>Résultats</h3>

		<?php
			if (count($data['lecons']) == 0){
				//Aucune leçon
				echo '	<div class="empty">&mdash; Aucune leçon disponible &mdash;</div>';
			}else if (count($data['users']) == 0){
				//Aucun user
				echo '	<div class="empty">&mdash; Aucun utilisateur lié à la formation &mdash;</div>';
			}else{
		?>

		<div class="radioContainer">
			<div class="radioButtonLabel">Affichage : </div>
			<div class="color_text radioButton active" data-control="radioControlledType" data-show="radioControlledTypeQuiz">Quiz</div>
			<div class="color_text radioButton" data-control="radioControlledType" data-show="radioControlledTypeDevoir">Devoirs</div>
			<div class="color_text radioButton" data-control="radioControlledType" data-show="radioControlledTypeTP">TP</div>
			<div class="color_text radioButton" data-control="radioControlledType" data-show="radioControlledType">Tous</div>
		</div>

		<div class="radioContainer">
			<div class="radioButtonLabel">Note : </div>
			<div class="color_text radioButton active" data-control="radioControlledNote" data-show="radioControlledNotePercent">%</div>
			<div class="color_text radioButton" data-control="radioControlledNote" data-show="radioControlledNoteNumber">123</div>
		</div>

		<div id="lateralscroll">
			<table class="fixed fixed1col">
				<thead>
					<tr>
						<th class="color_background"><div class="large">Nom de l'utilisateur</div></th>
						<th class="color_background">Note</th>
						<?php
							$i = 0;

							foreach($data['lecons'] AS $k=>$lecon){
								$j = 0;

								foreach($lecon['quiz'] AS $quiz){
									if (is_array($quiz)){
										$i++;
										$j++;

										$lien = "enseignant/".$_GET['qs'][1]."/quiz/".$quiz['id'];
										$nom = $quiz['nom'];
										$nom = (($nom)?$nom:"<em>Aucun nom associé à ce quiz.</em>");

										echo "<th class=\"color_background radioControlledType radioControlledTypeQuiz\"><a href=\"".$lien."\" class=\"openInMenuBar\"><div><span class=\"small\">L</span>".($k+1)."<span class=\"small\">Q</span>".$j."<div class=\"nom\"><span>".$nom."</span></div></a></div></th>";
									}
								}

								$j = 0;
								foreach($lecon['devoir'] AS $devoir){
									if (is_array($devoir)){
										$i++;
										$j++;

										$lien = "enseignant/".$_GET['qs'][1]."/devoir/".$devoir['id'];
										$nom = $devoir['nom'];
										$nom = (($nom)?$nom:"<em>Aucun nom associé à ce devoir.</em>");

										echo "<th class=\"color_background radioControlledType radioControlledTypeDevoir\"><div><a href=\"".$lien."\" class=\"openInMenuBar\"><span class=\"small\">L</span>".($k+1)."<span class=\"small\">D</span>".$j."<div class=\"nom\"><span>".$nom."</span></div></a></div></th>";
									}
								}

								$j = 0;
								foreach($lecon['tp'] AS $tp){
									if (is_array($tp)){
										$i++;
										$j++;

										$lien = "enseignant/".$_GET['qs'][1]."/tp/".$tp['id'];
										$nom = $tp['nom'];
										$nom = (($nom)?$nom:"<em>Aucun nom associé à ce TP.</em>");

										echo "<th class=\"color_background radioControlledType radioControlledTypeTP\"><div><a href=\"".$lien."\" class=\"openInMenuBar\"><span class=\"small\">L</span>".($k+1)."<span class=\"small\">D</span>".$j."<div class=\"nom\"><span>".$nom."</span></div></a></div></th>";
									}
								}
							}

							if ($i > 0){
								echo "			<th class=\"color_background\">Note</th>";
							}
						?>
					</tr>
				</thead>
				<tbody>
				<?php
					foreach($data['users'] AS $u){
						echo "<tr>";
							echo "<th class=\"color_background\"><a href=\"enseignant/".$_GET['qs'][1]."/user/".$u['id']."\" class=\"openInMenuBar\">".$u['nom'].", ".$u['prenom']."</a><a class=\"small block\" href=\"mailto:".$u['email']."\">".$u['email']."</a></th>";

			//				$noteCol = "<td><a href=\"enseignant/".$_GET['qs'][1]."/user/".$u['id']."/notes\" class=\"openInMenuBar\">".$u['noteFinaleCalcule']."<span class=\"small\">/".$u['noteFinaleValeur']."</span></a></td>";

							$note = $u['noteFinaleCalcule'];
							$valeur = $u['noteFinaleValeur'];

	//						$noteCol = "<td>".$u['noteFinaleCalcule']."<span class=\"small\">/".$u['noteFinaleValeur']."</span></td>";
							$noteCol = "<td>";
								$noteCol .= '<span class="radioControlledNote radioControlledNotePercent">'.round(($note/$valeur*100),2).'<span class="small">%</span></span>';
								$noteCol .= '<span class="radioControlledNote radioControlledNoteNumber">'.round($note*1,2).'/<span class="small">'.round($valeur*1,2).'</span></span>';
/*								$pct = round($note/$sur*100,2);
								$pct = explode(".", $pct);

								$pct2 = round($note2/$sur2*100,2);
								$pct2 = explode(".", $pct2);

								$noteCol .= '<span class="radioControlledNote radioControlledNotePercent">'.round($pct2[0],2).'<span class="small">'.(($pct2[1])?'.'.round($pct[1],2).' ':'').'%</span></span>';
								$noteCol .= '<span class="radioControlledNote radioControlledNoteNumber">'.round(($pct[0]),2).'<span class="small">'.(($pct[1])?'.'.round($pct[1],2).' ':'').'%</span></span>';*/
							$noteCol .= "</td>";

							echo $noteCol;

							$i = 0;

							foreach($data['lecons'] AS $k=>$lecon){
								foreach($lecon['quiz'] AS $quiz){
									if (is_array($quiz)){
										$i++;
										echo "<td class=\"radioControlledType radioControlledTypeQuiz\"><div>";

										if ($u['notes']['quiz'][$quiz['id']]['note']){
											$lien = "enseignant/".$_GET['qs'][1]."/user/".$u['id']."/quiz/".$quiz['id'];

											echo '<a href="'.$lien.'" class="openInMenuBar">';

											$note = $u['notes']['quiz'][$quiz['id']]['note'];
											$sur = $u['notes']['quiz'][$quiz['id']]['sur'];

											if ($u['notes']['quiz'][$quiz['id']]['noteEdit']){
												$note = $u['notes']['quiz'][$quiz['id']]['noteEdit'];
											}

//											print_r($u['notes']['quiz'][$quiz['id']]);

											$val = $u['notes']['quiz'][$quiz['id']]['valeur'];
											$noteVal = $note/$sur*$val;

	//										echo '<span class="radioControlledNote radioControlledNotePercent">'.round(($note*1),2).'/<span class="small">'.round(($sur*1),2).'</span></span>';
											echo '<span class="radioControlledNote radioControlledNotePercent">'.round((($note/$sur*100)*1),2).'<span class="small">%</span></span>';
											echo '<span class="radioControlledNote radioControlledNoteNumber">'.round($noteVal*1,2).'/<span class="small">'.round($val*1,2).'</span></span>';
	/*
											$pct = round($noteVal/$val*100,2);
											$pct = explode(".", $pct);

											$pct2 = round($note/$sur*100,2);
											$pct2 = explode(".", $pct2);

											echo '<span class="radioControlled radioControlledPct">'.round($pct[0],2).'<span class="small">'.(($pct[1])?'.'.$pct[1].' ':'').'%</span></span>';
											echo '<span class="radioControlled radioControlledPct2">'.round($pct2[0],2).'<span class="small">'.(($pct2[1])?'.'.$pct2[1].' ':'').'%</span></span>';
	//										echo '<span class="radioControlled radioControlledPct2">'.round($pct2[0],2).'/<span class="small">'.round($pct2[1],2).'%</span></span>';*/

											echo '</a>';
										}else{
											echo '<span class="grey">&mdash;</span>';
										}
										echo "</div></td>";
									}
								}

								foreach($lecon['devoir'] AS $devoir){
									if (is_array($devoir)){
										$i++;
										echo "<td class=\"radioControlledType radioControlledTypeDevoir\"><div>";

										if ($u['notes']['devoir'][$devoir['id']]['id']){

											$lien = "enseignant/".$_GET['qs'][1]."/user/".$u['id']."/devoir/".$u['notes']['devoir'][$devoir['id']]['id'];
											echo '<a href="'.$lien.'" class="openInMenuBar">';

											$note = $u['notes']['devoir'][$devoir['id']]['note'];
											$sur = 100;

											if ($note === null){
												echo '<strong class="text_color">&hellip;</strong>';
											}else{
												if ($u['notes']['devoir'][$devoir['id']]['noteEdit']){
													$note = $u['notes']['devoir'][$devoir['id']]['noteEdit'];
												}

	//											$val = $u['notes']['devoir'][$devoir['id']]['valeur'];
												$noteVal = $note/$sur*$val;

												echo '<span class="radioControlledNote radioControlledNotePercent">'.round(($note*1),2).'<span class="small">%</span></span>';
	//											echo '<span class="radioControlledNote radioControlledNoteNumber">'.round($noteVal*1,2).'/<span class="small">'.$sur.'</span></span>';
												echo '<span class="radioControlledNote radioControlledNoteNumber">'.round($noteVal*1,2).'/<span class="small">'.round($val*1,2).'</span></span>';
	/*
												$pct = round($noteVal/$val*100,2);
												$pct = explode(".", $pct);

												$pct2 = round($note/$sur*100,2);
												$pct2 = explode(".", $pct2);

												echo '<span class="radioControlled radioControlledPct">'.round($pct[0],2).'<span class="small">'.(($pct[1])?'.'.$pct[1].' ':'').'%</span></span>';
												echo '<span class="radioControlled radioControlledPct2">'.round($pct2[0],2).'/<span class="small">'.round($pct2[1],2).'%</span></span>';*/
											}

											echo '</a>';

										}else{
											echo '<span class="grey">&mdash;</span>';
										}
										echo "</div></td>";
									}
								}
							}

							if ($i > 0){
								echo $noteCol;
							}
						echo "</tr>";
					}
					echo "</tbody>";
					if (count($data['users']) > 1){
						echo "<tfoot>";
						echo "<tr>";
							echo "<th class=\"color_background\">Moyenne</th>";

							$note = $data['noteFinaleCalcule']/$data['nbNote'];
							$sur = $data['noteFinaleValeur']/$data['nbNote'];
							$note2 = $data['noteFinaleCalculeAbsolu']/$data['nbNoteAbsolu'];
							$sur2 = $data['noteFinaleValeurAbsolu']/$data['nbNoteAbsolu'];

	//						$noteCol = "<td>".$u['noteFinaleCalcule']."<span class=\"small\">/".$u['noteFinaleValeur']."</span></td>";
							$noteCol = "<td class=\"color_background\">";
								$noteCol .= '<span class="radioControlledNote radioControlledNotePercent">'.round($note2*1,2).'/<span class="small">'.round($sur2*1,2).'</span></span>';
								$noteCol .= '<span class="radioControlledNote radioControlledNoteNumber">'.round($note*1,2).'/<span class="small">'.round($sur*1,2).'</span></span>';
/*								$pct = round($note/$sur*100,2);
								$pct = explode(".", $pct);

								$pct2 = round($note2/$sur2*100,2);
								$pct2 = explode(".", $pct2);

								$noteCol .= '<span class="radioControlled radioControlledPct">'.round($pct2[0],2).'<span class="small">'.(($pct2[1])?'.'.round($pct2[1],2).' ':'').'%</span></span>';
								$noteCol .= '<span class="radioControlled radioControlledPct2">'.round($pct[0],2).'<span class="small">'.(($pct[1])?'.'.round($pct[1],2).' ':'').'%</span></span>';
//								$noteCol .= '<span class="radioControlled radioControlledPct2">'.round(($pct[0]),2).'<span class="small">'.(($pct[1])?'.'.round($pct[1],2).' ':'').'%</span></span>';*/
							$noteCol .= "</td>";

							echo $noteCol;

							$i = 0;

							foreach($data['lecons'] AS $k=>$lecon){
								foreach($lecon['quiz'] AS $quiz){
									if (is_array($quiz)){
										$i++;
										echo "<td class=\"color_background radioControlledType radioControlledTypeQuiz\"><div>";

										if ($quiz['nbNote'] > 0){
											$note = $quiz['points']/$quiz['nbNote'];
											$sur = $quiz['valeur']/$quiz['nbNote'];

											//CALCULATE THIS
											$val = $sur;
											$noteVal = $note/$sur*$val;

											echo '<span class="radioControlledNote radioControlledNotePercent">'.round(($note*1),2).'/<span class="small">'.round(($sur*1),2).'</span></span>';
											echo '<span class="radioControlledNote radioControlledNoteNumber">'.round($noteVal*1,2).'/<span class="small">'.round($val*1,2).'</span></span>';
	/*
											$pct = round($noteVal/$val*100,2);
											$pct = explode(".", $pct);

											$pct2 = round($note/$sur*100,2);
											$pct2 = explode(".", $pct2);

											echo '<span class="radioControlled radioControlledPct">'.round($pct[0],2).'<span class="small">'.(($pct[1])?'.'.$pct[1].' ':'').'%</span></span>';
											echo '<span class="radioControlled radioControlledPct2">'.round($pct2[0],2).'/<span class="small">'.round($pct2[1],2).'%</span></span>';*/

										}else{
											echo '<span class="grey">&mdash;</span>';
										}
										echo "</div></td>";
									}
								}

								foreach($lecon['devoir'] AS $devoir){
									if (is_array($devoir)){
										$i++;
										echo "<td class=\"color_background radioControlledType radioControlledTypeDevoir\"><div>";

										if ($quiz['devoir'] > 0){
											$note = $quiz['points']/$devoir['nbNote'];
											$sur = $devoir['valeur']/$quiz['nbNote'];

											//CALCULATE THIS
											$val = $sur;
											$noteVal = $note/$sur*$val;

											echo '<span class="radioControlledNote radioControlledNotePercent">'.round(($note*1),2).'/<span class="small">'.round(($sur*1),2).'</span></span>';
											echo '<span class="radioControlledNote radioControlledNoteNumber">'.round($noteVal*1,2).'/<span class="small">'.round($val*1,2).'</span></span>';
	/*
											$pct = round($noteVal/$val*100,2);
											$pct = explode(".", $pct);

											$pct2 = round($note/$sur*100,2);
											$pct2 = explode(".", $pct2);

											echo '<span class="radioControlled radioControlledPct">'.round($pct[0],2).'<span class="small">'.(($pct[1])?'.'.$pct[1].' ':'').'%</span></span>';
											echo '<span class="radioControlled radioControlledPct2">'.round($pct2[0],2).'/<span class="small">'.round($pct2[1],2).'%</span></span>';*/
										}else{
											echo '<span class="grey">&mdash;</span>';
										}

										echo "</div></td>";
									}
								}
							}

							if ($i > 0){
								echo $noteCol;
							}
						echo "</tr>";	
						echo "</tfoot>";				
					}
				?>
			</table>
		</div>
		<?php
			}
		?>
		<div class="clr"></div>
	</div>