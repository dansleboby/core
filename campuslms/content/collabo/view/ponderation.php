<?php
	if ($data['error']){
		echo "<h1>Erreur</h1>";
		echo "<p>Un problème est survenu. Veuillez réessayer.</p>";
		return;
	}
?>
<!--<header>
	<h1>Administration</h1>
	<h2>Nouveau cours</h2>
</header>-->
<form method="post" action="<?php echo $_GET['data']; ?>">
	<?php
	if ($data['saved']){
		?>
			<h1>Pondération</h1>
			<h2>Cours #<?php echo $_GET['qs'][1]; ?></h2>
			<div class="clr"></div>

			<p>Les modifications ont été appliquées et sont effectives immédiatement.</p>
		<?php
	}else{
		?>
			<h1>Pondération</h1>
			<h2>Cours #<?php echo $_GET['qs'][1]; ?></h2>
			<div class="clr"></div>

			<input type="submit" class="submit" value="Sauvegarder"/>
		<?php

		echo "<table class=\"fullWidth reverseBackground\">";
			echo "<thead>";
				echo "<tr>";
					echo "<th>Élément</th>";
					echo "<th width=\"100\">Valeur</th>";
				echo "</tr>";
			echo "</thead>";
			echo "<tbody>";
				$i = 0;
				foreach($data AS $res){
					$i++;

					$j = 0;
					foreach($res['quiz'] AS $quiz){
						$j++;

						echo "<tr>";
							echo "<th><label for=\"valeurquiz".$quiz['id']."_".$res['id']."\">Leçon ".$i.", Quiz ".$j."<br/>".$quiz['nom']."</label></th>";
							echo "<th><input class=\"text small\" type=\"text\" value=\"".($quiz['valeur']*1)."\" name=\"valeurquiz".$quiz['id']."_".$res['id']."\" id=\"valeurquiz".$quiz['id']."_".$res['id']."\"/></th>";
						echo "</tr>";
					}

					$j = 0;
					foreach($res['devoir'] AS $devoir){
						$j++;
						echo "<tr>";
							echo "<th><label for=\"valeurdevoir".$devoir['id']."_".$res['id']."\">Leçon ".$i.", Devoir ".$j."<br/>".$devoir['nom']."</label></th>";
							echo "<th><input class=\"text small\" type=\"text\" value=\"".($devoir['valeur']*1)."\" name=\"valeurdevoir".$devoir['id']."_".$res['id']."\" id=\"valeurdevoir".$devoir['id']."_".$res['id']."\"/></th>";
						echo "</tr>";
					}
				}
			echo "</tbody>";
		echo "</table>";

		echo "<input type=\"hidden\" value=\"1\" name=\"save\"/>";
	}
	?>
	<a class="cancel" href="#">Annuler</a>
</form>