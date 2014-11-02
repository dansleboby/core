<form method="post" action="<?php echo $_GET['data']; ?>" class="openInMenuBar">

	<input type="hidden" name="action" value="saveNotes"/>
	<h1>Détails du devoir</h1>
		<div class="clr"></div>
		<a class="cancel" href="#">Annuler</a>

	<table style="width:100%;">
		<tr>
			<th>Utilisateurs</th>
			<th>Note</th>
			<th>Note ajustée</th>
			<th>Téléchargement</th>
			<th>Date reçu</th>
		</tr>
		<?php
			foreach($data['users'] AS $res) {
				echo '<tr>';
					echo '<th>'.$res['nom'].', '.$res['prenom'].'</th>';
					if ($res['note']['dateNote']){
						if ($res['note']['note'] === null){
							$lien = "enseignant/".$_GET['qs'][1]."/user/".$res['id']."/devoir/".$res['note']['id']."";
							echo '<td><a href="'.$lien.'" class="openInMenuBar">&hellip;</a></td>';
							echo '<td><a href="'.$lien.'" class="openInMenuBar">&hellip;</a></td>';
						}else{
							echo '<td>'.$res['note']['note'].'/'.$res['note']['sur'].'</td>';
							echo '<td>'.($res['note']['noteEdit']?$res['note']['noteEdit']:$res['note']['note']).'/'.$res['note']['sur'].'</td>';
						}
						$lien = "enseignant/".$_GET['qs'][1]."/user/".$res['id']."/devoir/".$res['note']['id']."/download";
						echo '<td><a href="'.$lien.'">Télécharger</a></td>';
						echo '<td>'.$res['note']['dateNote'].'</td>';
					}else{
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