<header>
	<h1>Administration</h1>
	<h2>Cours</h2>
</header>

<?php
	echo '<div class="banner color_background">';
//		if ($canEdit){
		if ($_SESSION['accountType'] == 'campus'){
			echo '<a class="btn openInMenuBar" href="admin/cours/nouveau">+</a>';
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
			echo '<li class="moreDetails" id="courId'.$d['id'].'">';
				echo '<h4>'.$d['nom'].(($d['type'] == 'standard')?'':' *').'</h4>';
				echo '<ul>';
/*					echo '<li>';
						if ($d['nbGroupe'] == 0 && $canEdit){
							echo '<a href="admin/cours/'.$d['id'].'/groupes/nouveau" class="openInMenuBar">';
								echo '0 groupe';
							echo '</a>';
						}else{
							echo '<a href="admin/cours/'.$d['id'].'/groupes" class="openInRightBar">';
								echo $d['nbGroupe'].' groupe'.(($d['nbGroupe'] > 1)?'s':'');
							echo '</a>';
						}
					echo '</li>';*/
					echo '<li>';
						echo '<a href="admin/cours/'.$d['id'].'" class="openInMenuBar">';
							echo 'Modifier le cours';
						echo '</a>';
					echo '</li>';
				echo '</ul>';
			echo '</li>';
		}
	echo '</ul>';
?>