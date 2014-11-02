<form method="post" action="<?php echo $_GET['data']; ?>" class="openInMenuBar">
	<?php
		if ($data['id']){
			?>
			<h1>Détails du devoir</h1>
			<div class="clr"></div>

			<?php
			echo '<span style="position:absolute;top:0;right:35px;">'.$data['file']['etat']." (".$data['date'].")</span>";

			echo "<strong>Commentaire de l'étudiant</strong><br/>";
			echo $data['description'];
			echo '<div class="clr"></div><br/>';

			if ($data['file']['etat'] == "recu"){
				echo '<a href="'.$_GET['data'].'/download" class="btn btn2x color_background">';
					echo "<strong>Télécharger le fichier</strong>";
					echo $data['file']['name']." (".humanFilesize($data['file']['location']).")";
				echo '</a>';

				echo '<a href="notifications/messages/'.$_GET['qs'][3].'" class="btn btn2x color_background openInRightBar">';
					echo "<strong>Contacter cet utilisateur</strong>";
					echo "Envoyer un message privé à cet utilisateur";
				echo '</a>';

				echo "<hr/>";
				echo "<h2>Noter le devoir</h2>";
				echo '<div class="clr"></div>';

				echo '<label class="label" for="note">Note</label>';
				echo '<input type="text" class="text small" name="note" id="note" value="'.(($data['note']>0)?$data['note']:'').'"/> / 100';

				echo '<div class="clr"></div>';
				echo '<input type="submit" value="Sauvegarder" class="btn btn2x color_background"/>';

//				echo '<a href="'.$_GET['data'].'/noter" class="btn btn2x color_background openInMenuBar">';
			}else{
				echo "--Fichier indisponible--";
			}
			?>

			<a class="cancel" href="#">Annuler</a>
			<?php
		}else{
			echo "Une erreur est survenue.";
		}
	?>
</form>