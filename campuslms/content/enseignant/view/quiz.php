<form method="post" action="<?php echo $_GET['data']; ?>" class="openInMenuBar">

	<input type="hidden" name="action" value="saveNotes"/>
	<h1>Détails du quiz</h1>
		<div class="clr"></div>
		<a class="cancel" href="#">Annuler</a>

	<table style="width:100%;">
		<tr>
			<th>Utilisateurs</th>
			<th>Note</th>
			<th>Note ajustée</th>
			<th>Voir</th>
			<th>Date reçu</th>
			<th>Temps</th>
		</tr>
		<?php
			foreach($data['users'] AS $res) {
				echo '<tr>';
					echo '<th>'.$res['nom'].', '.$res['prenom'].'</th>';
					if ($res['note']['datefin'] > 0) {
						echo '<td>'.$res['note']['note'].'/'.$res['note']['sur'].'</td>';
						echo '<td>'.($res['note']['noteEdit']?$res['note']['noteEdit']:$res['note']['note']).'/'.$res['note']['sur'].'</td>';

						$link = "enseignant/".$_GET['qs'][1]."/user/".$res['id']."/quiz/".$res['note']['id']."/reponses";
						echo '<td><a href="'.$link.'">Voir</a></td>';
						echo '<td>'.$res['note']['datefin'].'</td>';
						echo '<td>'.round((strtotime($res['note']['datefin']) - strtotime($res['note']['datedebut']))/60,2).' min</td>';
					}else{
						echo '<td>-</td>';
						echo '<td>-</td>';
						echo '<td>-</td>';
						echo '<td>-</td>';
						echo '<td>-</td>';
					}
				echo '</tr>';
			}
		?>
	</table>
<!--	<input type="submit" class="submit" value="Sauvegarder"/>!-->
</form>