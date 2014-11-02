<header>
	<h1>Administration</h1>
	<h2>Entreprises</h2>
</header>

<?php
	echo '<div class="banner color_background">';
		if ($canEdit){
			echo '<a class="btn openInMenuBar" href="admin/entreprise/nouveau">+</a>';
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
			echo '<li class="moreDetails" id="cieId'.$d['id'].'">';
				echo '<h4>';
					echo $d['nom'];
					if (USE_USERCODE == "true"){
						if ($d['usercode']){
							echo " (".$d['usercode'].")";
						}
					}else{
						echo " (".$d['mail'].")";
					}
				echo '</h4>';
				echo '<ul>';
					echo '<li>';
						echo $d['niveau'];
					echo '</li>';
					
					echo '<li>';
					if ($d['nbUser'] == 0){
						echo '<a href="admin/entreprise/'.$d['id'].'/users/nouveau" class="openInMenuBar">';
							echo '0 utilisateur';
						echo '</a>';
					}else{
						echo '<a href="admin/entreprise/'.$d['id'].'/users" class="openInRightBar">';
							echo $d['nbUser'].' utilisateur'.(($d['nbUser'] > 1)?'s':'');
						echo '</a>';
					}
					echo '</li>';

					echo '<li>';
					if ($d['nbCours'] == 0){
						echo '<a href="admin/entreprise/'.$d['id'].'/cours/nouveau" class="openInMenuBar">';
							echo '0 cours';
						echo '</a>';
					}else{
						echo '<a href="admin/entreprise/'.$d['id'].'/cours" class="openInRightBar">';
							echo $d['nbCours'].' cours';
						echo '</a>';
					}
					echo '</li>';

					echo '<li>';
						echo '<a href="admin/entreprise/'.$d['id'].'" class="openInMenuBar">';
							echo "Modifier l'entreprise";
						echo "</a>";
					echo '</li>';
				echo '</ul>';
			echo '</li>';
		}
	echo '</ul>';
?>