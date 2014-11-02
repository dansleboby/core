<form>
	<h1>Travaux pratiques à corriger</h1>
	<div class="clr"></div>
	<a class="cancel" href="#">Annuler</a>

	<?php
		$i = 0;
		foreach($data['fichier'] AS $res){
			if ($i == 0){
				echo '<table style="width:100%;">';
					echo '<tr>';
						echo '<th>Nom</th>';
						echo '<th>Utilisateur</th>';
						echo '<th>Note</th>';
						echo '<th>Date reçu</th>';
					echo '</tr>';
			}
			$i++;
			echo '<tr>';
				echo '<td>'.$res['nomFichier'].'</td>';
				echo '<td>'.$res['nom'].', '.$res['prenom'].'</td>';
				echo '<td><input type="text" name="note'.$res['idRemise'].'" id="note'.$res['idRemise'].'" value=""/>%</td>';
				echo '<td>'.$res['dateRecu'].'</td>';
			echo '</tr>';
		}
		if ($i > 0){
			echo '</table>';
		}else{
			echo "Aucun TP en attente de correction";
		}
	?>
</form>