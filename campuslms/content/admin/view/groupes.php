<header>
	<h1>Administration</h1>
	<h2>Groupes</h2>
</header>

<?php
	echo '<div class="banner color_background">';
//		if ($canEdit){
		if ($_SESSION['accountType'] != 'cie'){
			echo '<a class="btn openInMenuBar" href="admin/groupes/nouveau">+</a>';
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
			echo '<li class="moreDetails" id="groupId'.$d['id'].'">';
				echo '<h4>'.$d['nom'].'</h4>';
				echo '<ul>';
					echo '<li>';
						if ($d['nbUser'] == 0 && $canEdit){
							echo '<a href="admin/groupes/'.$d['id'].'/users/nouveau" class="openInMenuBar">';
								echo '0';
								if ($d['nblicenses']){
									echo "/".$d['nblicenses'];
								}
								echo ' utilisateur';
							echo '</a>';
						}else{
							echo '<a href="admin/groupes/'.$d['id'].'/users" class="openInRightBar">';
								echo $d['nbUser'];
								if ($d['nblicenses']){
									echo "/".$d['nblicenses'];
								}
								echo ' utilisateur'.(($d['nbUser'] > 1)?'s':'');
							echo '</a>';
						}
					echo '</li>';
					if ($_SESSION['accountType'] == 'campus'){
						echo '<li>';
							if ($d['nbFormation'] == 0 && $canEdit){
								echo '<a href="admin/groupes/'.$d['id'].'/formations/nouveau" class="openInMenuBar">';
									echo '0 formation';
								echo '</a>';
							}else{
								echo '<a href="admin/groupes/'.$d['id'].'/formations" class="openInRightBar">';
									echo $d['nbFormation'].' formation'.(($d['nbFormation'] > 1)?'s':'');
								echo '</a>';
							}
						echo '</li>';
						echo '<li>';
							echo '<a href="admin/groupes/'.$d['id'].'/message" class="openInMenuBar">';
								echo 'Message au groupe';
							echo '</a>';
						echo '</li>';
						echo '<li>';
							echo '<a href="admin/groupes/'.$d['id'].'" class="openInMenuBar">';
								echo 'Modifier le groupe';
							echo '</a>';
						echo '</li>';
					}
				echo '</ul>';
			echo '</li>';
		}
	echo '</ul>';
?>