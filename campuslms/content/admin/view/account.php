<header>
	<h1>Administration</h1>
	<h2>Utilisateurs <?php echo (($_GET['qs'][2] == "cie")?' d\'entreprise':''); ?>
</h2>
</header>

	<ul id="headerOptions">
		<li>
			<?php
				if ($_SESSION['accountType'] == 'campus'){
					if ($_GET['qs'][2] != "cie"){
						echo '<a class="btn color_background openInContentPane" href="admin/account/cie">Afficher les <strong>comptes d\'entreprise</strong></a>';
					}else{
						echo '<a class="btn color_background openInContentPane" href="admin/account">Afficher les <strong>comptes standards</strong></a>';
					}
				}
			?>
		</li>
	</ul>

<?php
	echo '<div class="banner color_background">';
		if ($canEdit && $_GET['qs'][2] != "cie"){
			if ($_SESSION['accountType'] == 'campus'){
				echo '<a class="btn openInMenuBar" href="admin/account/nouveau">+</a>';
			}else{
				echo '<a class="btn openInMenuBar" href="admin/entreprise/'.$_SESSION['id_cie'].'/users/nouveau">+</a>';
			}
		}
		echo '<input id="filter" data-conteneur="ul.letterList" data-quoi="li" data-ou="h4" type="text" placeholder="filtrer..."/>';
	echo '</div>';

	echo '<ul class="letterList">';
		$lastLetter = null;

		foreach($data AS $d){
			$firstLetter = substr(stripAccents($d['nom']), 0,1);

			if ($lastLetter != $firstLetter){
				$lastLetter = $firstLetter;

				echo '<li class="header"><h3 class="color_background" id="letter'.$lastLetter.'">'.$lastLetter.'</h3></li>';
			}
			echo '<li class="moreDetails" id="accountId'.$d['id'].'">';
				echo '<h4>';
					if ($d['nom'] && $d['prenom']){
						echo $d['nom'].", ".$d['prenom'];
					}else{
						echo "<i>SANS NOM</i>";
					}
					if (USE_USERCODE == "true"){
						if ($d['usercode']){
							echo " (".$d['usercode'].($d['mail']?" | ".$d['mail']:'').")";
						}else{
							echo " (".$d['mail'].")";
						}
					}else{
						echo " (".$d['mail'].")";
					}
				echo '</h4>';
				echo '<ul>';
					echo '<li>';
						echo $d['niveau'];
					echo '</li>';
					
					if ($d['niveau'] == 'etudiant' && $d['id_cie'] == 0){
						echo '<li>';
							if ($d['nbGroupe'] == 0 && $canEdit){
								echo '<a href="admin/account/'.$d['id'].'/groupes/nouveau" class="openInMenuBar">';
									echo '0 groupe';
								echo '</a>';
							}else{
								echo '<a href="admin/account/'.$d['id'].'/groupes" class="openInRightBar">';
									echo $d['nbGroupe'].' groupe'.(($d['nbGroupe'] > 1)?'s':'');
								echo '</a>';
							}
						echo '</li>';

						echo '<li>';
							if ($d['nbCours'] == 0 && $canEdit){
								echo '<a href="admin/account/'.$d['id'].'/cours/nouveau" class="openInMenuBar">';
									echo '0 cours';
								echo '</a>';
							}else{
								echo '<a href="admin/account/'.$d['id'].'/cours" class="openInRightBar">';
									echo $d['nbCours'].' cours';
								echo '</a>';
							}
						echo '</li>';
					}

					if ($d['niveau'] == 'enseignant'){
						echo '<li>';
							echo '<a href="admin/account/'.$d['id'].'/profile" class="openInMenuBar">';
								echo "Gérer le profil";
							echo "</a>";
						echo '</li>';

					}

					echo '<li>';
						if ($d['id_cie'] == 0){
							echo '<a href="admin/account/'.$d['id'].'" class="openInMenuBar">';
						}else{
							echo '<a href="admin/entreprise/'.$d['id_cie'].'/users/'.$d['id'].'" class="openInMenuBar">';
						}
							echo "Modifier l'utilisateur";
						echo "</a>";
					echo '</li>';
				echo '</ul>';
			echo '</li>';
		}
	echo '</ul>';
?>