<form method="post" action="<?php echo $_GET['data']; ?>" class="openInMenuBar">

	<input type="hidden" name="action" value="saveNotes"/>
	<h1>Notes de l'utilisateur</h1>
		<div class="clr"></div>
		<a class="cancel" href="#">Annuler</a>

	<table style="width:100%;">
		<tr>
			<th>Activité</th>
			<th>Note</th>
			<th>Note ajustée</th>
			<th>Valeur</th>
			<th>Date reçu</th>
		</tr>
		<?php
			$i = 1;

			$eachVal = 0;
			foreach($data['notes'] AS $leconId=>$v){
				foreach($v AS $type=>$v2){
					foreach($v2 AS $res){
						$eachVal++;
					}
				}
			}

			$eachVal = round(100/$eachVal,5);

			$sub = 0;
			$sur = 0;

			foreach($data['notes'] AS $leconId=>$v){
				foreach($v AS $type=>$v2){
					$nbs = array();
					$nbs['q'] = 1;
					$nbs['d'] = 1;
					foreach($v2 AS $res){
						$label = "<span class=\"small\">L</span>".$i;
						$notNoted = false;

						switch($type){
							case 'quiz':
//								$lien = implode('/',$_GET['qs'])."/quiz/".$res['id'];
								$lien = "enseignant/".$_GET['qs'][1]."/user/".$_GET['qs'][3]."/quiz/".$res['id'];
								$label .= "<span class=\"small\">Q</span>".$nbs['q']." - ".$res['nom'];
								$nbs['q']++;

								$noteOri = $res['pointage']*1;
								$valeur = $eachVal*1;
								$noteEdit = $res['noteEdited']?$res['noteEdited']:$noteOri;
								if ($res['valeur'] > 0){
									$note = round($noteEdit/$res['valeur']*$valeur,2);
								}else{
									$note = 0;
								}
								$date = $res['datefin'];
								$inputName = "note/".$leconId."/".$type."/".$res['id']."/".$res['refid'];
							break;
							case 'devoir':
//								$lien = implode('/',$_GET['qs'])."/devoir/".$res['id'];
								$lien = "enseignant/".$_GET['qs'][1]."/user/".$_GET['qs'][3]."/devoir/".$res['id'];
								$label .= "<span class=\"small\">D</span>".$nbs['d']." - ".$res['nom'];
								$nbs['d']++;

								$noteOri = $res['note']*1;
								$valeur = $eachVal*1;
								$noteEdit = $res['noteEdited']?$res['noteEdited']:$noteOri;
								$note = round($noteEdit/$res['valeur']*$valeur,2);
								$date = $res['date'];
								$inputName = "note/".$leconId."/".$type."/".$res['id']."/".$res['refid'];

								//If devoir not yet noted
								if ($res['note'] === null){
									$date = null;
									$notNoted = true;
								}
							break;
						}

						$sub += $note;
						$sur += $valeur;

						echo '<tr>';
							echo '<td><a href="'.$lien.'" class="openInMenuBar">'.$label.'</a></td>';
							echo '<td>';
								if ($notNoted && $res['dateNote']) {
									$lien = "enseignant/".$_GET['qs'][1]."/user/".$_GET['qs'][3]."/devoir/".$res['refid']."";
									echo '<a href="'.$lien.'" class="openInMenuBar">&hellip;</a>';
//									echo "&hellip;";
								}else if ($date){
									echo $noteOri.'/'.$res['valeur']*1;
								}else{
									echo '-';
								}
							echo '</td>';
							echo '<td>';
								if ($date){
									echo '<input class="subtext" type="text" name="'.$inputName.'" value="'.($noteEdit*1).'"/>/'.$res['valeur']*1;
									echo '<input type="hidden" name="prev'.$inputName.'" value="'.($noteEdit*1).'"/>';
								}else{
									echo '-';
								}
							echo '</td>';
							echo '<td>';
								if ($date){
									echo round($note,2).'/'.round($valeur,2);
								}else{
									echo '-';
								}
							echo '</td>';
							echo '<td>';
								if ($date){
									echo $date;
								}else{
									echo "-";
								}
							echo '</td>';
						echo '</tr>';
					}
				}
				$i++;
			}

			echo '<tr>';
				echo '<th>Total</th>';
				echo '<td>'.$data['noteFinale']['calcule'].'/'.$data['noteFinale']['valeur'].'</td>';
				echo '<td>'.$data['noteFinale']['edited'].'/'.$data['noteFinale']['valeur'].'</td>';
				echo '<td>'.round($sub,2).'/'.round($sur,2).'</td>';
				echo '<td>';
					echo "-";
				echo '</td>';
			echo '</tr>';
		?>
	</table>
	<input type="submit" class="submit" value="Sauvegarder"/>
</form>