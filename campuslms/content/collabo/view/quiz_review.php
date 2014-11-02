<!--<header>
	<h1>Administration</h1>
	<h2>Nouveau cours</h2>
</header>-->

<form method="post" action="">
	<h1>Historique</h1>
	<div class="clr"></div>

	<table width="100%">
		<tr>
			<th>Date</th>
			<th>Note</th>
			<th>Durée</th>
		</tr>
	<?php
		foreach($data['review'] AS $res){
			echo "<tr>";
				echo "<td>".$res['datefin']."</td>";
				echo "<td>".($res['pointage']*1)."/".($res['valeur']*1)."</td>";

				$datefin = strtotime($res['datefin']);
				$datedebut = strtotime($res['datedebut']);
				$delta = $datefin - $datedebut;

				$min = floor($delta/60);
				$sec = $delta%60;

				echo "<td>";
					if ($min > 0){
						echo $min." minute".(($min>1)?'s':'').", ";
					}
					echo $sec." seconde".(($sec>1)?'s':'');
				echo "</td>";
//				echo "<td>".$res['datefin']."</td>";
			echo "</tr>";
		}
	?>
	</table>

	<a class="cancel" href="#">Annuler</a>
</form>