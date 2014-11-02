<form>
	<h1>Détails du Quiz</h1>
	<div class="clr"></div>
	<?php
		if ($data['id']){
			?>

			<?php
			echo '<span style="position:absolute;top:0;right:35px;">reçu le '.$data['datefin'].' ('.round((strtotime($data['datefin']) - strtotime($data['datedebut']))/60,2).' min)</span>';

			echo "Note reçue : <span style=\"font-size:2em;\">".$data['pointage']." / ".$data['valeur']."</span><br/>";

			echo "<br/><hr/><br/>";

			echo '<a href="'.$_GET['data'].'/reponses" class="btn btn2x color_background">';
				echo "<strong>Voir les réponses</strong>";
				echo "Afficher les réponses de l'utilisateur";
			echo '</a>';

			echo '<a href="notifications/messages/'.$_GET['qs'][3].'" class="btn btn2x color_background openInRightBar">';
				echo "<strong>Contacter cet utilisateur</strong>";
				echo "Envoyer un message privé à cet utilisateur";
			echo '</a>';


/*			echo '<a href="'.$_GET['data'].'/noter" class="btn color_background openInMenuBar">';
				echo "<strong>Modifier la note</strong>";
				echo "";
			echo '</a>';*/
			?>

			<?php
		}else{
			echo "Une erreur est survenue.";
		}
	?>
	<a class="cancel" href="#">Annuler</a>
</form>