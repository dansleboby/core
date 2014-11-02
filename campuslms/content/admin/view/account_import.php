<!--<header>
	<h1>Administration</h1>
	<h2>Nouveau cours</h2>
</header>-->

<form method="post" class="openinmenubar" data-onsubmit="validateImport" onsubmit="return validateImport();" action="<?php echo implode('/', $_GET['qs']); ?>">
	<h1>Importation d'utilisateurs multiples</h1>
	<div class="clr"></div>
	<?php
		$typesLabel = array();
		if (USE_USERCODE == "true"){
			$typesLabel['usercode'] = 'Code interne';
		}
		$typesLabel['email'] = 'Courriel';
		$typesLabel['password'] = 'Mot de passe';
		$typesLabel['firstname'] = 'Prenom';
		$typesLabel['lastname'] = 'Nom de famille';


		switch($data['step']){
			case 2:
				//Étape 2 (Identification)
				if ($data['min'] != $data['max']){
					echo "<strong>Impossible d'analyser le fichier CSV - Assurez-vous que le nombre de champ par ligne est constant.</strong>";
				}else{
					$showForm = false;

//					echo "<h2>2) Identification des données</h2>";


					echo "<p>Veuillez indiquer le contenu de chacune des colonnes. Si des lignes sont superflues, décochez-les.</p>";

					if (USE_USERCODE == "true"){
//						echo "<p>Merci d'indiquer le type d'information contenue dans chacune des lignes du tableau à l'aide des listes déroulante. Pour continuer, vous devez au minimum identifier les colonnes permettant l'identification des utilisateurs, soit le code interne ou le courriel ainsi que le mot de passe</p>";
					}else{
//						echo "<p>Merci d'indiquer le type d'information contenue dans chacune des lignes du tableau à l'aide des listes déroulante. Pour continuer, vous devez au minimum identifier les colonnes permettant l'identification des utilisateurs, soit le courriel ainsi que le mot de passe</p>";
					}
//					echo "<p>Si des lignes sont superflues, décochez-les afin de ne pas les importer.</p>";

					echo "<table class=\"colorth\">";
						echo "<thead>";
/*							echo "<tr>";
								echo "<th colspan=\"2\">Champ #</th>";
								for ($i=1;$i<=$max;$i++){
									echo "<th>".$i."</th>";
								}
							echo "</tr>";*/
							echo "<tr>";
								echo "<th colspan=\"2\">Type</th>";
								for ($i=0;$i<$data['max'];$i++){
									echo "<th>";
										echo "<select class=\"dataTypeList\" style=\"width:70px;\" name=\"data".$i."type\">";
											echo '<option value="none">Aucune</option>';
											foreach($typesLabel AS $k=>$v){
												echo '<option value="'.$k.'">'.$v.'</option>';		
											}
										echo "</select>";
									echo "</th>";
								}
							echo "</tr>";
						echo "</thead>";
						echo "<tbody>";
							for($i=0;$i<count($data['data']);$i++){
								echo "<tr>";
									echo "<th><input type=\"checkbox\" name=\"line".$i."Enabled\" id=\"line".$i."Enabled\" value=\"1\" checked=\"checked\"/></th>";
									echo "<th><label for=\"line".$i."Enabled\">".($i+1)."</label></th>";
									foreach ($data['data'][$i] AS $theData){
										echo "<td>".$theData."</td>";
									}
								echo "</tr>";
							}
						echo "</tbody>";
					echo "</table>";

					foreach($_POST AS $k=>$v){
						if ($k != 'importstep'){
							echo '<input type="hidden" name="'.htmlspecialchars($k).'" value="'.htmlspecialchars($v).'"/>';
						}
					}

					echo '<input type="hidden" id="importstep" name="importstep" value="2"/>';

					echo '<input class="submit" type="submit"/>';
					echo '<a class="cancel" href="#">Annuler</a>';

//					echo "<pre>".$min." / ".$max."\n\r".print_r($data,true)."</pre>";
				}
			break;
			case 4:
				//Étape 4 (sauvegarde)

					echo "Importation terminée. Les utilisateurs ont été importés.";

//					echo "<pre>".print_r($_POST,1)."</pre>";

					echo '<a class="cancel" href="#">Annuler</a>';
			break;
			case 3:
				//Étape 3 (confirmation)

				$nbErr = count($data['malformed']) + count($data['exists']);
				if (0){
//					echo "Une erreur est survenue";
//					echo '<a class="cancel" href="#">Annuler</a>';
				}else if ($nbErr > 0){
					//Ask to repair malformed / exists things

					if ($nbErr > 1){
						echo "<p>Un problème a été détecté les entrées suivantes.</p>";
					}else{
						echo "<p>Un problème a été détecté l'entrée suivante.</p>";
					}

					echo "<table class=\"colorth\">";
						echo "<thead>";
/*							echo "<tr>";
								echo "<th colspan=\"2\">Champ #</th>";
								for ($i=1;$i<=$max;$i++){
									echo "<th>".$i."</th>";
								}
							echo "</tr>";*/
							echo "<tr>";
								echo "<th>Action</th>";
								foreach($typePos AS $k=>$v){
									if ($v > -1){
										echo "<th>".$typesLabel[$k]."</th>";
									}
								}
							echo "</tr>";
						echo "</thead>";
						echo "<tbody>";
							for($i=0;$i<count($data['data']);$i++){
								if ($data['malformed'][$i] || $data['exists'][$i]) {
									echo "<tr>";
										echo "<td>";
											$errors = array();

											if ($data['malformed'][$i]){
												$errors['emailMalformed'] = "emailMalformed";
											}

											if ($data['exists'][$i]){
												if ($data['exists'][$i]['email']){
													$errors['emailExists'] = $data['exists'][$i]['email'];
												}
												if ($data['exists'][$i]['usercode']){
													$errors['usercodeExists'] = $data['exists'][$i]['usercode'];
												}
											}

											if ($errors['usercodeExists'] && $errors['emailExists'] && $errors['usercodeExists'] == $errors['emailExists']){
												$errors['userExists'] = $errors['usercodeExists'];
												unset($errors['usercodeExists']);
												unset($errors['emailExists']);
											}


											foreach($errors AS $k=>$v){
												echo "- ";

												switch($k){
													case 'emailMalformed':
														echo "Courriel erroné";
													break;
													case 'userExists':
														echo "Utilisateur déjà présent";
													break;
													case 'usercodeExists':
														echo "Code déjà utilisé";
													break;
													case 'emailExists':
														echo "Courriel déjà utilisé";
													break;
												}

												echo "<br/>";
											}

											echo "<select class=\"dataFix\" style=\"width:120px;\" name=\"data".$i."Action\">";
//												echo '<option value="empty">&mdash; Sélectionner une action &mdash;</option>';
												echo '<option value="delete">Ne pas importer</option>';
/*												if ($errors['emailMalformed']){
													echo '<option value="fixmail">Modifier le courriel</option>';
												}
												if ($errors['emailExists']){
													echo '<option value="useUser'.$errors['emailExists'].'">Sélectionner l\'utilisateur ayant ce courriel</option>';
												}
												if ($errors['usercodeExists']){
													echo '<option value="useUser'.$errors['usercodeExists'].'">Sélectionner l\'utilisateur ayant ce code interne</option>';
												}
												if ($errors['userExists']){
													echo '<option value="useUser'.$errors['userExists'].'">Sélectionner l\'utilisateur existant</option>';
												}*/
											echo "</select>";
										echo "</td>";

										foreach($typePos AS $k=>$v){
											if ($v > -1){
												echo "<td>".$data['data'][$i][$v]."</td>";
											}
										}

									echo "</tr>";
								}
							}
						echo "</tbody>";
					echo "</table>";

					//Add everything hidden
					foreach($_POST AS $k=>$v){
						if ($k != 'importstep'){
							echo '<input type="hidden" name="'.htmlspecialchars($k).'" value="'.htmlspecialchars($v).'"/>';
						}
					}

					echo '<input type="hidden" id="importstep" name="importstep" value="2"/>';

					echo '<input class="submit" type="submit"/>';
					echo '<a class="cancel" href="#">Annuler</a>';
				}else{
					//Ask what to do with the users (nothing, add to group X, etc)

					echo "<p>Que désirez-vous faire avec les utilisateurs sélectionnés ?</p>";

					$salt = uniqid();
					echo '<input type="hidden" name="salt" id="salt" value="'.$salt.'"/>';

					echo "<select name=\"importGroup\">";
						echo '<option value="'.createValidateKey("0",$salt).'">Importer seulement</option>';
						foreach($data['groupes'] AS $res){
							echo '<option value="'.createValidateKey($res['id'],$salt).'">Importer et ajouter au groupe "'.$res['nom'].'"</option>';
						}
					echo "</select>";

					foreach($_POST AS $k=>$v){
						if ($k != 'importstep'){
							echo '<input type="hidden" name="'.htmlspecialchars($k).'" value="'.htmlspecialchars($v).'"/>';
						}
					}

					echo '<input type="hidden" id="importstep" name="importstep" value="3"/>';

//					echo "step3 - No error detected. What do you want to do with those users ?";
					echo '<input class="submit" type="submit"/>';
					echo '<a class="cancel" href="#">Annuler</a>';

				}
			break;
			default:
				//Étape 1 (demande d'information initiale)
				?>
					<p>Veuillez entrer le contenu de votre fichier CSV ci-dessous.</p>

<!--					<div class="tab" onclick="showTab('byfile');">Valider par <strong>téléversement</strong></div>
					<div class="tab active" onclick="showTab('byinput');">Valider par <strong>Entrée directe</strong></div>!-->
					<div class="box">
						<div id="byinput" class="line">
							<label for="csv">Code CSV</label>
							<textarea name="csv" id="csv"><?php echo $_POST['csv']; ?></textarea>
						</div>
<!--						<div id="byfile" class="line">
							<label for="file">Fichier</label>
							<input type="file" name="file" id="file"/>
						</div>
						<a id="showMoreBtn" href="javascript:showMore();" onclick="return showMore()">Plus d'options</a>!-->
						<div id="more">
							<div class="smallline" style="width:33%;float:left;">
								<label for="delimiter">Séparateur</label>
								<input style="width:auto;" class="text" type="text" name="delimiter" id="delimiter" value=","/>
							</div>
							<div class="smallline" style="width:33%;float:left;">
								<label for="enclosure">Caractère de délimitation</label>
								<input style="width:auto;" class="text" type="text" name="enclosure" id="enclosure" value='"'/>
							</div>
							<div class="smallline" style="width:33%;float:left;">
								<label for="escape">Caractère d'échappement</label>
								<input style="width:auto;" class="text" type="text" name="escape" id="escape" value="\"/>
							</div>
						</div>
					</div>

					<input class="submit" type="submit"/>
				<?php
				echo '<a class="cancel" href="#">Annuler</a>';
			break;
		}
	?>


</form>