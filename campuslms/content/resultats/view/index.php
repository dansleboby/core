<header>
	<h1>Tableau</h1>
	<h2>Mes résultats</h2>
</header>

<?php
//echo "<pre>".print_r($data,true)."</pre>";


//echo "<pre>".print_r($toLoad,1)."</pre>";
if (count($data['cours']) >= 1){
	echo '<div>';
		foreach($data['cours'] AS $res){
			echo '<div class="table">';
				echo '<div class="titlebar color_border" data-for="'.$res['fid'].'">';
					echo '<div class="nom">';
						echo "<h3>".$res['nomcours']."</h3>";
						if ($_SESSION['accountType'] == 'campus'){
							echo "<p class=\"small\">Groupe ".$res['nomgroupe']."</p>";
						}
					echo '</div>';
					if ($res['note']){
						echo "<div class=\"note\">".round($res['note'],2)."<span class=\"small\"> / ".$res['note_valeur']."</span></div>";
					}
				echo '</div>';

				echo '<div class="content" id="resultats'.$res['fid'].'">';
					echo '<table class="color_background">';
						echo "<thead>";
							echo "<tr>";
								echo "<th>Activité</th>";
								echo "<th>Note</th>";
								echo "<th>Moyenne</th>";
							echo "</tr>";
						echo "</thead>";
						echo "<tbody>";

						$moyenne_finale = null;
						$moyenne_sur = null;

							$l = 0;
							foreach($res['lecons'] AS $k=>$lecon){
								$l++;
								$dnb = 1;
								$qnb = 1;
								$tpnb = 1;

								foreach($lecon['quiz'] AS $quiz){
									if (is_array($quiz)){
										$nom = $quiz['nom'];
										$nom = (($nom)?$nom:'<em>Sans titre</em>');
										echo "<tr>";
											echo '<th><span class="small">Leçon '.($l).', Quiz '.$qnb.'</span>'.$nom.'</th>';

											$note = $res['data']['quiz'][$quiz['id']]['note'];
											$moyenne = $res['data']['quiz'][$quiz['id']]['moyenne'];

											$valeur = $res['data']['quiz'][$quiz['id']]['valeur'];

											if (!$note){
												$note = "&mdash;";
											}else{
												$note = round($note,2);
											}

											$valeur = round($valeur,2);

											if (!$moyenne){
												$moyenne = "&mdash;";
											}else{
												$moyenne /= $res['data']['quiz'][$quiz['id']]['moyenne_nb'];
												$moyenne = round($moyenne,2);
												$moyenne_finale += $moyenne;
												$moyenne_sur += $valeur;
											}

											echo '<td>'.$note.'<span class="small"> / '.$valeur.'</span></td>';
											echo '<td>'.$moyenne.'<span class="small"> / '.$valeur.'</span></td>';
										echo "</tr>";
										$qnb++;
									}
								}

								foreach($lecon['tp'] AS $tp){
									if (is_array($tp)){
										$valeur = $res['data']['tp'][$tp['id']]['valeur'];

										if ($valeur > 0){
											$nom = $tp['nom'];
											$nom = (($nom)?$nom:'<em>Sans titre</em>');
											echo "<tr>";
												echo '<th><span class="small">Leçon '.($l).', TP '.$tpnb.'</span>'.$nom.'</th>';

												$note = $res['data']['tp'][$tp['id']]['note'];
												$moyenne = $res['data']['tp'][$tp['id']]['moyenne'];


												if (!$note){
													$note = "&mdash;";
												}else{
													$note = round($note,2);
												}

												$valeur = round($valeur,2);

												if (!$moyenne){
													$moyenne = "&mdash;";
												}else{
													$moyenne /= $res['data']['tp'][$tp['id']]['moyenne_nb'];
													$moyenne = round($moyenne,2);
													$moyenne_finale += $moyenne;
													$moyenne_sur += $valeur;
												}

												echo '<td>'.$note.'<span class="small"> / '.$valeur.'</span></td>';
												echo '<td>'.$moyenne.'<span class="small"> / '.$valeur.'</span></td>';
											echo "</tr>";
										}
										$tpnb++;
									}
								}

								foreach($lecon['devoir'] AS $devoir){
									if (is_array($devoir)){
										$valeur = $res['data']['devoir'][$devoir['id']]['valeur'];
										if ($valeur > 0){
											$nom = $devoir['nom'];
											$nom = (($nom)?$nom:'<em>Sans titre</em>');
											echo "<tr>";
												echo '<th><span class="small">Leçon '.($l).', Devoir '.$dnb.'</span>'.$nom.'</th>';

												$note = $res['data']['devoir'][$devoir['id']]['note'];
												$moyenne = $res['data']['devoir'][$devoir['id']]['moyenne'];


												if (!$note){
													$note = "&mdash;";
												}else{
													$note = round($note,2);
												}

												$valeur = round($valeur,2);

												if (!$moyenne){
													$moyenne = "&mdash;";
												}else{
													$moyenne /= $res['data']['devoir'][$devoir['id']]['moyenne_nb'];
													$moyenne = round($moyenne,2);
													$moyenne_finale += $moyenne;
													$moyenne_sur += $valeur;
												}

												echo '<td>'.$note.'<span class="small"> / '.$valeur.'</span></td>';
												echo '<td>'.$moyenne.'<span class="small"> / '.$valeur.'</span></td>';
											echo "</tr>";
										}
										$dnb++;
									}
								}
							}

							echo "<tr>";
								echo "<th>Note finale</th>";
								if ($res['note']){
									echo "<td>".round($res['note'],2)."<span class=\"small\"> / ".$res['note_valeur']."</span></td>";
								}else{
									echo "<td>&mdash;</td>";
								}


								if ($moyenne_finale){
									echo "<td>".round($moyenne_finale,2)."<span class=\"small\"> / ".$moyenne_sur."</span></td>";
								}else{
									echo "<td>&mdash;</td>";
								}
							echo "</tr>";
						echo "</tbody>";
					echo "</table>";
				echo '</div>';
			echo '</div>';
		}
	echo '</div>';
}else{
	echo "Aucun résultat disponible.";
}
?>